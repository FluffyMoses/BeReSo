<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// List tags
// included by ../index.php
// ###################################

// Read all tags of this user
$content = $f->read_file("templates/list_tags.txt");
$content = str_replace("(bereso_list_tags_allrecipe_numbers)",$f->get_recipenumber($user),$content); // recipe count for all recipes of $user
$content = str_replace("(bereso_list_tags_sharedrecipe_numbers)",$f->get_sharedrecipenumber($user),$content); // recipe count for all shared recipes of $user

if ($result = $sql->query("SELECT DISTINCT bereso_tags.tags_name from bereso_tags INNER JOIN bereso_recipe ON bereso_tags.tags_recipe = bereso_recipe.recipe_id WHERE bereso_recipe.recipe_user='".$f->get_user_id_by_user_name($user)."' ORDER BY bereso_tags.tags_name ASC"))
{	
    while ($row = $result -> fetch_assoc())
    {
        $content .= $f->read_file("templates/list_tags-item.txt");
		$content = str_replace("(bereso_list_tags_tag_name)",$row['tags_name'],$content);
		$content = str_replace("(bereso_list_tags_recipe_numbers)",$f->get_recipenumber_by_tag_id($row['tags_name'],$user),$content);
    }
}


?>