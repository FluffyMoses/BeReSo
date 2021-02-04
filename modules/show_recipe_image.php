<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Show recipe image
// included by ../index.php
// ###################################

// check if user is owner of this recipe
if ($f->is_recipe_owned_by_user($user,$recipe)) {
	
	if ($result = $sql->query("SELECT recipe_imagename from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($user)."' AND recipe_id='".$recipe."'"))
	{	
		$row = $result -> fetch_assoc();

		// Show recipe image - no content - override output!
		$output_default = false; // do not use default output template
		$output = $f->read_file("templates/show_recipe_image.txt"); // use this main template!

		$output = str_replace("(bereso_show_image_recipe_id)",$recipe,$output);
		$output = str_replace("(bereso_show_image_recipe_image_id)",$recipe_image_id,$output);
		$output = str_replace("(bereso_show_image_recipe_imagename)",$row['recipe_imagename'],$output);
		$output = str_replace("(bereso_show_image_recipe_image_extension)",$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id),$output);
	}
	
}
else
{
	$f->logdie ("CHECK: show recipe image owner failed");
}

?>