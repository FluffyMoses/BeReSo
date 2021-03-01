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
		$content = File::read_file("templates/delete.txt");
		$content = str_replace("(bereso_delete_item_id)",$item,$content);
		$content = str_replace("(bereso_delete_item_name)",Item::get_name($item),$content);

		if ($result = $sql->query("SELECT item_imagename from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_id='".$item."'"))
		{	
			$row = $result -> fetch_assoc();		
			$content = str_replace("(bereso_delete_item_imagename)",$row['item_imagename'],$content);
			$content = str_replace("(bereso_delete_item_image_extension)",Image::search_extension($bereso['images'].$row['item_imagename']."_0"),$content);
		}
		
		// add to navigation
		$navigation .= File::read_file("templates/delete-navigation.txt");	
		$navigation = str_replace("(bereso_delete_item_id)",$item,$navigation);		
	}

	// double check successfull => really delete the entry
	if ($action == "confirm")
	{
		// read imagename
		if ($result = $sql->query("SELECT item_imagename from bereso_item WHERE item_id='".$item."'"))
		{	
			$row = $result -> fetch_assoc();		
			$delete_imagename = $row['item_imagename'];
		}
		
		// delete SQL entrys
		$sql->query("DELETE FROM bereso_tags where tags_item=".$item);
		$sql->query("DELETE FROM bereso_item where item_id=".$item);
		
		// delete files	
		for ($i=0;$i<=5;$i++)
		{
			if (file_exists($bereso['images'].$delete_imagename."_".$i.".jpg")) 
			{
				unlink ($bereso['images'].$delete_imagename."_".$i.".jpg");
			}
			if (file_exists($bereso['images'].$delete_imagename."_".$i.".png")) 
			{
				unlink ($bereso['images'].$delete_imagename."_".$i.".png");
			}			
		}	
		
		header('Location: '.$bereso['url']); // Redirect to the startpage
	}
}
else
{
	Log::die ("CHECK: delete owner failed");
}

?>