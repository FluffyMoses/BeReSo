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
// ##		1.4          ##
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
			// read content from the uploaded textfile and save it into the database
			// read uploaded file
			$ocr_text = File::read_file($ocr_text_file['tmp_name']);

			// strip all unwanted chars
			$ocr_text = Text::convert_letter($ocr_text,"a-z0-9 SPECIAL");

			// save the entry
			Item::set_ocr_text($item,$ocr_text);	

			// on error write error as ocr_text - or else it will repeat everytime the agent starts - also return fail or succes message
			if (Item::get_ocr_text($item) == null) { // no ocr text saved -> something went wrong
				Item::set_ocr_text($item,"TESSERACT_OCR_NO_CHARACTERS_RECOGNIZED");
				$output = "TESSERACT_OCR_NO_CHARACTERS_RECOGNIZED ".$item."\n";
			}
			else // text saved sucessfully
			{
				$output = "Saved ".strlen(Item::get_ocr_text($item))." Characters in Item ".$item."\n";
			}

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
