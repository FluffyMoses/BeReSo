<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Edit
// included by ../index.php
// ###################################

// check if user is owner of this item
if (Item::is_owned_by_user($user,$item)) {
	// Edit item
	$item_edit_addmessage = null;
	$edit_name_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content
	$edit_text_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content

	// save edited entry
	if ($action == "edit")
	{
		// name is longer than 1 char -  no specialchars in name or text
		if (strlen($edit_name) > 0 && is_numeric($item) && $form_item_name_error == 0 && $form_item_text_error == 0) {
						
			// delete all tags and save the "new" one
			$sql->query("DELETE FROM bereso_tags WHERE tags_item='".$item."'");
			// save tags
			// add one whitespace character at the end for the regular expression to match when the last word is a hashtag!
			$edit_text = $edit_text . " ";
			preg_match_all("/(#\w+)\s/", $edit_text, $matches);
			for ($i=0;$i<count($matches[0]);$i++)
			{
				$matches[0][$i] = Text::remove_whitespace($matches[0][$i]); // remote whitespace
				$sql->query("INSERT into bereso_tags (tags_name, tags_item) VALUES ('".str_replace("#","",$matches[0][$i])."','".$item."')");
			}			
			
			// save name and text in item
			$sql->query("UPDATE bereso_item SET item_name='".$edit_name."', item_text='".$edit_text."', item_timestamp_edit='".$bereso['now']."' WHERE item_id='".$item."'");
			
			$item_edit_addmessage = "<font color=\"green\">(bereso_template-edit_entry_saved): <b>\"$edit_name\"</b></font>";		
		} 
		// form not correct
		else
		{
				if ($form_item_name_error == 1) { $item_edit_addmessage = "<font color=\"red\">(bereso_template-edit_entry_error_name_characters)</font>"; } // name wrong char
				elseif ($form_item_text_error == 1) { $item_edit_addmessage = "<font color=\"red\">(bereso_template-edit_entry_error_text_characters)</font>"; } // text wrong char
				else { $item_edit_addmessage = "<font color=\"red\">(bereso_template-edit_entry_error_name_missing)</font>"; } // name missing
				$edit_name_replace = $edit_name; // if set the form will replace the text with the variable content, not with the sql loaded content
				$edit_text_replace = $edit_text; // if set the form will replace the text with the variable content, not with the sql loaded content
		}	
		// load edit-form again with message success or failure
		$action = null;
	}
	
	
	// Delete Image (confirm form)
	if ($action == "delete_image")
	{		
		$content = File::read_file("templates/edit-image-delete.html");
		$content = str_replace("(bereso_edit_item_imagename)",Image::get_filename($item),$content);
		$content = str_replace("(bereso_edit_item_image_id)",$item_image_id,$content);
		$content = str_replace("(bereso_edit_item_id)",$item,$content);
		$content = str_replace("(bereso_edit_item_image_extension)",Image::get_fileextension($item,$item_image_id),$content);			
			
		// add to navigation
		$navigation .= File::read_file("templates/main-navigation-edit-delete.html");	
		$navigation = str_replace("(bereso_edit_item_id)",$item,$navigation);		
		
		// add to navigation -> Last item
		$navigation2 .= File::read_file("templates/main-navigation2-last_item.html");
		$navigation2 = str_replace("(main-navigation-last_item)",Image::get_filenamecomplete($item,0),$navigation2);
		$navigation2 = str_replace("(main-navigation-last_item_value)",$item,$navigation2);		
	}
	
	
	// Delete Image Confirmed 
	if ($action == "confirm_delete_image")
	{			
		if ($item_image_id >= 2) // only delete image >= 2
		{
			// change timestamp_edit
			$sql->query("UPDATE bereso_item SET item_timestamp_edit='".$bereso['now']."' WHERE item_id='".$item."'");
				
			unlink (Image::get_foldername_by_user_id(User::get_id_by_name($user)).Image::get_filenamecomplete($item,$item_image_id)); // delete file 
			$sql->query("DELETE FROM bereso_images WHERE images_item='".$item."' AND images_image_id='".$item_image_id."'"); // delete db record for that file
			$action = null; // Load Edit Form again	
		}
	}	

	// Turn Image
	if ($action == "turn_image_right" or $action == "turn_image_left")
	{
		if ($action == "turn_image_right") { $image_rotate_degrees = 270; } else { $image_rotate_degrees = 90; }
		$image_path = Image::get_foldername_by_user_id(User::get_id_by_name($user)) . Image::get_filenamecomplete($item,$item_image_id);

		// Load Jpg or Png
		if (Image::get_header_fileextension($image_path) == ".jpg") {
			$load_image = imagecreatefromjpeg($image_path);
			// rotate
			$rotate_image = imagerotate($load_image, $image_rotate_degrees, 0);
			imagejpeg($rotate_image,$image_path);
		}
		elseif (Image::get_header_fileextension($image_path) == ".png")
		{
			$load_image = imagecreatefrompng($image_path);
			// rotate
			$rotate_image = imagerotate($load_image, $image_rotate_degrees, 0);
			imagepng($rotate_image,$image_path);
		} 
		else {
			Log::die ("CHECK: edit image rotate - no jpg or png $image_path");
		}

		imagedestroy($load_image);
		imagedestroy($rotate_image);

		// change timestamp_edit
		$sql->query("UPDATE bereso_item SET item_timestamp_edit='".$bereso['now']."' WHERE item_id='".$item."'");					
		
		$action = null; // Load Edit Form again
	}	
	
	// Upload Image
	if ($action == "upload_image")
	{
		// check max_file_upload size
		if ($_SERVER['CONTENT_LENGTH'] < $bereso['max_upload_size'])
		{	
			if (file_exists($edit_photo['tmp_name'][$item_image_id])) // upload worked file exists
			{
				if (Image::get_header_fileextension($edit_photo['tmp_name'][$item_image_id]) == ".jpg" or Image::get_header_fileextension($edit_photo['tmp_name'][$item_image_id]) == ".png") // Fileextension ok
				{
						// delete "old" image file 
						@unlink (Image::get_foldername_by_user_id(User::get_id_by_name($user)).Image::get_filenamecomplete($item,$item_image_id)); 
						// delete image from database
						$sql->query("DELETE FROM bereso_images where images_item='".$item."' AND images_image_id='".$item_image_id."'");
						// insert new datase entry
						$sql->query("INSERT INTO bereso_images (images_item, images_image_id, images_fileextension) VALUES ('".$item."','".$item_image_id."','".Image::get_header_fileextension($edit_photo['tmp_name'][$item_image_id],false)."')");
						// Move and rename images
						move_uploaded_file($edit_photo['tmp_name'][$item_image_id], Image::get_foldername_by_user_id(User::get_id_by_name($user)) . Image::get_filename($item) . "_".$item_image_id.Image::get_header_fileextension($edit_photo['tmp_name'][$item_image_id]));
						// change timestamp_edit
						$sql->query("UPDATE bereso_item SET item_timestamp_edit='".$bereso['now']."' WHERE item_id='".$item."'");					
						// status message					
						$item_edit_addmessage = "<font color=\"green\">(bereso_template-edit_image_saved)</font>";	

						if($item_image_id == 0) // preview
						{
						// Resize Thumbnail
							$thumbnail_path = Image::get_foldername_by_user_id(User::get_id_by_name($user)) . Image::get_filenamecomplete($item,0);		
							$thumbnail_old_size=getimagesize($thumbnail_path); //[0] == width; [1] == height; [2] == type; (2 == JPEG; 3 == PNG)
							$thumbnail_new_height=$bereso['images_thumbnail_height']; 
							$thumbnail_new_width = round($thumbnail_old_size[0] / ($thumbnail_old_size[1] / $thumbnail_new_height),0); // oldsize_width / (oldsize_height / newsize height)
							if ($thumbnail_old_size[2] == 2) { $old_image = imagecreatefromjpeg($thumbnail_path); } // JPEG
							elseif ($thumbnail_old_size[2] == 3) { $old_image = imagecreatefrompng ($thumbnail_path); } // PNG
							$new_image = ImageCreateTrueColor($thumbnail_new_width,$thumbnail_new_height);
							imagecopyresized($new_image,$old_image,0,0,0,0,$thumbnail_new_width,$thumbnail_new_height,$thumbnail_old_size[0],$thumbnail_old_size[1]);
							if ($thumbnail_old_size[2] == 2) { imagejpeg($new_image,$thumbnail_path,95); } // JPG
							elseif ($thumbnail_old_size[2] == 3) { imagepng($new_image,$thumbnail_path);  }	// PNG
							imagedestroy($new_image);
							imagedestroy($old_image);
						}
				}
				else // Fileextension not ok
				{
					// error message
					$item_edit_addmessage = "<font color=\"red\">(bereso_template-edit_entry_error_filetype)</font>";
				}
			}					
		} 
		// max_file_upload size exceeded
		else
		{
			$item_edit_addmessage = "<font color=\"red\">(bereso_template-edit_entry_error_file (". ($bereso['max_upload_size']/1024/1024)." MB - ". $bereso['max_upload_size']." Bytes)</font>"; // max_file_upload size exceeded					
		}
		$action = null; // Load Edit Form again	
	}		
	
	// sharing toggle on/off
	if ($action == "share")
	{
		$item_sharing = Item::get_share_id($item);
		
		// item shared? => disable sharing
		if (strlen($item_sharing) > 0) 
		{
			// get old and new filename
			$old_filename = Image::get_filename($item);
			$new_filename = uniqid();
			
			// check if alle files are renamed correctly
			$error_rename = false;
			$error_rename_log = null;
			
			// rename all image files
			if ($result = $sql->query("SELECT images_image_id from bereso_images WHERE images_item='".$item."'"))
			{	
				while ($row = $result -> fetch_assoc())
				{
					$old_complete_filename = Image::get_filenamecomplete($item,$row['images_image_id']);
					$new_complete_filename = $new_filename . "_" . $row['images_image_id'] . Image::get_fileextension($item,$row['images_image_id']);					
					rename(Image::get_foldername_by_user_id(User::get_id_by_name($user)).$old_complete_filename,Image::get_foldername_by_user_id(User::get_id_by_name($user)).$new_complete_filename); // rename the file
					// check if rename failed
					if (!file_exists(Image::get_foldername_by_user_id(User::get_id_by_name($user)).$new_complete_filename)) { $error_rename = true; $error_rename_log .= "Renaming file: ".$old_complete_filename." to ".$new_complete_filename." - "; }
				}
			}
			// only change db if no rename error occured
			if ($error_rename == false)
			{
				// item shared - disable share - delete link + update new_filename in database
				$sql->query("UPDATE bereso_item SET item_shareid='', item_imagename='".$new_filename."' WHERE item_id='".$item."'");
			}
			else
			{
				// Log rename error and end script
				Log::die ("CHECK: rename item ".$item." (old imageid: ".$old_filename." - new imageid: ".$new_filename.") after end share: ".$error_rename_log);
			}
		}
		else // item not shared - enable sharing - create link
		{
			$sql->query("UPDATE bereso_item SET item_shareid='".uniqid()."' WHERE item_id='".$item."'");		
		}		
		// redirect back to show.php
		header('Location: ?module=show&item='.$item,true, 301 ); 
		exit(); // stops the rest of the script from running 
	}
	
	// favorite toggle on/off
	if ($action == "favorite")
	{
		$item_favorite = Item::get_favorite($item);
		
		// item favorite? => disable favorite
		if ($item_favorite == true) 
		{
			Item::set_favorite($item,false);
		}
		else
		{
			Item::set_favorite($item,true);
		}	
		// redirect back to show.php
		header('Location: ?module=show&item='.$item,true, 301 ); 
		exit(); // stops the rest of the script from running 
	}

	// Show form for item
	if ($action == null){
		if ($result = $sql->query("SELECT item_name, item_text from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_id='".$item."'"))
		{	
			$row = $result -> fetch_assoc();
			
			$content = File::read_file("templates/edit.html");	
			if (strlen($edit_name_replace) > 0) { $content = str_replace("(bereso_edit_item_name)",$edit_name_replace,$content); } else { $content = str_replace("(bereso_edit_item_name)",$row['item_name'],$content); } // if set the form will replace the text with the variable content, not with the sql loaded content
			if (strlen($edit_text_replace) > 0) { $content = str_replace("(bereso_edit_item_text)",$edit_text_replace,$content); } else { $content = str_replace("(bereso_edit_item_text)",$row['item_text'],$content); } // if set the form will replace the text with the variable content, not with the sql loaded content
			$content = str_replace("(bereso_edit_item_message)",$item_edit_addmessage,$content); // insert or clear message field
			
			// Images
			// Get highest image id - and show forms for alle images to this point and one more
			if ($result = $sql->query("SELECT images_image_id from bereso_images WHERE images_item='".$item."' ORDER BY images_image_id DESC LIMIT 0,1"))
			{	
				$row = $result -> fetch_assoc();
				$highest_image_id = $row['images_image_id'];
			}
			$content_edit_images = null;
			for ($i=0;$i<=$highest_image_id+1;$i++) // run $highest_image_id + 1 times - so the user can always add one image more via edit
			{
				if (strlen(Image::get_filenamecomplete($item,$i)) > 0 ) // image exists
				{
					$content_edit_images .= File::read_file("templates/edit-image.html");
					$content_edit_images = str_replace("(bereso_edit_item_image_image)",File::read_file("templates/edit-image-image.html"),$content_edit_images);
					if ($i > 1) // do not delete 0 and 1 (preview and first image)
					{
						$content_edit_images = str_replace("(bereso_edit_item_image_delete)",File::read_file("templates/edit-image-form-delete.html"),$content_edit_images);	
					}
					else
					{
						$content_edit_images = str_replace("(bereso_edit_item_image_delete)",null,$content_edit_images);	
					}
					$content_edit_images = str_replace("(bereso_edit_item_image_turn)",File::read_file("templates/edit-image-form-turn.html"),$content_edit_images);			
					$content_edit_images = str_replace("(bereso_edit_item_imagename)",Image::get_filename($item),$content_edit_images);
					$content_edit_images = str_replace("(bereso_edit_item_image_id)",$i,$content_edit_images);
					$content_edit_images = str_replace("(bereso_edit_item_image_extension)",Image::get_fileextension($item,$i),$content_edit_images);
					if ($i == 0) // text for the preview image
					{
						$content_edit_images = str_replace("(bereso_edit_item_image_description)","(bereso_template-edit_preview_image)",$content_edit_images);
						$content_edit_images = str_replace("(bereso_edit_item_image_number)",null,$content_edit_images);
					}
					else // text for every other image
					{
						$content_edit_images = str_replace("(bereso_edit_item_image_description)","(bereso_template-edit_item_page)",$content_edit_images);
						$content_edit_images = str_replace("(bereso_edit_item_image_number)",$i,$content_edit_images);
					}
				}
				else // no image, load empty placeholder
				{
					$content_edit_images .= File::read_file("templates/edit-image.html");
					$content_edit_images = str_replace("(bereso_edit_item_image_image)",null,$content_edit_images);
					$content_edit_images = str_replace("(bereso_edit_item_image_delete)",null,$content_edit_images);	
					$content_edit_images = str_replace("(bereso_edit_item_image_turn)",null,$content_edit_images);	
					$content_edit_images = str_replace("(bereso_edit_item_image_id)",$i,$content_edit_images);
					$content_edit_images = str_replace("(bereso_edit_item_image_description)","(bereso_template-edit_item_page)",$content_edit_images);
					$content_edit_images = str_replace("(bereso_edit_item_image_number)",$i,$content_edit_images);
				}
			}
			$content = str_replace("(bereso_edit_item_images)",$content_edit_images,$content);
			
			$content = str_replace("(bereso_edit_item_id)",$item,$content);

			// load alle hashtags and add all in the dropdown menu
			if ($result = $sql->query("SELECT DISTINCT bereso_tags.tags_name from bereso_tags INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".User::get_id_by_name($user)."' ORDER BY bereso_tags.tags_name ASC"))
			{	
				$insert_hashtag = null;
				while ($row = $result -> fetch_assoc())
				{
					$insert_hashtag .= File::read_file("templates/edit-hashtag.html");
					$insert_hashtag = str_replace("(bereso_edit_item_insert_hashtag_name)",$row['tags_name'],$insert_hashtag);
					$insert_hashtag = str_replace("(bereso_edit_item_insert_hashtag_value)","#".$row['tags_name']." ",$insert_hashtag);
				}
			}
			$content = str_replace("(bereso_edit_item_insert_hashtag)",$insert_hashtag,$content); // insert option tags of all hashtags 
			
			// add to navigation -> Last item
			$navigation2 .= File::read_file("templates/main-navigation2-last_item.html");
			$navigation2 = str_replace("(main-navigation-last_item)",Image::get_filenamecomplete($item,0),$navigation2);
			$navigation2 = str_replace("(main-navigation-last_item_value)",$item,$navigation2);
		
		}
	}
}
else
{
	Log::die ("CHECK: edit owner failed");
}