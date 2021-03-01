<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Show image
// included by ../index.php
// ###################################

// check if user is owner of this item
if (Item::is_owned_by_user($user,$item)) {
	
	if ($result = $sql->query("SELECT item_imagename from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_id='".$item."'"))
	{	
		$row = $result -> fetch_assoc();

		// Show item image - no content - override output!
		$output_default = false; // do not use default output template
		$output = File::read_file("templates/show_image.txt"); // use this main template!

		$output = str_replace("(bereso_show_image_item_id)",$item,$output);
		$output = str_replace("(bereso_show_image_item_image_id)",$item_image_id,$output);
		$output = str_replace("(bereso_show_image_item_imagename)",$row['item_imagename'],$output);
		$output = str_replace("(bereso_show_image_item_image_extension)",Image::search_extension($bereso['images'].$row['item_imagename']."_".$item_image_id),$output);
	}
	
}
else
{
	Log::die ("CHECK: show item image owner failed");
}

?>