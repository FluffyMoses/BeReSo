<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Show item image
// included by ../index.php
// ###################################

// load template
$output = File::read_file("templates/share_image.txt");
$output_default = false; // do not use default output template

if ($result = $sql->query("SELECT item_id, item_name, item_text, item_user from bereso_item WHERE item_shareid='".$shareid."'"))
{	
	$row = $result -> fetch_assoc();
			
	// if entry with this share id exists
	if (mysqli_num_rows($result) == 1)
	{
			// build output
			$output = str_replace("(bereso_share_image_shareid)",$shareid,$output);
			$output = str_replace("(bereso_share_image_item_name)",$row['item_name'],$output);
			$output = str_replace("(bereso_share_image_imagename)",Image::get_filename($row['item_id']),$output);	
			$output = str_replace("(bereso_share_image_image_id)",$share_image_id,$output);
			$output = str_replace("(bereso_share_image_extension)",Image::get_fileextension($row['item_id'],$share_image_id),$output);			
	}
	else
	{
		$output = File::read_file("templates/share_image-error.txt");
	}	
}

?>