<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Show image
// included by ../index.php
// ###################################

// check if user is owner of this item
if (Item::is_owned_by_user($user,$item)) {
	
	// Show item image - no content - override output!
	$output_default = false; // do not use default output template
	$output = File::read_file("templates/show_image.html"); // use this main template!

	$output = str_replace("(bereso_show_image_item_id)",$item,$output);
	$output = str_replace("(bereso_show_image_item_image_id)",$item_image_id,$output);
	$output = str_replace("(bereso_show_image_item_imagename)",Image::get_filename($item),$output);
	$output = str_replace("(bereso_show_image_item_image_extension)",Image::get_fileextension($item,$item_image_id),$output);
}
else
{
	Log::die ("CHECK: show item image owner failed");
}

?>