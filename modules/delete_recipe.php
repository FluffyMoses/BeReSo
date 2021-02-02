<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Delete recipe
// included by ../index.php
// ###################################


// check if user is owner of this recipe
if ($f->is_recipe_owned_by_user($user,$recipe)) {
	// Delete recipe

	// show double check
	if ($action == null)
	{
		// load template
		$content = $f->read_file("templates/delete_recipe.txt");
		$content = str_replace("(bereso_delete_recipe_id)",$recipe,$content);
		$content = str_replace("(bereso_delete_recipe_name)",$f->get_recipename($recipe),$content);
		
		// add to navigation
		$navigation .= $f->read_file("templates/delete_recipe-navigation.txt");	
		$navigation = str_replace("(bereso_delete_recipe_id)",$recipe,$navigation);		
	}

	// double check successfull => really delete the entry
	if ($action == "confirm")
	{
		// read imagename
		if ($result = $sql->query("SELECT recipe_imagename from bereso_recipe WHERE recipe_id='".$recipe."'"))
		{	
			$row = $result -> fetch_assoc();		
			$delete_imagename = $row['recipe_imagename'];
		}
		
		// delete SQL entrys
		$sql->query("DELETE FROM bereso_tags where tags_recipe=".$recipe);
		$sql->query("DELETE FROM bereso_recipe where recipe_id=".$recipe);
		
		// delete files	
		for ($i=0;$i<=3;$i++)
		{
			if (file_exists($bereso['recipe_images'].$delete_imagename."_".$i.".jpg")) 
			{
				unlink ($bereso['recipe_images'].$delete_imagename."_".$i.".jpg");
			}
			if (file_exists($bereso['recipe_images'].$delete_imagename."_".$i.".png")) 
			{
				unlink ($bereso['recipe_images'].$delete_imagename."_".$i.".png");
			}			
		}	
		
		header('Location: '.$bereso['url']); // Redirect to the startpage
	}
}
else
{
	die ("CHECK: delete owner failed");
}

?>