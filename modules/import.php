<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Import recipe
// included by ../index.php
// ###################################

// Import shareid as new recipe for $user
if ($result = $sql->query("SELECT recipe_name, recipe_text, recipe_imagename from bereso_recipe WHERE recipe_shareid='".$shareid."'"))
{	
	$row = $result -> fetch_assoc();
	
	// if entry with this share id exists
	if (mysqli_num_rows($result) == 1)
	{		
		// new unique ids for imagename 
		$add_uniqueid = uniqid();

		$sql->query("INSERT into bereso_recipe (recipe_name, recipe_text,recipe_user, recipe_imagename, recipe_timestamp_creation, recipe_timestamp_edit) VALUES ('".$row['recipe_name']."','".$row['recipe_text']."','".$f->get_user_id_by_user_name($user)."','".$add_uniqueid."','".$timestamp."','".$timestamp."')");
		$add_id = $sql->insert_id;
			
		// save tags
		preg_match_all("/(#\w+)/", $row['recipe_text'], $matches);
		for ($i=0;$i<count($matches[0]);$i++)
		{
			// Debug: echo $matches[0][$i]."<br>";
			$sql->query("INSERT into bereso_tags (tags_name, tags_recipe) VALUES ('".str_replace("#","",$matches[0][$i])."','".$add_id."')");
		}		
		
		// copy image files to new imagename
		for ($i=0;$i<4;$i++)
		{
			// jpg or png
			if (file_exists($bereso['recipe_images'].$row['recipe_imagename']."_".$i.".jpg")) { $extension = ".jpg"; } else { $extension = ".png"; }
			$old_file = $bereso['recipe_images'].$row['recipe_imagename']."_".$i.$extension;
			$new_file = $bereso['recipe_images'].$add_uniqueid."_".$i.$extension;
			// DEBUG: echo $old_file . $new_file ."<br>";
			@copy($old_file,$new_file); // copy image
			header('Location: '.$bereso['url']."?module=show_recipe&recipe=".$add_id); // Redirect to the new created recipe
		}
	}
	// image does not exist or is not shared
	else 
	{
		$content = $f->read_file("templates/share-error.txt");
	}
}	

?>