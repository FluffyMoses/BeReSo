<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Show recipe image
// included by ../index.php
// ###################################

// load template
$output = $f->read_file("templates/share_image.txt");
$output_default = false; // do not use default output template

if ($result = $sql->query("SELECT recipe_name, recipe_text, recipe_imagename, recipe_user from bereso_recipe WHERE recipe_shareid='".$shareid."'"))
{	
	$row = $result -> fetch_assoc();
			
	// if entry with this share id exists
	if (mysqli_num_rows($result) == 1)
	{
			// build output
			$output = str_replace("(bereso_share_image_shareid)",$shareid,$output);
			$output = str_replace("(bereso_share_image_recipe_name)",$row['recipe_name'],$output);
			$output = str_replace("(bereso_share_image_imagename)",$row['recipe_imagename'],$output);	
			$output = str_replace("(bereso_share_image_image_id)",$share_image_id,$output);
			$output = str_replace("(bereso_share_image_extension)",$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_".$share_image_id),$output);
			
		
	}
	else
	{
		$output = $f->read_file("templates/share_image-error.txt");
	}	
}

?>