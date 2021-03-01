<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// New
// included by ../index.php
// ###################################

// Add new item
$item_new_addmessage = null;

// save new entry and uploaded file
if ($action == "add")
{
	$form_item_file_type_error = false;

	// check max_file_upload size
	if ($_SERVER['CONTENT_LENGTH'] < $bereso['max_upload_size'])
	{
		// run for every file input field
		for ($i=0;$i<count($add_photo['tmp_name']);$i++) 
		{
			// Filetype only jpg or png		
			if (file_exists($add_photo['tmp_name'][$i])) { // check every uploaded file for matching filetypes
				if (!(Image::get_header_fileextension($add_photo['tmp_name'][$i]) == ".jpg" or Image::get_header_fileextension($add_photo['tmp_name'][$i]) == ".png")) { $form_item_file_type_error = true; }				
			}
		}
	}

	// insert when file 1 is ok, name is longer than 1 char - preview file is ok - no specialchars in name or text
	if (@file_exists($add_photo['tmp_name'][0]) && @file_exists($add_photo['tmp_name'][1]) && strlen($add_name) > 0 && $form_item_name_error == 0 && $form_item_text_error == 0 && $form_item_file_type_error == false && $_SERVER['CONTENT_LENGTH'] < $bereso['max_upload_size']) {
		
		// generate uniqueid that is used for the imagename
		$add_uniqueid = uniqid();

		$sql->query("INSERT into bereso_item (item_name, item_text,item_user, item_imagename, item_timestamp_creation, item_timestamp_edit) VALUES ('".$add_name."','".$add_text."','".User::get_id_by_name($user)."','".$add_uniqueid."','".$bereso['now']."','".$bereso['now']."')");
		$add_id = $sql->insert_id;
		
		// save tags
		preg_match_all("/(#\w+)/", $add_text, $matches);
		for ($i=0;$i<count($matches[0]);$i++)
		{
			$sql->query("INSERT into bereso_tags (tags_name, tags_item) VALUES ('".str_replace("#","",$matches[0][$i])."','".$add_id."')");
		}		

		// save images
		for ($i=0;$i<count($add_photo['tmp_name']);$i++) 
		{
			// Filetype only jpg or png		
			if (file_exists($add_photo['tmp_name'][$i])) { // check every uploaded file
				// check file header for image type
				if (Image::get_header_fileextension($add_photo['tmp_name'][$i]) == ".jpg") 
				{
					$save_fileextension = "jpg";
				}
				else
				{
					$save_fileextension = "png";
				}

				// save to database
				$sql->query("INSERT INTO bereso_images (images_item, images_image_id, images_fileextension) VALUES ('".$add_id."','".$i."','".$save_fileextension."')");

				// Move and rename images
				move_uploaded_file($add_photo['tmp_name'][$i], $bereso['images'] . $add_uniqueid . "_".$i.".".$save_fileextension);
			}
		}
		
		// Resize Thumbnail
		$thumbnail_path = $bereso['images'] . $add_uniqueid . "_0".Image::get_fileextension($add_id,0);		
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
		
		
	    $item_new_addmessage = "<font color=\"green\">(bereso_template-new_entry_saved): <b>\"$add_name\"</b></font>";
		// clear $add_name and $add_text for the form
		$add_name = null;
		$add_text = null;
		
	} 
	// form not correct
	else
	{
			if ($form_item_name_error == 1) { $item_new_addmessage = "<font color=\"red\">(bereso_template-new_entry_error_name_characters)</font>"; } // name wrong char
			elseif ($form_item_text_error == 1) { $item_new_addmessage = "<font color=\"red\">(bereso_template-new_entry_error_text_characters)</font>"; } // text wrong char
			elseif ($form_item_file_type_error == true) { $item_new_addmessage = "<font color=\"red\">(bereso_template-new_entry_error_filetype)</font>"; } // Wrong filetype
			elseif ($_SERVER['CONTENT_LENGTH'] > $bereso['max_upload_size']) { $item_new_addmessage = "<font color=\"red\">(bereso_template-new_entry_error_filesize) (". ($bereso['max_upload_size']/1024/1024)." MB - ". $bereso['max_upload_size']." Bytes)</font>"; } // max_upload_size exceeded
			else { $item_new_addmessage = "<font color=\"red\">(bereso_template-new_entry_error_missing)</font>"; } // name, preview or image1 missing
	}	
	// load new_item-form again with message success or failure
	$action = null;
}

// Show form for new item
if ($action == null){
	$content = File::read_file("templates/new.txt");
	$content = str_replace("(bereso_new_item_add_name)",$add_name,$content); // if entry is saved with errors - show name again
	$content = str_replace("(bereso_new_item_add_text)",$add_text,$content); // if entry is saved with errors - show text again
	$content = str_replace("(bereso_new_item_message)",$item_new_addmessage,$content); // insert or clear message field

	// load all hashtags and add all in the dropdown menu
	if ($result = $sql->query("SELECT DISTINCT bereso_tags.tags_name from bereso_tags INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".User::get_id_by_name($user)."' ORDER BY bereso_tags.tags_name ASC"))
	{	
		$insert_hashtag = null;
		while ($row = $result -> fetch_assoc())
		{
			$insert_hashtag .= File::read_file("templates/new-hashtag.txt");
			$insert_hashtag = str_replace("(bereso_new_item_insert_hashtag_name)",$row['tags_name'],$insert_hashtag);
			$insert_hashtag = str_replace("(bereso_new_item_insert_hashtag_value)","#".$row['tags_name'],$insert_hashtag);
		}
	}
	$content = str_replace("(bereso_new_item_insert_hashtag)",$insert_hashtag,$content); // insert option tags of all hashtags 

	// Load additional image uploads - preview 0 and image 1 are always hardcoded in the template
	$content_optional_images = null;
	for ($i=2;$i<=$bereso['new_amount_images'];$i++)
	{
		$content_optional_images .= File::read_file("templates/new-optional_images.txt");
		$content_optional_images = str_replace("(bereso_new_item_image_optional_image_id)",$i,$content_optional_images);
	}	
	$content = str_replace("(bereso_new_item_optional_images)",$content_optional_images,$content); // Insert additional images into main template
}
?>