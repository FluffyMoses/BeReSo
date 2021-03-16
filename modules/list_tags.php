<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// List tags
// included by ../index.php
// ###################################

// list all tags inside a taggroup
if ($action == "taggroup" && strlen($taggroup))
{
	$taggroup_hashtags_csv = Tags::get_taggroup_hashtags_csv($user,$taggroup);
	// if taggroup exists
	if (strlen($taggroup_hashtags_csv) > 0)
	{
		$content = File::read_file("templates/list_tags-taggroup.html");
		$content_item = null;
		// Tags
		if ($result = $sql->query("SELECT DISTINCT bereso_tags.tags_name from bereso_tags INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".User::get_id_by_name($user)."' AND bereso_tags.tags_name IN (".$taggroup_hashtags_csv.") ORDER BY bereso_tags.tags_name ASC"))
		{	
			while ($row = $result -> fetch_assoc())
			{
				$content_item .= File::read_file("templates/list_tags-taggroup-item.html");
				$content_item = str_replace("(bereso_list_tags_tag_name)",$row['tags_name'],$content_item);
				$content_item = str_replace("(bereso_list_tags_item_numbers)",Item::get_number_by_tag($row['tags_name'],$user),$content_item);
			}
		}
		//headline
		$list_items_headline = "(bereso_template-list_tags_taggroup_with) " . $taggroup;
		// insert content items into content
		$content = str_replace("(bereso_list_tags_taggroup_items)",$content_item,$content);
		$content = str_replace("(bereso_list_tags_taggroup_headline)",$list_items_headline,$content);

		// add to navigation
		$navigation .= File::read_file("templates/main-navigation-list_tags-taggroup.html");	
		$navigation = str_replace("(bereso_list_tags_taggroup)",$taggroup,$navigation);
		$navigation = str_replace("(bereso_list_tags_taggroup_id)",Tags::get_taggroup_id($user,$taggroup),$navigation);				
	}
	else
	{
		Log::die ("CHECK: list_tags \$taggroup ($taggroup) does not exist");
	}
}

// List all categories, tag groups and tags
if ($action == null) 
{
	// Read all tags of this user
	$content = File::read_file("templates/list_tags.html"); // hardcoded categories
	$content = str_replace("(bereso_list_tags_allitem_numbers)",Item::get_number($user),$content); // item count for all items of $user
	$content = str_replace("(bereso_list_tags_shareditem_numbers)",Item::get_sharednumber($user),$content); // item count for all shared items of $user
	$content = str_replace("(bereso_list_tags_favoriteitem_numbers)",Item::get_favoritenumber($user),$content); // item count for all favorite items of $user
	
	$content_item = null;

	// tag groups
	if ($result = $sql->query("SELECT group_name, group_text FROM bereso_group WHERE group_user='".User::get_id_by_name($user)."' ORDER BY group_name ASC"))
	{	
		while ($row = $result -> fetch_assoc())
		{
			$content_item .= File::read_file("templates/list_tags-item-taggroup.html");
			$content_item = str_replace("(bereso_list_tags_taggroup_name)",$row['group_name'],$content_item);
			// get tags and count items		
			$group_items = 0;
			preg_match_all("/(#\w+)/", $row['group_text'], $matches);
			for ($i=0;$i<count($matches[0]);$i++)
			{			
				$group_items = $group_items + Item::get_number_by_tag(str_replace("#",null,$matches[0][$i]),$user);
			}		
			$content_item = str_replace("(bereso_list_tags_taggroup_numbers)",$group_items,$content_item);
		}
	}

	// Tags
	if ($result = $sql->query("SELECT DISTINCT bereso_tags.tags_name from bereso_tags INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".User::get_id_by_name($user)."' ORDER BY bereso_tags.tags_name ASC"))
	{	
		while ($row = $result -> fetch_assoc())
		{
			// show tag in overview if it is not part of any tag group
			if (Tags::is_tag_in_taggroup($user,$row['tags_name']) == false) 
			{
				$content_item .= File::read_file("templates/list_tags-item.html");
				$content_item = str_replace("(bereso_list_tags_tag_name)",$row['tags_name'],$content_item);
				$content_item = str_replace("(bereso_list_tags_item_numbers)",Item::get_number_by_tag($row['tags_name'],$user),$content_item);
			}
		}
	}
	// insert content items into content
	$content = str_replace("(bereso_list_tags_items)",$content_item,$content);
}
?>