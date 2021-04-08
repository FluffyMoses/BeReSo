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
			header('Location: index.php', true, 302);// Redirect to the startpage
			exit(); // stops the rest of the script from running 
		}
	}	
}

// check if user is owner of this item
if (Item::is_owned_by_user($user,$item)) {
	// Show item

	// load template
	$content = File::read_file("templates/show.html");

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
				$content_item .= File::read_file("templates/show-item.html");
				$content_item = str_replace("(bereso_show_item_image_id)",$row2['images_image_id'],$content_item);
				$content_item = str_replace("(bereso_show_item_image_extension)",Image::get_fileextension($item,$row2['images_image_id']),$content_item);				
			}
		}

		// add to navigation
		$navigation .= File::read_file("templates/main-navigation-show.html");			
		// Text shared or not shared
		$item_sharing = Item::get_share_id($item);
		if (strlen($item_sharing) > 0) { $navigation = str_replace("(bereso_show_item_share_status)","(bereso_template-main_navigation_show_stop_sharing)",$navigation); } else { $navigation = str_replace("(bereso_show_item_share_status)","(bereso_template-main_navigation_show_start_sharing)",$navigation); }
		// Text favorite or not favorite
		$item_favorite = Item::get_favorite($item);
		if ($item_favorite == true) { $navigation = str_replace("(bereso_show_item_favorite_status)","(bereso_template-main_navigation_show_stop_favorite)",$navigation); } else { $navigation = str_replace("(bereso_show_item_favorite_status)","(bereso_template-main_navigation_show_start_favorite)",$navigation); }
		// Text ocr	
		if (Config::get_config("ocr_enabled") == 1 && User::get_ocr($user) == true) // is ocr enabled for this user and global
		{
			// Enable / disable button
			$navigation .= File::read_file("templates/main-navigation-show-ocr-toggle.html");
			$item_ocr = Item::get_ocr($item);
			if ($item_ocr == true) { $navigation = str_replace("(bereso_show_item_ocr_status)","(bereso_template-main_navigation_show_stop_ocr)",$navigation); } else { $navigation = str_replace("(bereso_show_item_ocr_status)","(bereso_template-main_navigation_show_start_ocr)",$navigation); }
			// show Edit ocr button when something is available
			if (strlen(Item::get_ocr_text($item)) > 0)
			{
				$navigation .= File::read_file("templates/main-navigation-show-ocr-edit.html");
			}
		}
		// Replace inside navigation
		$navigation = str_replace("(bereso_show_item_id)",$item,$navigation);
		
		// build output
		
		// item shared? show link
		if (strlen($item_sharing) > 0) 
		{
			// item shared show link
			$content = str_replace("(bereso_show_item_sharing)",File::read_file("templates/show-sharing.html"),$content);
			$content = str_replace("(bereso_show_item_share_id)",$item_sharing,$content);
		}
		else // item not shared
		{
			$content = str_replace("(bereso_show_item_sharing)",null,$content);
		}

		// ocr enabled? show status		
		if (Config::get_config("ocr_enabled") == 1 && User::get_ocr($user) == true) // is ocr enabled for this user and global
		{
			// ocr enabled for this item?
			if (Item::get_ocr($item) == true)
			{
				// ocr for this item enabled but still pending
				if (Item::get_ocr_text($item) == null)
				{
					$content = str_replace("(bereso_show_ocr_status)",File::read_file("templates/show-ocr_status-pending.html"),$content);
				}
				else // ocr for this item is enabled and done
				{
					$content = str_replace("(bereso_show_ocr_status)",File::read_file("templates/show-ocr_status-done.html"),$content);
				}
			}
			else // disabled for this item
			{
				$content = str_replace("(bereso_show_ocr_status)",File::read_file("templates/show-ocr_status-disabled.html"),$content);
			}
		}
		else 
		{
			$content = str_replace("(bereso_show_ocr_status)",null,$content); // no ocr allowed - delete the replace placeholder
		}

		$content = str_replace("(bereso_show_item_images)",$content_item,$content);
		if ($item_favorite == true) { $content = str_replace("(berso_show_item_favorite)",File::read_file("templates/show-item-favorite.html"),$content); } else { $content = str_replace("(berso_show_item_favorite)",null,$content); }
		$content = str_replace("(bereso_show_item_text)",$item_text_higlighted,$content);
		$content = str_replace("(bereso_show_item_name)",$row['item_name'],$content);
		$content = str_replace("(bereso_show_item_id)",$item,$content);
		$content = str_replace("(bereso_show_item_timestamp_creation)",Time::timestamp_to_datetime($row['item_timestamp_creation']),$content);
		$content = str_replace("(bereso_show_item_timestamp_edit)",Time::timestamp_to_datetime($row['item_timestamp_edit']),$content);
		$content = str_replace("(bereso_show_item_imagename)",Image::get_filename($item),$content);

	}
}
else
{
	Log::die ("CHECK: show item owner failed");
}


?>