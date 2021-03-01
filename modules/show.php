<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Show
// included by ../index.php
// ###################################

// open random item
if ($action == "random")
{
	$sql_list_items = "SELECT item_id from bereso_item WHERE item_user='".User::get_id_by_name($user)."' ORDER BY item_id ASC";
	if ($result = $sql->query($sql_list_items))
	{	
		if (mysqli_num_rows($result) > 0)
		{
			$i = 0;
			while ($row = $result -> fetch_assoc())
			{	
				$random_items[$i] = $row['item_id'];
				$i++;
			}
			$item = $random_items[rand(0,$i-1)];
		}
		// user has no items
		else
		{
			header('Location: '.$bereso['url']); // Redirect to the startpage
		}
	}	
	$action = null;
}

// check if user is owner of this item
if (Item::is_owned_by_user($user,$item)) {
	// Show item

	// load template
	$content = File::read_file("templates/show.txt");

	if ($result = $sql->query("SELECT item_name, item_text, item_timestamp_creation, item_timestamp_edit from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_id='".$item."'"))
	{	
		$row = $result -> fetch_assoc();
		
		// Highlight Tags with links
		$item_text_higlighted = Text::highlight_text($row['item_text']);
		
		// templates for images
		$content_item = null;

		if ($result2 = $sql->query("SELECT images_image_id from bereso_images WHERE images_item='".$item."' AND images_image_id > 0 ORDER BY images_image_id ASC")) // All images of item except the first (preview) one
		{	
			while ($row2 = $result2 -> fetch_assoc())
			{
				$content_item .= File::read_file("templates/show-item.txt");
				$content_item = str_replace("(bereso_show_item_image_id)",$row2['images_image_id'],$content_item);
				$content_item = str_replace("(bereso_show_item_image_extension)",Image::get_fileextension($item,$row2['images_image_id']),$content_item);				
			}
		}

		// add to navigation
		$navigation .= File::read_file("templates/show-navigation.txt");	
		$navigation = str_replace("(bereso_show_item_id)",$item,$navigation);
		// Text shared or not shared
		$item_sharing = Item::get_share_id($item);
		if (strlen($item_sharing) > 0) { $navigation = str_replace("(bereso_show_item_share_status)","(bereso_template-show_navigation_stop_sharing)",$navigation); } else { $navigation = str_replace("(bereso_show_item_share_status)","(bereso_template-show_navigation_start_sharing)",$navigation); }
		
		// build output
		$content = str_replace("(bereso_show_item_item)",$content_item,$content);
		$content = str_replace("(bereso_show_item_text)",$item_text_higlighted,$content);
		$content = str_replace("(bereso_show_item_name)",$row['item_name'],$content);
		$content = str_replace("(bereso_show_item_id)",$item,$content);
		$content = str_replace("(bereso_show_item_timestamp_creation)",Time::timestamp_to_datetime($row['item_timestamp_creation']),$content);
		$content = str_replace("(bereso_show_item_timestamp_edit)",Time::timestamp_to_datetime($row['item_timestamp_edit']),$content);
		$content = str_replace("(bereso_show_item_imagename)",Image::get_filename($item),$content);
		
		// item shared? show link
		if (strlen($item_sharing) > 0) 
		{
			// item shared show link
			$content = str_replace("(bereso_show_item_sharing)",File::read_file("templates/show-sharing.txt"),$content);
			$content = str_replace("(bereso_show_item_share_id)",$item_sharing,$content);
		}
		else // item not shared
		{
			$content = str_replace("(bereso_show_item_sharing)",null,$content);
		}

	}
}
else
{
	Log::die ("CHECK: show item owner failed");
}


?>