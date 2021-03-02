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
		preg_match_all("/(#\w+)/", $row['item_text'], $matches);
		for ($i=0;$i<count($matches[0]);$i++)
		{
			// Debug: echo $matches[0][$i]."<br>";
			$sql->query("INSERT into bereso_tags (tags_name, tags_item) VALUES ('".str_replace("#","",$matches[0][$i])."','".$add_id."')");
		}		
		
		// copy image files to new imagename
		if ($result2 = $sql->query("SELECT images_image_id from bereso_images WHERE images_item='".$row['item_id']."'"))
		{	
			while ($row2 = $result2 -> fetch_assoc())
			{
				$old_file = $bereso['images'].Image::get_filenamecomplete($row['item_id'],$row2['images_image_id']);
				$new_file = $bereso['images'].$add_uniqueid."_".$row2['images_image_id'].Image::get_fileextension($row['item_id'],$row2['images_image_id']);
				copy($old_file,$new_file); // copy image
				$sql->query("INSERT into bereso_images (images_item, images_image_id, images_fileextension) VALUES ('".$add_id."','".$row2['images_image_id']."','".Image::get_fileextension($row['item_id'],$row2['images_image_id'],false)."')");
				header('Location: '.$bereso['url']."?module=show&item=".$add_id); // Redirect to the new created item
			}
		}
	}
	// image does not exist or is not shared
	else 
	{
		$content = File::read_file("templates/share-error.html");
	}
}	

?>