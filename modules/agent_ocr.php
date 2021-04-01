<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Agent OCR
// included by ../index.php
// ###################################


// ########################
// ##      BeReSo        ##
// ##  OCR AGENT VERSION ##
// ##		1.1          ##
// ##     REQUIRED       ##
// ########################

// is ocr activated
if (Config::get_config("ocr_enabled") == "1")
{
	// check if agent ocr_password is matching
	if ($ocr_password == Config::get_config("ocr_password"))
	{
		$output_default = false; // do not use default output template


		// send a list of all images that need ocr to the client - preview images are ignored
		if ($action == "list")	
		{
			// Format:
			// ITEM_ID,IMAGE_URL
			// Each line is one image of an item, newline UNIX format \n

			// Example:
			// 5,https://bereso/images/3/601bb10defcd0_1.png
			// 5,https://bereso/images/3/601bb10defcd0_2.png
			// 6,https://bereso/images/4/601bb10dea321_1.png
			
			// get all items with ocr enabled (=1) and no ocr text
			if ($result = $sql->query("SELECT item_id, item_user FROM bereso_item WHERE item_ocr='1' AND item_ocr_text IS NULL")) // check for true null in text and enabled ocr for this item
			{	
				while ($row = $result -> fetch_assoc())
				{
					if ($result2 = $sql->query("SELECT images_image_id from bereso_images WHERE images_item='".$row['item_id']."' AND images_image_id > 0 ORDER BY images_image_id ASC")) // All images of item except the first (preview) one
					{	
						while ($row2 = $result2 -> fetch_assoc())
						{
							$output .= $row['item_id'].",".$bereso['url'].Image::get_foldername_by_user_id($row['item_user']).Image::get_filenamecomplete($row['item_id'],$row2['images_image_id'])."\n";
						}
					}
				}
			}
		}


		// save ocr text
		if ($action == "save")
		{
			// add ocr text to existing one - if more than one page is saved
			$old_ocr_text = Item::get_ocr_text($item);					

			// save the entry
			Item::set_ocr_text($item,$old_ocr_text . $ocr_text);

			// on error write error as ocr_text - or else it will repeat everytime the agent starts
			if (Item::get_ocr_text($item) == null) { Item::set_ocr_text($item,"OCR_AGENT_ERROR"); }

			// return message to the agent
			$output = "Saved ocr text for item: " . $item;
		}
	}
	else 
	{
		Log::die ("OCR: Authentification failed");
	}
}
else 
{
	Log::die ("OCR: disabled");
}

?>
