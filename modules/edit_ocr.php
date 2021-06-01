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
			$edit_text_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content
			$edit_searchable_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content

			// Item ocr toggle on/off
			if ($action == "ocr")
			{
				$item_ocr = Item::get_ocr($item);
		
				// item ocr? => disable ocr
				if ($item_ocr == true) 
				{
					Item::set_ocr($item,false);
					Log::useraction($user,$module,$action,"OCR on item $item disabled");  // log when user_log enabled
				}
				else
				{
					Item::set_ocr($item,true);
					Log::useraction($user,$module,$action,"OCR on item $item enabled");  // log when user_log enabled
				}	

				// reset ocr text on enable/disable
				Item::set_ocr_text($item,NULL);  // needs to be null not "" !

				// reset ocr searchable
				Item::set_ocr_searchable($item,false);				

				// redirect back to show.php
				header('Location: index.php?module=show&item='.$item,true, 302 ); 
				exit(); // stops the rest of the script from running 
			}

			// save the edited ocr text
			if ($action == "edit")
			{
				// check forbidden characters
				if ($form_item_text_error == 0) // no error -> save ocr text
				{
					Item::set_ocr_text($item,$edit_text); // save entry
					Item::set_ocr_searchable($item,$edit_searchable);

					$ocr_edit_message = "<div id=\"messagepopup\" style=\"background: green;\"><font color=\"white\">(bereso_template-edit_ocr_entry_saved)</font></div>";

					if ($edit_searchable == true) { $edit_log_searchable = 1; } else { $edit_log_searchable = 0; }
					Log::useraction($user,$module,$action,"OCR text saved for item $item - searchable($edit_log_searchable)");  // log when user_log enabled
				}
				else // wrong characters
				{
					$edit_text_replace = $edit_text;
					$edit_searchable_replace = $edit_searchable;

					$ocr_edit_message = "<div id=\"messagepopup\" style=\"background: red;\"><font color=\"white\">(bereso_template-edit_ocr_entry_error_text_characters)</font></div>";
					Log::useraction($user,$module,$action,"OCR text not saved for item $item - Wrong characters");  // log when user_log enabled
				}

				// activate the messagepopup
				$ocr_edit_message .=  "\n<script src=\"templates/js/show_messagepopup.js\"></script>\n";

				// load edit_ocr-form again with message success or failure
				$action = null;
			}

			// show form
			if ($action == null)
			{
				$content = File::read_file("templates/edit_ocr.html");
				if (strlen($edit_text_replace) > 0) { $content = str_replace("(bereso_edit_ocr_edit_text)",$edit_text_replace,$content); } else { $content = str_replace("(bereso_edit_ocr_edit_text)",Item::get_ocr_text($item),$content); } // if saving the text did not work do not overwrite it
				$content = str_replace("(bereso_edit_ocr_message)",$ocr_edit_message,$content);
				$content = str_replace("(bereso_edit_ocr_item_id)",$item,$content);

				// is ocr_text_searchable checked?
				if (strlen($edit_text_replace) > 0) // if saving the text did not work do not overwrite it
				{
					if ($edit_searchable_replace == true)
					{
						$content = str_replace("(bereso_edit_ocr_searchable)",'checked="checked"',$content);
					}
					else
					{
						$content = str_replace("(bereso_edit_ocr_searchable)",null,$content);
					}
				}
				else // load value from database
				{
					if (Item::get_ocr_searchable($item) == true)
					{
						$content = str_replace("(bereso_edit_ocr_searchable)",'checked="checked"',$content);
					}
					else
					{
						$content = str_replace("(bereso_edit_ocr_searchable)",null,$content);
					}
				}
				

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