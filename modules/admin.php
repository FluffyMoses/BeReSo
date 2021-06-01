<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Admin
// included by ../index.php
// ###################################


// check if user is admin
if (User::is_admin($user))
{
	$admin_config_message = null; // init error/success message variable
	$admin_user_message = null; // init error/success message variable
	$admin_user_password_message = null; // init error/success message variable
	$copyitem_message = null; // init error/success message variable

	$user_name_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content
	$user_templates_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content
	$user_admin_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content
	$user_ocr_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content

	// list admincenter navigation
	if ($action == null)
	{
		$content = File::read_file("templates/admin.html");

		// Statistic information - replaces		
		$content = str_replace("(bereso_admin_center_statistic_user)",mysqli_num_rows($sql->query("SELECT * from bereso_user")),$content); // User count
		$content = str_replace("(bereso_admin_center_statistic_items)",mysqli_num_rows($sql->query("SELECT * from bereso_item")),$content); // Item count
		$content = str_replace("(bereso_admin_center_statistic_items_images)",mysqli_num_rows($sql->query("SELECT * from bereso_images")),$content); // Images count
		$content = str_replace("(bereso_admin_center_statistic_items_ocr)",mysqli_num_rows($sql->query("SELECT * from bereso_item WHERE item_ocr='1'")),$content); // Item OCR count
		$content = str_replace("(bereso_admin_center_statistic_items_shared)",mysqli_num_rows($sql->query("SELECT * from bereso_item WHERE LENGTH(item_shareid) > 0")),$content); // Item share count
		// calculate used disc space
		$disc_space = 0;
		if ($result = $sql->query("SELECT user_id FROM bereso_user"))
		{
			while($row = $result -> fetch_assoc())
			{
				$disc_space = $disc_space + File::get_directorysize(Image::get_foldername_by_user_id($row['user_id']),"MB");
			}
		}
		$content = str_replace("(bereso_admin_center_statistic_discspace)",$disc_space. " MB",$content); // Used discspace of all bereso users
	}

	// check if there is an missmatch between the items stored on the filesystem and the meta informations in the database
	if ($action == "checkimages")
	{
			$checkimages_content = null;
			$content = File::read_file("templates/admin-checkimages.html");

			// check every user - folder
			if ($result = $sql->query("SELECT user_id, user_name FROM bereso_user ORDER BY user_id ASC"))
			{
				while($row = $result -> fetch_assoc())
				{				
					// read files and check database
					$checkimages_content .= File::read_file("templates/admin-checkimages-readfile.html");
					$checkimages_content = str_replace("(berso_admin_checkimages_username)",$row['user_name'],$checkimages_content);
					$checkimages_content = str_replace("(berso_admin_checkimages_userid)",$row['user_id'],$checkimages_content);

					$files = scandir(Image::get_foldername_by_user_id($row['user_id']));
					
					$content_errors = null;
					$count = 0;

					//check files and search inside the database if the entry matches
					foreach ($files as $key => $value) {
						if ($value != "." and $value != ".."){
							if (Image::image_in_database($value) == false)
							{
								$content_errors .= File::read_file("templates/admin-checkimages-readfile-error.html");
								$content_errors = str_replace("(berso_admin_checkimages_filenamecomplete)",$value,$content_errors);	
								$content_errors = str_replace("(berso_admin_checkimages_folder)",Image::get_foldername_by_user_id($row['user_id']),$content_errors);
							}
							$count++;
						}
					}
					$checkimages_content = str_replace("(berso_admin_checkimages_readfile_content)",$content_errors,$checkimages_content);
					$checkimages_content = str_replace("(berso_admin_checkimages_count)",$count,$checkimages_content);

					// read database and check files
					$checkimages_content .= File::read_file("templates/admin-checkimages-readdatabase.html");
					$content_errors = null;
					$count = 0;

					$result2 = $sql->query("SELECT * from bereso_images  INNER JOIN bereso_item ON bereso_images.images_item = bereso_item.item_id WHERE bereso_item.item_user='".$row['user_id']."'");
					while($row2 = $result2 -> fetch_assoc())
					{
						if (!file_exists(Image::get_foldername_by_user_id($row['user_id']).Image::get_filenamecomplete($row2['item_id'],$row2['images_image_id'])))
						{
							$content_errors .= File::read_file("templates/admin-checkimages-readdatabase-error.html");
							$content_errors = str_replace("(berso_admin_checkimages_filenamecomplete)",Image::get_filenamecomplete($row2['item_id'],$row2['images_image_id']),$content_errors);	
							$content_errors = str_replace("(berso_admin_checkimages_folder)",Image::get_foldername_by_user_id($row['user_id']),$content_errors);	
							$content_errors = str_replace("(bereso_admin_checkimages_itemid)",$row2['item_id'],$content_errors);								
						}

						$count++;
					}
					$checkimages_content = str_replace("(berso_admin_checkimages_readdatabase_content)",$content_errors,$checkimages_content);
					$checkimages_content = str_replace("(berso_admin_checkimages_count)",$count,$checkimages_content);
				}								
			}
			$content = str_replace("(bereso_admin_checkimages_content)",$checkimages_content,$content);

			// add to navigation 2
			$navigation2 = File::read_file("templates/main-navigation2-admin_last_menu.html");
			$navigation2 = str_replace("(bereso_admin_last_menu_link)","?module=admin",$navigation2);
	}



	// Copy item to another user - same as import - but uses item id and not shareid
	// save item and show form again afterwards
	if ($action == "savecopyitem")
	{
		// Load item details
		if ($result = $sql->query("SELECT item_id, item_name, item_text, item_user, item_imagename from bereso_item WHERE item_id='".$bereso_sourceitemid."'"))
		{	
			$row = $result -> fetch_assoc();
	
			// if entry with this item id exists
			if (mysqli_num_rows($result) == 1 && $bereso_targetuserid > 0)
			{		
				// new unique ids for imagename 
				$add_uniqueid = uniqid();

				$sql->query("INSERT into bereso_item (item_name, item_text,item_user, item_imagename, item_timestamp_creation, item_timestamp_edit) VALUES ('".$row['item_name']."','".$row['item_text']."','".$bereso_targetuserid."','".$add_uniqueid."','".$bereso['now']."','".$bereso['now']."')");
				$add_id = $sql->insert_id;
			
				// save tags
				// add one whitespace character at the end for the regular expression to match when the last word is a hashtag!
				$row['item_text'] = $row['item_text'] . " ";
				preg_match_all("/(#\w+)\s/", $row['item_text'], $matches);
				for ($i=0;$i<count($matches[0]);$i++)
				{
					$matches[0][$i] = Text::remove_whitespace($matches[0][$i]); // remove whitespace
					$sql->query("INSERT into bereso_tags (tags_name, tags_item) VALUES ('".str_replace("#","",$matches[0][$i])."','".$add_id."')");
				}		
		
				// copy image files to new imagename
				if ($result2 = $sql->query("SELECT images_image_id from bereso_images WHERE images_item='".$row['item_id']."'"))
				{	
					while ($row2 = $result2 -> fetch_assoc())
					{
						$old_file = Image::get_foldername_by_user_id($row['item_user']).Image::get_filenamecomplete($row['item_id'],$row2['images_image_id']);
						$new_file = Image::get_foldername_by_user_id($bereso_targetuserid).$add_uniqueid."_".$row2['images_image_id'].Image::get_fileextension($row['item_id'],$row2['images_image_id']);
						copy($old_file,$new_file); // copy image
						$sql->query("INSERT into bereso_images (images_item, images_image_id, images_fileextension) VALUES ('".$add_id."','".$row2['images_image_id']."','".Image::get_fileextension($row['item_id'],$row2['images_image_id'],false)."')");
					}
				}

				Log::useraction($user,$module,$action,"Copied ".$row['item_id']." (User: ".$row['item_user'].") to $add_id (User: ".$bereso_targetuserid.")");  // log when user_log enabled
				$copyitem_message = "<div id=\"messagepopup\" style=\"background: green;\"><font color=\"white\">(bereso_template-admin_copyitem_successful) ID:".$row['item_id']." (User ID: ".$row['item_user'].") -&gt; ID: $add_id (User ID: $bereso_targetuserid)</font></div>";
			}
			// item does not exist
			else 
			{
				$copyitem_message = "<div id=\"messagepopup\" style=\"background: red;\"><font color=\"white\">(bereso_template-admin_copyitem_error)</font></div>";
			}
		}

		// activate the messagepopup
		$copyitem_message .= "\n<script src=\"templates/js/show_messagepopup.js\"></script>\n";

		$action = "copyitem"; // show form again
	}


	// form for copyitem
	if ($action == "copyitem")
	{

		// show form
		$content = File::read_file("templates/admin-copyitem.html");
		$content = str_replace("(bereso_admin_copyitem_message)",$copyitem_message,$content);
		// load users into dropdown menu
		$content_users = null;
		if ($result = $sql->query("SELECT user_id, user_name FROM bereso_user ORDER BY user_name ASC"))
		{
			while($row = $result -> fetch_assoc())
			{
				$content_users .= File::read_file("templates/admin-copyitem-useritem.html");
				$content_users = str_replace("(bereso_admin_users_id)",$row['user_id'],$content_users);
				$content_users = str_replace("(bereso_admin_users_name)",$row['user_name'],$content_users);
			}
		}
		$content = str_replace("(bereso_admin_users)",$content_users,$content);

		// add to navigation 2
		$navigation2 = File::read_file("templates/main-navigation2-admin_last_menu.html");
		$navigation2 = str_replace("(bereso_admin_last_menu_link)","?module=admin",$navigation2);
	}


	// list users
	if ($action == "users")
	{
		// add to navigation
		$navigation .= File::read_file("templates/main-navigation-admin-users.html");	

		// List users
		$content = File::read_file("templates/admin-users.html");
		$content_items = null;
		if ($result = $sql->query("SELECT user_id, user_name, user_template, user_admin, user_ocr FROM bereso_user ORDER BY user_name ASC"))
		{
			while($row = $result -> fetch_assoc())
			{
				$content_items .= File::read_file("templates/admin-users-item.html");
				$content_items = str_replace("(bereso_admin_users_id)",$row['user_id'],$content_items);
				$content_items = str_replace("(bereso_admin_users_name)",$row['user_name'],$content_items);
				$content_items = str_replace("(bereso_admin_users_template)",User::get_template_name($row['user_name']),$content_items);
				$content_items = str_replace("(bereso_admin_users_items)",Item::get_number($row['user_name']),$content_items);
				$content_items = str_replace("(bereso_admin_users_images)",mysqli_num_rows($sql->query("SELECT * from bereso_images  INNER JOIN bereso_item ON bereso_images.images_item = bereso_item.item_id WHERE bereso_item.item_user='".$row['user_id']."'")),$content_items);
				$content_items = str_replace("(bereso_admin_users_diskspace)",File::get_directorysize(Image::get_foldername_by_user_id($row['user_id']),"MB"). " MB",$content_items);
				if ($row['user_admin'] == 1) { $user_admin = "(bereso_template-admin_users_yes)"; } else { $user_admin = "(bereso_template-admin_users_no)"; }
				$content_items = str_replace("(bereso_admin_users_admin)",$user_admin,$content_items);
				if ($row['user_ocr'] == 1) { $user_ocr = "(bereso_template-admin_users_yes)"; } else { $user_ocr = "(bereso_template-admin_users_no)"; }
				$content_items = str_replace("(bereso_admin_users_ocr)",$user_ocr,$content_items);
			}
		}
		
		$content = str_replace("(bereso_admin_users_item)",$content_items,$content); // insert items into table

		// add to navigation 2
		$navigation2 = File::read_file("templates/main-navigation2-admin_last_menu.html");
		$navigation2 = str_replace("(bereso_admin_last_menu_link)","?module=admin",$navigation2);
	}


	// save new user
	if ($action == "new_user_add")
	{
		// all values ok
		if ($form_user_error == 0 && $form_user_password_error == 0) 
		{
			// if user not exists
			if (!User::get_id_by_name($user_name))
			{
				// password hash
				$user_password_hash = User::generate_password_hash($user_password);

				// user ocr 
				if ($user_ocr == "new_ocr") { $user_ocr = 1; } else { $user_ocr = 0; }

				// user admin 
				if ($user_admin == "new_admin") { $user_admin = 1; } else { $user_admin = 0; }

				// save user	
				$sql->query("INSERT into bereso_user (user_name, user_pwhash, user_template, user_admin, user_ocr) VALUES ('".$user_name."','".$user_password_hash."','".$user_templates."','".$user_admin."','".$user_ocr."')");
				$add_id = $sql->insert_id;

				// create user directory
				mkdir(Image::get_foldername_by_user_id($add_id));

				$admin_user_message = "<div id=\"messagepopup\" style=\"background: green;\"><font color=\"white\">(bereso_template-admin_users_new_saved) <b>\"$user_name\"</b></font></div>"; // success message

				Log::useraction($user,$module,$action,"Added user " . User::get_name_by_id($add_id) . " (".$add_id.")");  // log when user_log enabled

				// reset all buffer variables 
				$user_name = null;
				$user_password = null;
				$user_templates = null;
				$user_admin = null;
				$user_ocr = null;
			}
			else // user already exists
			{
				$admin_user_message = "<div id=\"messagepopup\" style=\"background: red;\"><font color=\"white\">(bereso_template-admin_users_new_user_exists)</font></div>"; // error message
				Log::useraction($user,$module,$action,"Adding user " . $user_name . " failed - User exists");  // log when user_log enabled
			}
		}
		else
		{
			$admin_user_message = "<div id=\"messagepopup\" style=\"background: red;\"><font color=\"white\">(bereso_template-admin_users_new_user_error_missing)</font></div>"; // error message
			Log::useraction($user,$module,$action,"Adding user failed: Wrong characters or empty");  // log when user_log enabled
		}

		// activate the messagepopup
		$admin_user_message .= "\n<script src=\"templates/js/show_messagepopup.js\"></script>\n";

		// load new user form after saving or error
		$action = "new_user";
	}


	// new user
	if ($action == "new_user")
	{
		$content = File::read_file("templates/admin-users-new.html");
		$content = str_replace("(bereso_admin_users_new_message)",$admin_user_message,$content);

		// on error set last inputs again
		$content = str_replace("(bereso_admin_users_new_name)",$user_name,$content); // if entry is saved with errors - show name again
		$content = str_replace("(bereso_admin_users_new_password)",$user_password,$content); // if entry is saved with errors - show password again
		for ($i=1;$i<=4;$i++) // run through all templates
		{
			if ($i == $user_templates) { $content = str_replace("(bereso_admin_users_new_template_".$i.")","selected",$content); } else { $content = str_replace("(bereso_admin_users_new_template_".$i.")",null,$content); } // if entry is saved with errors - select last template
		}
		if ($user_ocr == "new_ocr") { $content = str_replace("(bereso_admin_users_new_ocr)","checked",$content); } else { $content = str_replace("(bereso_admin_users_new_ocr)",null,$content); }
		if ($user_admin == "new_admin") { $content = str_replace("(bereso_admin_users_new_admin)","checked",$content); } else { $content = str_replace("(bereso_admin_users_new_admin)",null,$content); }

		// add to navigation 2
		$navigation2 = File::read_file("templates/main-navigation2-admin_last_menu.html");
		$navigation2 = str_replace("(bereso_admin_last_menu_link)","?module=admin&action=users",$navigation2);
	}


	// edit password save
	if ($action == "edit_user_password_save")
	{
		// save new password - password not empty and all chars ok
		if ($form_user_password_error == 0)
		{
			// password hash
			$user_password_hash = User::generate_password_hash($user_password);
			
			// save in database
			$sql->query("UPDATE bereso_user SET user_pwhash='".$user_password_hash."' WHERE user_id='".$user_id."'");

			$admin_user_password_message = "<div id=\"messagepopup\" style=\"background: green;\"><font color=\"white\">(bereso_template-admin_users_edit_password_saved)</font></div>"; // error message

			Log::useraction($user,$module,$action,"Edited user password " . User::get_name_by_id($user_id) . " (".$user_id.")");  // log when user_log enabled

			// reset all buffer variables 
			$user_password = null;
		} 
		else // password empty or contains forbidden characters
		{
			$admin_user_password_message = "<div id=\"messagepopup\" style=\"background: red;\"><font color=\"white\">(bereso_template-admin_users_edit_password_error)</font></div>"; // error message
			Log::useraction($user,$module,$action,"Editing user password " . User::get_name_by_id($user_id) . " (".$user_id.") failed: Wrong characters or empty");  // log when user_log enabled
		}

		// activate the messagepopup
		$admin_user_password_message .= "\n<script src=\"templates/js/show_messagepopup.js\"></script>\n";

		// load edit user form after saving or error
		$action = "edit_user";
	}


	// edit save
	if ($action == "edit_user_save")
	{
		// name not empty and all chars ok
		if ($form_user_error == 0)
		{

			// user ocr 
			if ($user_ocr == "edit_ocr") { $user_ocr = 1; } else { $user_ocr = 0; }

			// user admin 
			if ($user_admin == "edit_admin") { $user_admin = 1; } else { $user_admin = 0; }
			
			// save in database
			$sql->query("UPDATE bereso_user SET user_name='".$user_name."', user_template='".$user_templates."', user_admin='".$user_admin."', user_ocr='".$user_ocr."' WHERE user_id='".$user_id."'");

			$admin_user_message = "<div id=\"messagepopup\" style=\"background: green;\"><font color=\"white\">(bereso_template-admin_users_edit_saved)</font></div>"; // success message

			// reset all buffer variables 
			$user_name = null;
			$user_templates = null;
			$user_admin = null;
			$user_ocr = null;

			Log::useraction($user,$module,$action,"Editing user " . User::get_name_by_id($user_id) . " (".$user_id.")");  // log when user_log enabled
		} 
		else // empty or contains forbidden characters
		{
			$admin_user_message = "<div id=\"messagepopup\" style=\"background: red;\"><font color=\"white\">(bereso_template-admin_users_edit_user_error_missing)</font></div>"; // error message

			// set replace variables
			if ($user_ocr == "edit_ocr") { $user_ocr_replace = 1; } else { $user_ocr_replace = 0; }
			if ($user_admin == "edit_admin") { $user_admin_replace = 1; } else { $user_admin_replace = 0; } 
			$user_name_replace = $user_name; 
			$user_templates_replace = $user_templates; 

			Log::useraction($user,$module,$action,"Editing user  " . User::get_name_by_id($user_id) . " (".$user_id.") failed: Wrong characters or empty");  // log when user_log enabled


		}	
		
		// activate the messagepopup
		$admin_user_message .= "\n<script src=\"templates/js/show_messagepopup.js\"></script>\n";

		// load edit user form after saving or error
		$action = "edit_user";
	}


	// edit user
	if ($action == "edit_user")
	{
		if ($result = $sql->query("SELECT user_id, user_name, user_template, user_admin, user_ocr FROM bereso_user WHERE user_id='".$user_id."'"))
		{
			$row = $result -> fetch_assoc();
	
			$content = File::read_file("templates/admin-users-edit.html"); // load template
	
			// messages
			$content = str_replace("(bereso_admin_users_edit_message)",$admin_user_message,$content);
			$content = str_replace("(bereso_admin_users_edit_password_message)",$admin_user_password_message,$content);

			$content = str_replace("(bereso_admin_users_edit_user_id)",$row['user_id'],$content);
			if (strlen($user_name_replace) > 0) { $content = str_replace("(bereso_admin_users_edit_name)",$user_name_replace,$content); } else { $content = str_replace("(bereso_admin_users_edit_name)",$row['user_name'],$content); }
			if (strlen($user_templates_replace) > 0) { $user_templates = $user_templates_replace; } else { $user_templates = $row['user_template']; }
			for ($i=1;$i<=4;$i++) // run through all templates
			{
				if ($i == $user_templates) { $content = str_replace("(bereso_admin_users_edit_template_".$i.")","selected",$content); } else { $content = str_replace("(bereso_admin_users_edit_template_".$i.")",null,$content); } // load template and select
			}
			if (strlen($user_ocr_replace) > 0) { $user_ocr = $user_ocr_replace; } else { $user_ocr = $row['user_ocr']; }
			if ($user_ocr == "1") { $content = str_replace("(bereso_admin_users_edit_ocr)","checked",$content); } else { $content = str_replace("(bereso_admin_users_edit_ocr)",null,$content); } // check if ocr is set
			if (strlen($user_admin_replace) > 0) { $user_admin = $user_admin_replace; } else { $user_admin = $row['user_admin']; }
			if ($user_admin == "1") { $content = str_replace("(bereso_admin_users_edit_admin)","checked",$content); } else { $content = str_replace("(bereso_admin_users_edit_admin)",null,$content); } // check if ocr is set
			$content = str_replace("(bereso_admin_users_edit_password)",$user_password,$content);
		}		

		// add to navigation 2
		$navigation2 = File::read_file("templates/main-navigation2-admin_last_menu.html");
		$navigation2 = str_replace("(bereso_admin_last_menu_link)","?module=admin&action=users",$navigation2);
	}


	// delete user - first confirm again
	if ($action == "delete_user") 
	{ 
		$content = File::read_file("templates/admin-users-delete.html");
		$content = str_replace("(bereso_delete_user_name)",User::get_name_by_id($user_id),$content);
		$content = str_replace("(bereso_delete_user_id)",$user_id,$content);

		// add to navigation 2
		$navigation2 = File::read_file("templates/main-navigation2-admin_last_menu.html");
		$navigation2 = str_replace("(bereso_admin_last_menu_link)","?module=admin&action=users",$navigation2);
	}


	// delete user - confirmed
	if ($action == "delete_user_confirm") 
	{ 
		Log::useraction($user,$module,$action,"Deleted user " . User::get_name_by_id($user_id) . " (".$user_id.")");  // log when user_log enabled

		// delete files and folder
		File::delete_directory(Image::get_foldername_by_user_id($user_id));

		// delete database entries
		$sql->query("DELETE bereso_images from bereso_images INNER JOIN bereso_item ON bereso_images.images_item = bereso_item.item_id WHERE bereso_item.item_user='".$user_id."'"); // delete all images in database
		$sql->query("DELETE bereso_tags FROM bereso_tags INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".$user_id."'"); // delete tags in database
		$sql->query("DELETE FROM bereso_item WHERE item_user='".$user_id."'"); // delete all items in database
		$sql->query("DELETE FROM bereso_group WHERE group_user='".$user_id."'"); // delete all tag groups in database
		$sql->query("DELETE FROM bereso_user WHERE user_id='".$user_id."'"); // delete user in database

		// redirect back to users in admin.php
		header('Location: index.php?module=admin&action=users',true, 302 ); 
	}


	// BeReSo configuration
	if ($action == "saveconfig") // save configuration
	{
		// if all post variables are ok
		if ($form_config_error == 0)
		{
			// save all configs
			Config::set_config("url",$bereso_url);
			if ($bereso_httpsredirect == "httpsredirect") { $bereso_httpsredirect = 1; } else { $bereso_httpsredirect = 0; } // if checked set to 1 - else 0
			Config::set_config("https_redirect",$bereso_httpsredirect);
			if ($bereso_user_log == "user_log") { $bereso_user_log = 1; } else { $bereso_user_log = 0; } // if checked set to 1 - else 0			
			Config::set_config("user_log",$bereso_user_log);
			if ($bereso_agent_ocr_log == "agent_ocr_log") { $bereso_agent_ocr_log = 1; } else { $bereso_agent_ocr_log = 0; } // if checked set to 1 - else 0			
			Config::set_config("agent_ocr_log",$bereso_agent_ocr_log);
			Config::set_config("https_redirect",$bereso_httpsredirect);
			Config::set_config("images",$bereso_images);
			Config::set_config("images_thumbnail_height",$bereso_images_thumbnail_height);
			Config::set_config("timezone",$bereso_timezone);
			Config::set_config("datetimestring",$bereso_datetime);
			Config::set_config("session_lifetime",$bereso_sessionlifetime);
			Config::set_config("new_amount_images",$bereso_new_amount_images);
			Config::set_config("ocr_password",$bereso_ocr_password);
			if ($bereso_ocr_enabled == "ocr_enabled") { $bereso_ocr_enabled = 1; } else { $bereso_ocr_enabled = 0; } // if checked set to 1 - else 0
			Config::set_config("ocr_enabled",$bereso_ocr_enabled);
			Config::set_config("login_motd",$bereso_login_motd);

			// return message
			$admin_config_message = "<div id=\"messagepopup\" style=\"background: green;\"><font color=\"white\">(bereso_template-admin_config_saved)</font></div>";

			Log::useraction($user,$module,$action,"Saved config");  // log when user_log enabled
		}
		else // wrong characters in post variables
		{
			// return message
			$admin_config_message = "<div id=\"messagepopup\" style=\"background: red;\"><font color=\"white\">(bereso_template-admin_config_error_text_characters)</font></div>";

			Log::useraction($user,$module,$action,"Saving config failed - wrong characters");  // log when user_log enabled
		}

		// set config in running script
		$bereso['url'] = $bereso_url;
		if ($bereso_httpsredirect == 1 or $bereso_httpsredirect == "httpsredirect") { $bereso['https_redirect'] = true; } else { $bereso['https_redirect'] = false; }
		$bereso['images'] = $bereso_images;
		$bereso['images_thumbnail_height'] = $bereso_images_thumbnail_height;
		$bereso['timezone'] = $bereso_timezone;
		$bereso['datetimestring'] = $bereso_datetime;
		$bereso['session_lifetime'] = $bereso_sessionlifetime;
		$bereso['new_amount_images'] = $bereso_new_amount_images;

		// buffer the rest of the config settings that have no $bereso value - if the script cannot save it displays the text/value last submitted instead of the db value
		$buffer['ocr_enabled'] = $bereso_ocr_enabled;
		$buffer['user_log'] = $bereso_user_log;
		$buffer['agent_ocr_log'] = $bereso_agent_ocr_log;
		$buffer['ocr_password'] = $bereso_ocr_password;
		$buffer['login_motd'] = $bereso_login_motd;
		
		// activate the messagepopup
		$admin_config_message .= "\n<script src=\"templates/js/show_messagepopup.js\"></script>\n";

		// set action to config to load configuration management after saving
		$action = "config";
	}
	

	// show and edit configuration
	if ($action == "config") 
	{
		$content = File::read_file("templates/admin-config.html");

		// if form was not submitted or configuration saved - load some values direct from database - else show the posted value
		if (!isset($buffer))
		{
			$buffer['ocr_enabled'] = Config::get_config("ocr_enabled");
			$buffer['user_log'] = Config::get_config("user_log");
			$buffer['agent_ocr_log'] = Config::get_config("agent_ocr_log");
			$buffer['ocr_password'] = Config::get_config("ocr_password");
			$buffer['login_motd'] = Config::get_config("login_motd");
		}

		// insert config values in template
		$content = str_replace("(bereso_admin_config_url)",$bereso['url'],$content);
		if ($bereso['https_redirect'] == true) { $content = str_replace("(bereso_admin_config_httpsredirect)","checked",$content); } else { $content = str_replace("(bereso_admin_config_httpsredirect)",null,$content); }
		if ($buffer['user_log'] == true) { $content = str_replace("(bereso_admin_config_user_log)","checked",$content); } else { $content = str_replace("(bereso_admin_config_user_log)",null,$content); }
		if ($buffer['agent_ocr_log'] == true) { $content = str_replace("(bereso_admin_config_agent_ocr_log)","checked",$content); } else { $content = str_replace("(bereso_admin_config_agent_ocr_log)",null,$content); }
		$content = str_replace("(bereso_admin_config_images)",$bereso['images'],$content);
		$content = str_replace("(bereso_admin_config_images_thumbnail_height)",$bereso['images_thumbnail_height'],$content);
		$content = str_replace("(bereso_admin_config_timezone)",$bereso['timezone'],$content);
		$content = str_replace("(bereso_admin_config_datetime)",$bereso['datetimestring'],$content);
		$content = str_replace("(bereso_admin_config_sessionlifetime)",$bereso['session_lifetime'],$content);
		$content = str_replace("(bereso_admin_config_new_amount_images)",$bereso['new_amount_images'],$content);
		$content = str_replace("(bereso_admin_config_message)",$admin_config_message,$content);
		$content = str_replace("(bereso_admin_config_ocr_password)",$buffer['ocr_password'],$content);
		if ($buffer['ocr_enabled'] == true or $buffer['ocr_enabled'] == "1") { $content = str_replace("(bereso_admin_config_ocr_enabled)","checked",$content); } else { $content = str_replace("(bereso_admin_config_ocr_enabled)",null,$content); }
		$content = str_replace("(bereso_admin_config_login_motd)",$buffer['login_motd'],$content);

		// add to navigation 2
		$navigation2 = File::read_file("templates/main-navigation2-admin_last_menu.html");
		$navigation2 = str_replace("(bereso_admin_last_menu_link)","?module=admin",$navigation2);
	}
	
}
else // User is not an admin
{
	Log::die ("CHECK: user is no admin");
}

?>

