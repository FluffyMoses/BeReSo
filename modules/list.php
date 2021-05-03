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
	$search_explode = explode(",",User::get_last_list($user)); // split response by ,
	$search_tag = $search_explode[0]; // tag
	$search = substr($search_tag,6,strlen($search_tag)-6); // convert last_list to search without the prefix SEARCH
	$tag = null;
}

// List items via search request
if (strlen($search) > 0)
{
	if ($search_is_letter_failed == true) // wrong character in $search
	{
		$sql_list_items = null;
		$content = File::read_file("templates/list-searcherror.html"); // load error template for content	
		User::set_last_list($user,null,$page); // delete the last list
	}
	else // start searching...
	{
		// is $search in name, text or ocr_text (if ocr searchable is true for this item)
		$sql_list_items = "SELECT item_id, item_name from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND (item_name LIKE '%".$search."%' OR item_text LIKE '%".$search."%' OR (item_ocr_searchable='1' AND item_ocr_text LIKE '%".$search."%')) ORDER BY item_name ASC"; 
		$list_items_headline = "(bereso_template-list_search_results) " . $search;	
		User::set_last_list($user,"SEARCH".$search,$page);
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
	// list all favorite items
	elseif ($tag == "FAVORITE") 
	{
		$sql_list_items = "SELECT item_id, item_name from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_favorite='1' ORDER BY item_name ASC";
		$list_items_headline = "(bereso_template-list_tags_all_favorite_items)";
	}	
	// list all rated items
	elseif ($tag == "RATED") 
	{
		$sql_list_items = "SELECT item_id, item_name from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_rating > '0' ORDER BY item_name ASC";
		$list_items_headline = "(bereso_template-list_tags_all_rated_items)";
	}	
	// list all ocr items
	elseif ($tag == "OCR") 
	{
		$sql_list_items = "SELECT item_id, item_name from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_ocr='1' ORDER BY item_name ASC";
		$list_items_headline = "(bereso_template-list_tags_all_ocr_items)";
	}	
	// list items with $tag
	else
	{
		$sql_list_items = "SELECT  bereso_tags.tags_name, bereso_item.item_name, bereso_item.item_id from bereso_item INNER JOIN bereso_tags ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".User::get_id_by_name($user)."' AND bereso_tags.tags_name='".$tag."' ORDER BY bereso_item.item_name ASC";
		$list_items_headline = "(bereso_template-list_tags_items_with) #" . $tag;
	}	
	User::set_last_list($user,$tag,$page);
}	
// no valid list request
else
{
	Log::die ("CHECK: no valid list_items request");
	User::set_last_list($user,null,$page); // delete the last list
}

// if there is no error - execute sql and show all items
if (strlen($sql_list_items) > 0)
{
	// load template
	$content = File::read_file("templates/list.html");
	// insert headline
	$content = str_replace("(bereso_item_headline)",$list_items_headline,$content);

	// limit recipes per page
	$limit_start = ($page - 1) * $bereso['items_per_page'];
	$sql_list_items_page = $sql_list_items . " LIMIT " . $limit_start .",". $bereso['items_per_page'];

	// show items on this page
	if ($result = $sql->query($sql_list_items_page))
	{	
		while ($row = $result -> fetch_assoc())
		{
			$content_item .= File::read_file("templates/list-item.html");
			if (Item::get_favorite($row['item_id']) == true) { $content_item = str_replace("(bereso_list_item_favorite)",File::read_file("templates/list-item-favorite.html"),$content_item); } else { $content_item = str_replace("(bereso_list_item_favorite)",null,$content_item); }
			$content_item = str_replace("(bereso_item_id)",$row['item_id'],$content_item);
			$content_item = str_replace("(bereso_item_imagename)",Image::get_filename($row['item_id']),$content_item);
			$content_item = str_replace("(bereso_item_name)",$row['item_name'],$content_item);		
			// get image extension
			$content_item = str_replace("(bereso_item_image_extension)",Image::get_fileextension($row['item_id'],0),$content_item);
			// rating replace
			$rating = Item::get_rating($row['item_id']);			
			$item_rating = null;
			for ($i=1;$i<=$rating;$i++)
			{
				$item_rating .= File::read_file("templates/list-item-rating.html");
			}
			$item_rating = str_replace("(bereso_list_item_id)",$item,$item_rating);
			$content_item = str_replace("(bereso_list_item_rating)",$item_rating,$content_item);
		}
		$content = str_replace("(bereso_list_items_item)",$content_item,$content);
	}

	// count results
	$result_count = $sql->query($sql_list_items);
	$list_items_count = $result_count->num_rows;	
	$content = str_replace("(bereso_item_count)",$list_items_count,$content);

	// page links
	if ($list_items_count > $bereso['items_per_page'])  // more than one page
	{
		$page_links = null;
		if (strlen($search) > 0) { $page_linktag = "SEARCH"; } else { $page_linktag = $tag; }
		$all_pages = ceil($list_items_count/$bereso['items_per_page']); // amount of pages
		for ($i=1;$i<=$all_pages;$i++)
		{
			if ($i == $page) // active page
			{
				$page_links .= File::read_file("templates/list-page-active.html");
			}
			else // another page - with link
			{
				$page_links .= File::read_file("templates/list-page.html");
			}
			$page_links = str_replace("(bereso_list_page_number)",$i,$page_links);
			$page_links = str_replace("(bereso_list_tag)",$page_linktag,$page_links);
		}
		$content = str_replace("(bereso_list_pagelinks)",$page_links,$content);
	}
	else // only one page - delete links
	{
		$content = str_replace("(bereso_list_pagelinks)",null,$content);
	}
}
?>