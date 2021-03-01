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
	$content = File::read_file("templates/share.txt");

	if ($result = $sql->query("SELECT item_name, item_text, item_imagename, item_user from bereso_item WHERE item_shareid='".$shareid."'"))
	{	
		$row = $result -> fetch_assoc();
			
		// if entry with this share id exists
		if (mysqli_num_rows($result) == 1)
		{
			
			// Highlight Tags with links
			$item_text_higlighted = Text::highlight_text_share($row['item_text']);
				
			// templates for images
			$content_item = null;
			for ($i=1;$i<=5;$i++)
			{
				if (file_exists($bereso['images'].$row['item_imagename']."_".$i.Image::search_extension($bereso['images'].$row['item_imagename']."_".$i))) 
				{
					$content_item .= File::read_file("templates/share-item.txt");
					$content_item = str_replace("(bereso_share_image_id)",$i,$content_item);
					$content_item = str_replace("(bereso_share_image_extension)",Image::search_extension($bereso['images'].$row['item_imagename']."_".$i),$content_item);
				}
			}
			
			// add to navigation
			$navigation .= File::read_file("templates/share-navigation.txt");				
			$navigation = str_replace("(bereso_share_id)",$shareid,$navigation);
			
			// build output			
			$content = str_replace("(bereso_share_item)",$content_item,$content);
			$content = str_replace("(bereso_share_username)",User::get_name_by_id($row['item_user']),$content);
			$content = str_replace("(bereso_share_text)",$item_text_higlighted,$content);
			$content = str_replace("(bereso_share_name)",$row['item_name'],$content);
			$content = str_replace("(bereso_share_id)",$shareid,$content);
			$content = str_replace("(bereso_share_imagename)",$row['item_imagename'],$content);		
			$title .= " - " . $row['item_name'];
						
		}
		// error message - item not shared or does not exist
		else
		{
			$content = File::read_file("templates/share-error.txt");
		}
	}	

}
?>