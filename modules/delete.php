<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Delete
// included by ../index.php
// ###################################


// check if user is owner of this item
if ($f->is_item_owned_by_user($user,$item)) {
	// Delete item

	// show double check
	if ($action == null)
	{
		// load template
		$content = $f->read_file("templates/delete.txt");
		$content = str_replace("(bereso_delete_item_id)",$item,$content);
		$content = str_replace("(bereso_delete_item_name)",$f->get_itemname($item),$content);

		if ($result = $sql->query("SELECT item_imagename from bereso_item WHERE item_user='".$f->get_user_id_by_user_name($user)."' AND item_id='".$item."'"))
		{	
			$row = $result -> fetch_assoc();		
			$content = str_replace("(bereso_delete_item_imagename)",$row['item_imagename'],$content);
			$content = str_replace("(bereso_delete_item_image_extension)",$f->search_image_extension($bereso['images'].$row['item_imagename']."_0"),$content);
		}
		
		// add to navigation
		$navigation .= $f->read_file("templates/delete-navigation.txt");	
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
		for ($i=0;$i<=3;$i++)
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
	$f->logdie ("CHECK: delete owner failed");
}

?>