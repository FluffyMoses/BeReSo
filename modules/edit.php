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
			preg_match_all("/(#\w+)/", $edit_text, $matches);
			for ($i=0;$i<count($matches[0]);$i++)
			{
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
		if ($result = $sql->query("SELECT item_imagename from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_id='".$item."'"))
		{	
			$row = $result -> fetch_assoc();			
			$content = File::read_file("templates/edit-image-delete.txt");
			$content = str_replace("(bereso_edit_item_imagename)",$row['item_imagename'],$content);
			$content = str_replace("(bereso_edit_item_image_id)",$item_image_id,$content);
			$content = str_replace("(bereso_edit_item_id)",$item,$content);
			$content = str_replace("(bereso_edit_item_image_extension)",Image::search_extension($bereso['images'].$row['item_imagename']."_".$item_image_id),$content);
			
			
			// add to navigation
			$navigation .= File::read_file("templates/edit-navigation-delete.txt");	
			$navigation = str_replace("(bereso_edit_item_id)",$item,$navigation);			
		}
	}
	
	
	// Delete Image Confirmed 
	if ($action == "confirm_delete_image")
	{
		if ($result = $sql->query("SELECT item_imagename from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_id='".$item."'"))
		{	
			$row = $result -> fetch_assoc();			
			if ($item_image_id >= 2 and $item_image_id < 6) // only delete image 2-5
			{
				// change timestamp_edit
				$sql->query("UPDATE bereso_item SET item_timestamp_edit='".$bereso['now']."' WHERE item_id='".$item."'");
				
				@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.Image::search_extension($bereso['images'].$row['item_imagename']."_".$item_image_id)); // delete file 			
				$action = null; // Load Edit Form again	
			}
		}
	}	

	// Turn Image
	if ($action == "turn_image_right" or $action == "turn_image_left")
	{
		if ($result = $sql->query("SELECT item_imagename from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_id='".$item."'"))
		{	
			$row = $result -> fetch_assoc();		
			
			if ($action == "turn_image_right") { $image_rotate_degrees = 270; } else { $image_rotate_degrees = 90; }
			$image_path = $bereso['images'] . $row['item_imagename'] . "_".$item_image_id.Image::search_extension($bereso['images'] . $row['item_imagename'] . "_".$item_image_id);

			// Load Jpg or Png
			if (Image::get_extension($image_path) == ".jpg") {
				$load_image = imagecreatefromjpeg($image_path);
				// rotate
				$rotate_image = imagerotate($load_image, $image_rotate_degrees, 0);
				imagejpeg($rotate_image,$image_path);
			}
			elseif (Image::get_extension($image_path) == ".png")
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
		}
		$action = null; // Load Edit Form again
	}	
	
	// Upload Image
	if ($action == "upload_image")
	{
		// check max_file_upload size
		if ($_SERVER['CONTENT_LENGTH'] < $bereso['max_upload_size'])
		{		
			if ($result = $sql->query("SELECT item_imagename from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_id='".$item."'"))
			{	
				$row = $result -> fetch_assoc();		
			

				// Thumbnail upload - needs resizing!
				if ($item_image_id == 0 && file_exists($edit_photo0['tmp_name']))
				{
					// Check Fileextensions				
					if (!(Image::get_extension($edit_photo0['tmp_name']) == ".jpg" or Image::get_extension($edit_photo0['tmp_name']) == ".png")) 
					{
						$item_edit_addmessage = "<font color=\"red\">(bereso_template-edit_entry_error_filetype)</font>"; // fileextension
					}
					else
					{					
						// delete "old" image file 
						@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".jpg"); 
						@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".png"); 

						// Resize Thumbnail
						$thumbnail_path = $bereso['images'] . $row['item_imagename'] . "_0".Image::get_extension($edit_photo0['tmp_name']);
						$thumbnail_old_size=getimagesize($edit_photo0['tmp_name']); //[0] == width; [1] == height; [2] == type; (2 == JPEG; 3 == PNG)
						$thumbnail_new_height=$bereso['images_thumbnail_height']; 
						$thumbnail_new_width = round($thumbnail_old_size[0] / ($thumbnail_old_size[1] / $thumbnail_new_height),0); // oldsize_width / (oldsize_height / newsize height)
						if ($thumbnail_old_size[2] == 2) { $old_image = imagecreatefromjpeg($edit_photo0['tmp_name']); } // JPEG
						elseif ($thumbnail_old_size[2] == 3) { $old_image = imagecreatefrompng ($edit_photo0['tmp_name']); } // PNG
						$new_image = ImageCreateTrueColor($thumbnail_new_width,$thumbnail_new_height);
						imagecopyresized($new_image,$old_image,0,0,0,0,$thumbnail_new_width,$thumbnail_new_height,$thumbnail_old_size[0],$thumbnail_old_size[1]);
						if ($thumbnail_old_size[2] == 2) { imagejpeg($new_image,$thumbnail_path,95); } // JPG
						elseif ($thumbnail_old_size[2] == 3) { imagepng($new_image,$thumbnail_path);  }	// PNG
						imagedestroy($new_image);
						imagedestroy($old_image);		

						// change timestamp_edit
						$sql->query("UPDATE bereso_item SET item_timestamp_edit='".$bereso['now']."' WHERE item_id='".$item."'");					
					
						$item_edit_addmessage = "<font color=\"green\">(bereso_template-edit_image_saved)</font>";	
					}
				}
				else // other item images upload
				{
					if ($item_image_id == 1 && file_exists($edit_photo1['tmp_name']))
					{
						// Check Fileextensions
						if (!(Image::get_extension($edit_photo1['tmp_name']) == ".jpg" or Image::get_extension($edit_photo1['tmp_name']) == ".png"))
						{
							$item_edit_addmessage = "<font color=\"red\">(bereso_template-edit_entry_error_filetype)</font>";  // fileextension					
						}
						else 
						{
							// delete "old" image file 
							@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".jpg"); 
							@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".png"); 
							// Copy File
							move_uploaded_file($edit_photo1['tmp_name'], $bereso['images'] . $row['item_imagename'] . "_1".Image::get_extension($edit_photo1['tmp_name']));		
						
							// change timestamp_edit
							$sql->query("UPDATE bereso_item SET item_timestamp_edit='".$bereso['now']."' WHERE item_id='".$item."'");			
						
							$item_edit_addmessage = "<font color=\"green\">(bereso_template-edit_image_saved)</font>";							
						}
					}
					elseif ($item_image_id == 2 && file_exists($edit_photo2['tmp_name']))
					{
						// Check Fileextensions
						if (!(Image::get_extension($edit_photo2['tmp_name']) == ".jpg" or Image::get_extension($edit_photo2['tmp_name']) == ".png")) 
						{
							$item_edit_addmessage = "<font color=\"red\">(bereso_template-edit_entry_error_filetype)</font>";  // fileextension					
						}
						else
						{
							// delete "old" image file 
							@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".jpg"); 
							@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".png"); 						
							// Copy File						
							move_uploaded_file($edit_photo2['tmp_name'], $bereso['images'] . $row['item_imagename'] . "_2".Image::get_extension($edit_photo2['tmp_name']));			
												
							// change timestamp_edit
							$sql->query("UPDATE bereso_item SET item_timestamp_edit='".$bereso['now']."' WHERE item_id='".$item."'");		
						
							$item_edit_addmessage = "<font color=\"green\">(bereso_template-edit_image_saved)</font>";	
						}
					}
					elseif ($item_image_id == 3 && file_exists($edit_photo3['tmp_name']))
					{
						// Check Fileextensions
						if (!(Image::get_extension($edit_photo3['tmp_name']) == ".jpg" or Image::get_extension($edit_photo3['tmp_name']) == ".png"))
						{
							$item_edit_addmessage = "<font color=\"red\">(bereso_template-edit_entry_error_filetype)</font>";  // fileextension					
						}
						else
						{
							// delete "old" image file 
							@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".jpg"); 
							@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".png"); 							
							// Copy File						
							move_uploaded_file($edit_photo3['tmp_name'], $bereso['images'] . $row['item_imagename'] . "_3".Image::get_extension($edit_photo3['tmp_name']));	
						
							// change timestamp_edit
							$sql->query("UPDATE bereso_item SET item_timestamp_edit='".$bereso['now']."' WHERE item_id='".$item."'");		
						
							$item_edit_addmessage = "<font color=\"green\">(bereso_template-edit_image_saved)</font>";						
							}
					}
					elseif ($item_image_id == 4 && file_exists($edit_photo4['tmp_name']))
					{
						// Check Fileextensions
						if (!(Image::get_extension($edit_photo4['tmp_name']) == ".jpg" or Image::get_extension($edit_photo4['tmp_name']) == ".png"))
						{
							$item_edit_addmessage = "<font color=\"red\">(bereso_template-edit_entry_error_filetype)</font>";  // fileextension					
						}
						else
						{
							// delete "old" image file 
							@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".jpg"); 
							@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".png"); 							
							// Copy File						
							move_uploaded_file($edit_photo4['tmp_name'], $bereso['images'] . $row['item_imagename'] . "_4".Image::get_extension($edit_photo4['tmp_name']));	
						
							// change timestamp_edit
							$sql->query("UPDATE bereso_item SET item_timestamp_edit='".$bereso['now']."' WHERE item_id='".$item."'");		
						
							$item_edit_addmessage = "<font color=\"green\">(bereso_template-edit_image_saved)</font>";						
							}
					}
					elseif ($item_image_id == 5 && file_exists($edit_photo5['tmp_name']))
					{
						// Check Fileextensions
						if (!(Image::get_extension($edit_photo5['tmp_name']) == ".jpg" or Image::get_extension($edit_photo5['tmp_name']) == ".png"))
						{
							$item_edit_addmessage = "<font color=\"red\">(bereso_template-edit_entry_error_filetype)</font>";  // fileextension					
						}
						else
						{
							// delete "old" image file 
							@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".jpg"); 
							@unlink ($bereso['images'].$row['item_imagename']."_".$item_image_id.".png"); 							
							// Copy File						
							move_uploaded_file($edit_photo5['tmp_name'], $bereso['images'] . $row['item_imagename'] . "_5".Image::get_extension($edit_photo5['tmp_name']));	
						
							// change timestamp_edit
							$sql->query("UPDATE bereso_item SET item_timestamp_edit='".$bereso['now']."' WHERE item_id='".$item."'");		
						
							$item_edit_addmessage = "<font color=\"green\">(bereso_template-edit_image_saved)</font>";						
							}
					}						
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
	
	// sharing on/off
	if ($action == "share")
	{
		$item_sharing = Item::get_share_id($item);
		
		// item shared? 
		if (strlen($item_sharing) > 0) 
		{
			// item shared - disable share - delete link
			$sql->query("UPDATE bereso_item SET item_shareid='' WHERE item_id='".$item."'");		
		}
		else // item not shared - enable share - create link
		{
			$sql->query("UPDATE bereso_item SET item_shareid='".uniqid()."' WHERE item_id='".$item."'");		
		}		
		// redirect back to show.php
		header('Location: ?module=show&item='.$item,true, 301 ); 
		exit();		
	}
	

	// Show form for item
	if ($action == null){
		if ($result = $sql->query("SELECT item_name, item_text, item_imagename from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_id='".$item."'"))
		{	
			$row = $result -> fetch_assoc();
			
			$content = File::read_file("templates/edit.txt");	
			if (strlen($edit_name_replace) > 0) { $content = str_replace("(bereso_edit_item_name)",$edit_name_replace,$content); } else { $content = str_replace("(bereso_edit_item_name)",$row['item_name'],$content); } // if set the form will replace the text with the variable content, not with the sql loaded content
			if (strlen($edit_text_replace) > 0) { $content = str_replace("(bereso_edit_item_text)",$edit_text_replace,$content); } else { $content = str_replace("(bereso_edit_item_text)",$row['item_text'],$content); } // if set the form will replace the text with the variable content, not with the sql loaded content
			$content = str_replace("(bereso_edit_item_message)",$item_edit_addmessage,$content); // insert or clear message field
			
			// Images
			// 0
			$content = str_replace("(bereso_edit_item_image_0)",File::read_file("templates/edit-image.txt"),$content);
			$content = str_replace("(bereso_edit_item_image_image)",File::read_file("templates/edit-image-image.txt"),$content);			
			$content = str_replace("(bereso_edit_item_image_turn)",File::read_file("templates/edit-image-form-turn.txt"),$content);	
			$content = str_replace("(bereso_edit_item_imagename)",$row['item_imagename'],$content);
			$content = str_replace("(bereso_edit_item_image_id)","0",$content);
			$content = str_replace("(bereso_edit_item_image_extension)",Image::search_extension($bereso['images'].$row['item_imagename']."_"."0"),$content);
			$content = str_replace("(bereso_edit_item_image_description)","(bereso_template-edit_preview_image)",$content);
			$content = str_replace("(bereso_edit_item_image_delete)",null,$content);			
			// 1
			$content = str_replace("(bereso_edit_item_image_1)",File::read_file("templates/edit-image.txt"),$content);
			$content = str_replace("(bereso_edit_item_image_image)",File::read_file("templates/edit-image-image.txt"),$content);			
			$content = str_replace("(bereso_edit_item_image_turn)",File::read_file("templates/edit-image-form-turn.txt"),$content);	
			$content = str_replace("(bereso_edit_item_imagename)",$row['item_imagename'],$content);
			$content = str_replace("(bereso_edit_item_image_id)","1",$content);
			$content = str_replace("(bereso_edit_item_image_extension)",Image::search_extension($bereso['images'].$row['item_imagename']."_"."1"),$content);
			$content = str_replace("(bereso_edit_item_image_description)","(bereso_template-edit_item_page) 1",$content);
			$content = str_replace("(bereso_edit_item_image_delete)",null,$content);			
			// 2
			$content = str_replace("(bereso_edit_item_image_2)",File::read_file("templates/edit-image.txt"),$content);
			if (file_exists($bereso['images'].$row['item_imagename']."_"."2".Image::search_extension($bereso['images'].$row['item_imagename']."_"."2")))
			{
				$content = str_replace("(bereso_edit_item_image_image)",File::read_file("templates/edit-image-image.txt"),$content);
				$content = str_replace("(bereso_edit_item_image_delete)",File::read_file("templates/edit-image-form-delete.txt"),$content);	
				$content = str_replace("(bereso_edit_item_image_turn)",File::read_file("templates/edit-image-form-turn.txt"),$content);	
			}
			else 
			{
				$content = str_replace("(bereso_edit_item_image_image)",null,$content);
				$content = str_replace("(bereso_edit_item_image_delete)",null,$content);	
				$content = str_replace("(bereso_edit_item_image_turn)",null,$content);	
			}			
			$content = str_replace("(bereso_edit_item_imagename)",$row['item_imagename'],$content);
			$content = str_replace("(bereso_edit_item_image_id)","2",$content);
			$content = str_replace("(bereso_edit_item_image_extension)",Image::search_extension($bereso['images'].$row['item_imagename']."_"."2"),$content);
			$content = str_replace("(bereso_edit_item_image_description)","(bereso_template-edit_item_page) 2",$content);			
			// 3
			$content = str_replace("(bereso_edit_item_image_3)",File::read_file("templates/edit-image.txt"),$content);
			if (file_exists($bereso['images'].$row['item_imagename']."_"."3".Image::search_extension($bereso['images'].$row['item_imagename']."_"."3")))
			{		
				$content = str_replace("(bereso_edit_item_image_image)",File::read_file("templates/edit-image-image.txt"),$content);
				$content = str_replace("(bereso_edit_item_image_delete)",File::read_file("templates/edit-image-form-delete.txt"),$content);	
				$content = str_replace("(bereso_edit_item_image_turn)",File::read_file("templates/edit-image-form-turn.txt"),$content);	
			}
			else 
			{
				$content = str_replace("(bereso_edit_item_image_image)",null,$content);
				$content = str_replace("(bereso_edit_item_image_delete)",null,$content);	
				$content = str_replace("(bereso_edit_item_image_turn)",null,$content);	
			}				
			$content = str_replace("(bereso_edit_item_imagename)",$row['item_imagename'],$content);
			$content = str_replace("(bereso_edit_item_image_id)","3",$content);
			$content = str_replace("(bereso_edit_item_image_extension)",Image::search_extension($bereso['images'].$row['item_imagename']."_"."3"),$content);
			$content = str_replace("(bereso_edit_item_image_description)","(bereso_template-edit_item_page) 3",$content);			
			// 4
			$content = str_replace("(bereso_edit_item_image_4)",File::read_file("templates/edit-image.txt"),$content);
			if (file_exists($bereso['images'].$row['item_imagename']."_"."4".Image::search_extension($bereso['images'].$row['item_imagename']."_"."4")))
			{		
				$content = str_replace("(bereso_edit_item_image_image)",File::read_file("templates/edit-image-image.txt"),$content);
				$content = str_replace("(bereso_edit_item_image_delete)",File::read_file("templates/edit-image-form-delete.txt"),$content);		
				$content = str_replace("(bereso_edit_item_image_turn)",File::read_file("templates/edit-image-form-turn.txt"),$content);	
			}
			else 
			{
				$content = str_replace("(bereso_edit_item_image_image)",null,$content);
				$content = str_replace("(bereso_edit_item_image_delete)",null,$content);
				$content = str_replace("(bereso_edit_item_image_turn)",null,$content);	
			}				
			$content = str_replace("(bereso_edit_item_imagename)",$row['item_imagename'],$content);
			$content = str_replace("(bereso_edit_item_image_id)","4",$content);
			$content = str_replace("(bereso_edit_item_image_extension)",Image::search_extension($bereso['images'].$row['item_imagename']."_"."4"),$content);
			$content = str_replace("(bereso_edit_item_image_description)","(bereso_template-edit_item_page) 4",$content);	
			// 5
			$content = str_replace("(bereso_edit_item_image_5)",File::read_file("templates/edit-image.txt"),$content);
			if (file_exists($bereso['images'].$row['item_imagename']."_"."5".Image::search_extension($bereso['images'].$row['item_imagename']."_"."5")))
			{		
				$content = str_replace("(bereso_edit_item_image_image)",File::read_file("templates/edit-image-image.txt"),$content);
				$content = str_replace("(bereso_edit_item_image_delete)",File::read_file("templates/edit-image-form-delete.txt"),$content);			
				$content = str_replace("(bereso_edit_item_image_turn)",File::read_file("templates/edit-image-form-turn.txt"),$content);	
			}
			else 
			{
				$content = str_replace("(bereso_edit_item_image_image)",null,$content);
				$content = str_replace("(bereso_edit_item_image_delete)",null,$content);	
				$content = str_replace("(bereso_edit_item_image_turn)",null,$content);	
			}				
			$content = str_replace("(bereso_edit_item_imagename)",$row['item_imagename'],$content);
			$content = str_replace("(bereso_edit_item_image_id)","5",$content);
			$content = str_replace("(bereso_edit_item_image_extension)",Image::search_extension($bereso['images'].$row['item_imagename']."_"."5"),$content);
			$content = str_replace("(bereso_edit_item_image_description)","(bereso_template-edit_item_page) 5",$content);	
		
			
			$content = str_replace("(bereso_edit_item_id)",$item,$content);

			// load alle hashtags and add all in the dropdown menu
			if ($result = $sql->query("SELECT DISTINCT bereso_tags.tags_name from bereso_tags INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".User::get_id_by_name($user)."' ORDER BY bereso_tags.tags_name ASC"))
			{	
				$insert_hashtag = null;
				while ($row = $result -> fetch_assoc())
				{
					$insert_hashtag .= File::read_file("templates/edit-hashtag.txt");
					$insert_hashtag = str_replace("(bereso_edit_item_insert_hashtag_name)",$row['tags_name'],$insert_hashtag);
					$insert_hashtag = str_replace("(bereso_edit_item_insert_hashtag_value)","#".$row['tags_name'],$insert_hashtag);
				}
			}
			$content = str_replace("(bereso_edit_item_insert_hashtag)",$insert_hashtag,$content); // insert option tags of all hashtags 
			
			// add to navigation
			$navigation .= File::read_file("templates/edit-navigation.txt");	
			$navigation = str_replace("(bereso_edit_item_id)",$item,$navigation);	
			
		}
	}
}
else
{
	Log::die ("CHECK: edit owner failed");
}