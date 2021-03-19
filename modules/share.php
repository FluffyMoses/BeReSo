<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Share
// included by ../index.php
// ###################################

// Share item

// when $shareid is set
if(strlen($shareid) > 0)
{
	// load template
	$content = File::read_file("templates/share.html");

	if ($result = $sql->query("SELECT item_id, item_name, item_text, item_user from bereso_item WHERE item_shareid='".$shareid."'"))
	{	
		$row = $result -> fetch_assoc();
			
		// if entry with this share id exists
		if (mysqli_num_rows($result) == 1)
		{
			
			// Highlight Tags with links
			$item_text_higlighted = Text::highlight_text_share($row['item_text']);
				
			// templates for images
			$content_item = null;
			if ($result2 = $sql->query("SELECT images_image_id from bereso_images WHERE images_item='".$row['item_id']."' AND images_image_id > 0 ORDER BY images_image_id ASC")) // All images of item except the first (preview) one
			{	
				while ($row2 = $result2 -> fetch_assoc())
				{
					$content_item .= File::read_file("templates/share-item.html");
					$content_item = str_replace("(bereso_share_image_id)",$row2['images_image_id'],$content_item);
					$content_item = str_replace("(bereso_share_image_extension)",Image::get_fileextension($row['item_id'],$row2['images_image_id']),$content_item);				
				}
			}

			
			// add to navigation
			$navigation .= File::read_file("templates/main-navigation-share.html");				
			$navigation = str_replace("(bereso_share_id)",$shareid,$navigation);
			
			// build output			
			$content = str_replace("(bereso_share_item)",$content_item,$content);
			$content = str_replace("(bereso_share_username)",User::get_name_by_id($row['item_user']),$content);
			$content = str_replace("(bereso_share_text)",$item_text_higlighted,$content);
			$content = str_replace("(bereso_share_name)",$row['item_name'],$content);
			$content = str_replace("(bereso_share_id)",$shareid,$content);
			$content = str_replace("(bereso_share_imagename)",Image::get_filename($row['item_id']),$content);
			$content = str_replace("(bereso_images_share)",Image::get_foldername_by_shareid($shareid),$content);
			$title .= " - " . $row['item_name'];
						
		}
		// error message - item not shared or does not exist
		else
		{
			$content = File::read_file("templates/share-error.html");
		}
	}	

}
?>