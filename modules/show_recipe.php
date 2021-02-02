<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Show recipe
// included by ../index.php
// ###################################

// open random recipe
if ($action == "random")
{
	$sql_list_recipes = "SELECT recipe_id from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($user)."' ORDER BY recipe_id ASC";
	if ($result = $sql->query($sql_list_recipes))
	{	
		if (mysqli_num_rows($result) > 0)
		{
			$i = 0;
			while ($row = $result -> fetch_assoc())
			{	
				$random_recipes[$i] = $row['recipe_id'];
				$i++;
			}
			$recipe = $random_recipes[rand(0,$i-1)];
		}
		// user has no recipes
		else
		{
			header('Location: '.$bereso['url']); // Redirect to the startpage
		}
	}	
	$action = null;
}

// check if user is owner of this recipe
if ($f->is_recipe_owned_by_user($user,$recipe)) {
	// Show recipe

	// load template
	$content = $f->read_file("templates/show_recipe.txt");

	if ($result = $sql->query("SELECT recipe_name, recipe_text, recipe_imagename, recipe_timestamp_creation, recipe_timestamp_edit from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($user)."' AND recipe_id='".$recipe."'"))
	{	
		$row = $result -> fetch_assoc();
		
		// Highlight Tags with links
		$recipe_text_higlighted = $f->highlight_text($row['recipe_text']);
		
		// templates for images
		$content_item = null;
		for ($i=1;$i<=3;$i++)
		{
			if (file_exists($bereso['recipe_images'].$row['recipe_imagename']."_".$i.$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_".$i))) 
			{
				$content_item .= $f->read_file("templates/show_recipe-item.txt");
				$content_item = str_replace("(bereso_show_recipe_image_id)",$i,$content_item);
				$content_item = str_replace("(bereso_show_recipe_image_extension)",$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_".$i),$content_item);
			}
		}

		// add to navigation
		$navigation .= $f->read_file("templates/show_recipe-navigation.txt");	
		$navigation = str_replace("(bereso_show_recipe_id)",$recipe,$navigation);
		// Text shared or not shared
		$recipe_sharing = $f->get_recipe_share_id($recipe);
		if (strlen($recipe_sharing) > 0) { $navigation = str_replace("(bereso_show_recipe_share_status)","Freigabe beenden",$navigation); } else { $navigation = str_replace("(bereso_show_recipe_share_status)","Rezept freigeben",$navigation); }
		
		// build output
		$content = str_replace("(bereso_show_recipe_item)",$content_item,$content);
		$content = str_replace("(bereso_show_recipe_text)",$recipe_text_higlighted,$content);
		$content = str_replace("(bereso_show_recipe_name)",$row['recipe_name'],$content);
		$content = str_replace("(bereso_show_recipe_id)",$recipe,$content);
		$content = str_replace("(bereso_show_recipe_timestamp_creation)",$f->timestamp_to_datetime($row['recipe_timestamp_creation']),$content);
		$content = str_replace("(bereso_show_recipe_timestamp_edit)",$f->timestamp_to_datetime($row['recipe_timestamp_edit']),$content);
		$content = str_replace("(bereso_show_recipe_imagename)",$row['recipe_imagename'],$content);
		
		// Recipe shared? show link
		if (strlen($recipe_sharing) > 0) 
		{
			// recipe shared show link
			$content = str_replace("(bereso_show_recipe_sharing)",$f->read_file("templates/show_recipe-sharing.txt"),$content);
			$content = str_replace("(bereso_show_recipe_share_id)",$recipe_sharing,$content);
		}
		else // recipe not shared
		{
			$content = str_replace("(bereso_show_recipe_sharing)",null,$content);
		}

	}
}
else
{
	die ("CHECK: show recipe owner failed");
}


?>