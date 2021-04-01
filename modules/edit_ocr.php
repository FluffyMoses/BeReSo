<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Edit Ocr 
// included by ../index.php
// ###################################

// is ocr activated global
if (Config::get_config("ocr_enabled") == 1)
{
	// ocr activated for the user
	if (User::get_ocr($user) == true)
	{

		// check if user is owner of this item
		if (Item::is_owned_by_user($user,$item)) {

			$ocr_edit_message = null;

			// Item ocr toggle on/off
			if ($action == "ocr")
			{
				$item_ocr = Item::get_ocr($item);
		
				// item ocr? => disable ocr
				if ($item_ocr == true) 
				{
					Item::set_ocr($item,false);
				}
				else
				{
					Item::set_ocr($item,true);
				}	

				// reset ocr text on enable/disable
				Item::set_ocr_text($item,NULL);  // needs to be null not "" !

				// redirect back to show.php
				header('Location: ?module=show&item='.$item,true, 301 ); 
				exit(); // stops the rest of the script from running 
			}

			// save the edited ocr text
			if ($action == "edit")
			{
				Item::set_ocr_text($item,$edit_text); // save entry

				$ocr_edit_message = "<font color=\"green\">(bereso_template-edit_ocr_entry_saved)</font>";

				// load edit_ocr-form again with message success or failure
				$action = null;
			}

			// show form
			if ($action == null)
			{
				$content = File::read_file("templates/edit_ocr.html");
				$content = str_replace("(bereso_edit_ocr_edit_text)",Item::get_ocr_text($item),$content);
				$content = str_replace("(bereso_edit_ocr_message)",$ocr_edit_message,$content);
				$content = str_replace("(bereso_edit_ocr_item_id)",$item,$content);
				

				// add to navigation -> Last item
				$navigation2 .= File::read_file("templates/main-navigation2-last_item.html");
				$navigation2 = str_replace("(main-navigation-last_item)",Image::get_filenamecomplete($item,0),$navigation2);
				$navigation2 = str_replace("(main-navigation-last_item_value)",$item,$navigation2);	
			}


		}
		else
		{
			Log::die ("CHECK: ocr owner failed");
		}

	}
	else
	{
		Log::die ("OCR: disabled for this user");
	}
}
else 
{
	Log::die ("OCR: disabled");
}
?>