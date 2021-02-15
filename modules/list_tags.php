<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// List tags
// included by ../index.php
// ###################################

// Read all tags of this user
$content = $f->read_file("templates/list_tags.txt");
$content = str_replace("(bereso_list_tags_allitem_numbers)",$f->get_itemnumber($user),$content); // item count for all items of $user
$content = str_replace("(bereso_list_tags_shareditem_numbers)",$f->get_shareditemsnumber($user),$content); // item count for all shared items of $user

if ($result = $sql->query("SELECT DISTINCT bereso_tags.tags_name from bereso_tags INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".$f->get_user_id_by_user_name($user)."' ORDER BY bereso_tags.tags_name ASC"))
{	
    while ($row = $result -> fetch_assoc())
    {
        $content .= $f->read_file("templates/list_tags-item.txt");
		$content = str_replace("(bereso_list_tags_tag_name)",$row['tags_name'],$content);
		$content = str_replace("(bereso_list_tags_item_numbers)",$f->get_itemnumber_by_tag_id($row['tags_name'],$user),$content);
    }
}


?>