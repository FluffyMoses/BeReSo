<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// List
// included by ../index.php
// ###################################


// Read all items with this tag or search query
$content_item = null;

// start search empty
if (strlen($search) == 0 && strlen($tag) == 0) { $tag = "ALL"; } // set All items as default

// if tag is set by last_list and was a search request we need to convert tag to search (when tag starts with SEARCH)
if (substr($tag,0,6) == "SEARCH") 
{
	$search = substr(User::get_last_list($user),6,strlen(User::get_last_list($user))-6); // convert last_list to search without the prefix SEARCH
	$tag = null;
}

// List items via search request
if (strlen($search) > 0)
{
	if ($search_is_letter_failed == true)
	{
		$sql_list_items = null;
		$content = File::read_file("templates/list-searcherror.html"); // load error template for content	
		User::set_last_list($user,null); // delete the last list
	}
	// wrong character in $search
	else
	{
		$sql_list_items = "SELECT item_id, item_name from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND (item_name LIKE '%".$search."%' OR item_text LIKE '%".$search."%') ORDER BY item_name ASC"; 
		$list_items_headline = "(bereso_template-list_search_results) " . $search;	
		User::set_last_list($user,"SEARCH".$search);
	}
}
// list items via tag
elseif (strlen($tag) > 0)
{
	// list all items
	if ($tag == "ALL") 
	{
		$sql_list_items = "SELECT item_id, item_name from bereso_item WHERE item_user='".User::get_id_by_name($user)."' ORDER BY item_name ASC";
		$list_items_headline = "(bereso_template-list_tags_all_items)";
	}
	// list all shared items
	elseif ($tag == "SHARED") 
	{
		$sql_list_items = "SELECT item_id, item_name from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND LENGTH(item_shareid) > 0 ORDER BY item_name ASC";
		$list_items_headline = "(bereso_template-list_tags_all_shared_items)";
	}	
	// list items with $tag
	else
	{
		$sql_list_items = "SELECT  bereso_tags.tags_name, bereso_item.item_name, bereso_item.item_id from bereso_item INNER JOIN bereso_tags ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".User::get_id_by_name($user)."' AND bereso_tags.tags_name='".$tag."' ORDER BY bereso_item.item_name ASC";
		$list_items_headline = "(bereso_template-list_tags_items_with) #" . $tag;
	}	
	User::set_last_list($user,$tag);
}	
// no valid list request
else
{
	Log::die ("CHECK: no valid list_items request");
	User::set_last_list($user,null); // delete the last list
}

// if there is no error - execute sql and show alle items
if (strlen($sql_list_items) > 0)
{
	// load template
	$content = File::read_file("templates/list.html");
	// insert headline
	$content = str_replace("(bereso_item_headline)",$list_items_headline,$content);

	if ($result = $sql->query($sql_list_items))
	{	
		while ($row = $result -> fetch_assoc())
		{
			$content_item .= File::read_file("templates/list-item.html");
			$content_item = str_replace("(bereso_item_id)",$row['item_id'],$content_item);
			$content_item = str_replace("(bereso_item_imagename)",Image::get_filename($row['item_id']),$content_item);
			$content_item = str_replace("(bereso_item_name)",$row['item_name'],$content_item);		
			// get image extension
			$content_item = str_replace("(bereso_item_image_extension)",Image::get_fileextension($row['item_id'],0),$content_item);
		}
		$content = str_replace("(bereso_list_items_item)",$content_item,$content);
	}

	// count results
	$list_items_count = $result->num_rows;	
	$content = str_replace("(bereso_item_count)",$list_items_count,$content);
}
?>