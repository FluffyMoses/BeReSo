<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// List recipes
// included by ../index.php
// ###################################


// Read all recipes with this tag or search query
$content_item = null;

// start search empty
if (strlen($search) == 0 && strlen($tag) == 0) { $tag = "ALL"; } // set All recipes as default

// List recipes via search request
if (strlen($search) > 0)
{
	if ($search_is_letter_failed == true)
	{
		$sql_list_recipes = null;
		$content = $f->read_file("templates/list_recipes-searcherror.txt"); // load error template for content	
	}
	// wrong character in $search
	else
	{
		$sql_list_recipes = "SELECT recipe_id, recipe_name, recipe_imagename from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($user)."' AND (recipe_name LIKE '%".$search."%' OR recipe_text LIKE '%".$search."%') ORDER BY recipe_name ASC"; 
		$list_recipes_headline = "Suchergebnisse f&uuml;r " . $search;	
	}
}
// list recipes via tag
elseif (strlen($tag) > 0)
{
	// list all recipes
	if ($tag == "ALL") 
	{
		$sql_list_recipes = "SELECT recipe_id, recipe_name, recipe_imagename from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($user)."' ORDER BY recipe_name ASC";
		$list_recipes_headline = "Alle Rezepte";
	}
	// list all shared recipes
	elseif ($tag == "SHARED") 
	{
		$sql_list_recipes = "SELECT recipe_id, recipe_name, recipe_imagename from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($user)."' AND LENGTH(recipe_shareid) > 0 ORDER BY recipe_name ASC";
		$list_recipes_headline = "Alle geteilten Rezepte";
	}	
	// list recipes with $tag
	else
	{
		$sql_list_recipes = "SELECT  bereso_tags.tags_name, bereso_recipe.recipe_name, bereso_recipe.recipe_id, bereso_recipe.recipe_imagename from bereso_recipe INNER JOIN bereso_tags ON bereso_tags.tags_recipe = bereso_recipe.recipe_id WHERE bereso_recipe.recipe_user='".$f->get_user_id_by_user_name($user)."' AND bereso_tags.tags_name='".$tag."' ORDER BY bereso_recipe.recipe_name ASC";
		$list_recipes_headline = "Rezepte mit #" . $tag;
	}	
}	
// no valid list_recipes request
else
{
	$f->logdie ("CHECK: no valid list_recipes request");
}

// if there is no error - execute sql and show alle recipes
if (strlen($sql_list_recipes) > 0)
{
	// load template
	$content = $f->read_file("templates/list_recipes.txt");
	// insert headline
	$content = str_replace("(bereso_recipe_headline)",$list_recipes_headline,$content);

	if ($result = $sql->query($sql_list_recipes))
	{	
		while ($row = $result -> fetch_assoc())
		{
			$content_item .= $f->read_file("templates/list_recipes-item.txt");
			$content_item = str_replace("(bereso_recipe_id)",$row['recipe_id'],$content_item);
			$content_item = str_replace("(bereso_recipe_imagename)",$row['recipe_imagename'],$content_item);
			$content_item = str_replace("(bereso_recipe_name)",$row['recipe_name'],$content_item);		
			// get image extension
			$content_item = str_replace("(bereso_recipe_image_extension)",$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_0"),$content_item);
		}
		$content = str_replace("(bereso_list_recipes_item)",$content_item,$content);
	}

	// count results
	$list_recipes_count = $result->num_rows;	
	$content = str_replace("(bereso_recipe_count)",$list_recipes_count,$content);
}
?>