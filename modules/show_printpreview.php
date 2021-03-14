<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Show printpreview
// included by ../index.php
// ###################################

// check if user is owner of this item
if (Item::is_owned_by_user($user,$item)) {
	// Show printpreview no content - override output!
	$output_default = false; // do not use default output template

	// load template
	$output = File::read_file("templates/show_printpreview.html");

	if ($result = $sql->query("SELECT item_name, item_text, item_timestamp_creation, item_timestamp_edit from bereso_item WHERE item_user='".User::get_id_by_name($user)."' AND item_id='".$item."'"))
	{	
		$row = $result -> fetch_assoc();
		
		// Highlight Tags with links
		$item_text_higlighted = Text::highlight_text_share($row['item_text']);
		
		// templates for images
		$output_item = null;

		if ($result2 = $sql->query("SELECT images_image_id from bereso_images WHERE images_item='".$item."' AND images_image_id > 0 ORDER BY images_image_id ASC")) // All images of item except the first (preview) one
		{	
			while ($row2 = $result2 -> fetch_assoc())
			{
				$output_item .= File::read_file("templates/show_printpreview-item.html");
				$output_item = str_replace("(bereso_show_printpreview_item_image_id)",$row2['images_image_id'],$output_item);
				$output_item = str_replace("(bereso_show_printpreview_item_image_extension)",Image::get_fileextension($item,$row2['images_image_id']),$output_item);				
			}
		}
	
		// build output
		$output = str_replace("(bereso_show_printpreview_item_images)",$output_item,$output);
		$output = str_replace("(bereso_show_printpreview_item_previewimage)",Image::get_filenamecomplete($item,0),$output);
		$output = str_replace("(bereso_show_printpreview_item_text)",$item_text_higlighted,$output);
		$output = str_replace("(bereso_show_printpreview_item_name)",$row['item_name'],$output);
		$output = str_replace("(bereso_show_printpreview_item_id)",$item,$output);
		$output = str_replace("(bereso_show_printpreview_item_timestamp_creation)",Time::timestamp_to_datetime($row['item_timestamp_creation']),$output);
		$output = str_replace("(bereso_show_printpreview_item_timestamp_edit)",Time::timestamp_to_datetime($row['item_timestamp_edit']),$output);
		$output = str_replace("(bereso_show_printpreview_item_imagename)",Image::get_filename($item),$output);
		
	}
}
else
{
	Log::die ("CHECK: show printpreview item owner failed");
}
