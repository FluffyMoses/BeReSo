<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Share
// included by ../index.php
// ###################################

// Show recipe

// when $shareid is set
if(strlen($shareid) > 0)
{
	// load template
	$content = $f->read_file("templates/share.txt");

	if ($result = $sql->query("SELECT recipe_name, recipe_text, recipe_imagename, recipe_user from bereso_recipe WHERE recipe_shareid='".$shareid."'"))
	{	
		$row = $result -> fetch_assoc();
			
		// if entry with this share id exists
		if (mysqli_num_rows($result) == 1)
		{
			
			// Highlight Tags with links
			$recipe_text_higlighted = $f->highlight_text_share($row['recipe_text']);
				
			// templates for images
			$content_item = null;
			for ($i=1;$i<=3;$i++)
			{
				if (file_exists($bereso['recipe_images'].$row['recipe_imagename']."_".$i.$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_".$i))) 
				{
					$content_item .= $f->read_file("templates/share-item.txt");
					$content_item = str_replace("(bereso_share_image_id)",$i,$content_item);
					$content_item = str_replace("(bereso_share_image_extension)",$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_".$i),$content_item);
				}
			}
			
			// add to navigation
			$navigation .= $f->read_file("templates/share-navigation.txt");				
			$navigation = str_replace("(bereso_share_id)",$shareid,$navigation);
			
			// build output			
			$content = str_replace("(bereso_share_item)",$content_item,$content);
			$content = str_replace("(bereso_share_username)",$f->get_user_name_by_user_id($row['recipe_user']),$content);
			$content = str_replace("(bereso_share_text)",$recipe_text_higlighted,$content);
			$content = str_replace("(bereso_share_name)",$row['recipe_name'],$content);
			$content = str_replace("(bereso_share_id)",$shareid,$content);
			$content = str_replace("(bereso_share_imagename)",$row['recipe_imagename'],$content);		
			$title .= " - " . $row['recipe_name'];
						
		}
		// error message - recipe not shared or does not exist
		else
		{
			$content = $f->read_file("templates/share-error.txt");
		}
	}	

}
?>