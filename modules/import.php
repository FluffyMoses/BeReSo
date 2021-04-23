<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Import
// included by ../index.php
// ###################################

// Import shareid as new item for $user
if ($result = $sql->query("SELECT item_id, item_name, item_text, item_imagename from bereso_item WHERE item_shareid='".$shareid."'"))
{	
	$row = $result -> fetch_assoc();
	
	// if entry with this share id exists
	if (mysqli_num_rows($result) == 1)
	{		
		// new unique ids for imagename 
		$add_uniqueid = uniqid();

		$sql->query("INSERT into bereso_item (item_name, item_text,item_user, item_imagename, item_timestamp_creation, item_timestamp_edit) VALUES ('".$row['item_name']."','".$row['item_text']."','".User::get_id_by_name($user)."','".$add_uniqueid."','".$bereso['now']."','".$bereso['now']."')");
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
				$old_file = Image::get_foldername_by_shareid($shareid).Image::get_filenamecomplete($row['item_id'],$row2['images_image_id']);
				$new_file = Image::get_foldername_by_user_id(User::get_id_by_name($user)).$add_uniqueid."_".$row2['images_image_id'].Image::get_fileextension($row['item_id'],$row2['images_image_id']);
				copy($old_file,$new_file); // copy image
				$sql->query("INSERT into bereso_images (images_item, images_image_id, images_fileextension) VALUES ('".$add_id."','".$row2['images_image_id']."','".Image::get_fileextension($row['item_id'],$row2['images_image_id'],false)."')");
			}
		}

		Log::useraction($user,$module,$action,"Imported ".$row['item_id']." as $add_id");  // log when user_log enabled

		header('Location: index.php?module=show&item='.$add_id, true, 302); // Redirect to the new created item
		exit(); // stops the rest of the script from running 
	}
	// item does not exist or is not shared
	else 
	{
		$content = File::read_file("templates/share-error.html");
	}
}	

?>