<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Delete
// included by ../index.php
// ###################################


// check if user is owner of this item
if (Item::is_owned_by_user($user,$item)) {
	// Delete item

	// show double check
	if ($action == null)
	{
		// load template
		$content = File::read_file("templates/delete.html");
		$content = str_replace("(bereso_delete_item_id)",$item,$content);
		$content = str_replace("(bereso_delete_item_name)",Item::get_name($item),$content);

	
		$content = str_replace("(bereso_delete_item_imagename)",Image::get_filename($item),$content);
		$content = str_replace("(bereso_delete_item_image_extension)",Image::get_fileextension($item,0),$content);		
		
		// add to navigation -> Last item
		$navigation2 .= File::read_file("templates/main-navigation2-last_item.html");
		$navigation2 = str_replace("(main-navigation-last_item)",Image::get_filenamecomplete($item,0),$navigation2);
		$navigation2 = str_replace("(main-navigation-last_item_value)",$item,$navigation2);	
	}

	// double check successfull => really delete the entry
	if ($action == "confirm")
	{
		// delete files	
		if ($result = $sql->query("SELECT images_image_id from bereso_images WHERE images_item='".$item."'"))
		{	
			while ($row = $result -> fetch_assoc())
			{
				if (file_exists($bereso['images'].Image::get_filenamecomplete($item,$row['images_image_id'])))
				{
					unlink ($bereso['images'].Image::get_filenamecomplete($item,$row['images_image_id']));
				}
			}
		}

		// delete SQL entrys
		$sql->query("DELETE FROM bereso_tags where tags_item='".$item."'");
		$sql->query("DELETE FROM bereso_item where item_id='".$item."'");
		$sql->query("DELETE FROM bereso_images where images_item='".$item."'");
		
		header('Location: '.$bereso['url']); // Redirect to the startpage
	}
}
else
{
	Log::die ("CHECK: delete owner failed");
}

?>