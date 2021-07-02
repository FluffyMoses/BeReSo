<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// User config
// included by ../index.php
// ###################################


$userconfig_message = null; // init error/success message variable


// User configuration
if ($action == "saveconfig") // save configuration
{
	// if all post variables are ok
	if ($form_config_error == 0)
	{
		// save all configs
		Config::set_userconfig("userconfig_items_per_page",$bereso_items_per_page,$user);
		if ($bereso_wakescreenlock == "wakescreenlock") { $bereso_wakescreenlock = 1; } else { $bereso_wakescreenlock = 0; } // if checked set to 1 - else 0
		Config::set_userconfig("userconfig_wakescreenlock",$bereso_wakescreenlock,$user);
		if ($bereso_newline_after_hashtag_insert == "newline_after_hashtag_insert") { $bereso_newline_after_hashtag_insert = 1; } else { $bereso_newline_after_hashtag_insert = 0; } // if checked set to 1 - else 0
		Config::set_userconfig("userconfig_newline_after_hashtag_insert",$bereso_newline_after_hashtag_insert,$user);
		if ($bereso_ocr_checked_new_item == "ocr_checked_new_item") { $bereso_ocr_checked_new_item = 1; } else { $bereso_ocr_checked_new_item = 0; } // if checked set to 1 - else 0
		Config::set_userconfig("userconfig_ocr_checked_new_item",$bereso_ocr_checked_new_item,$user);

		// return message
		$userconfig_message = "<div id=\"messagepopup\" style=\"background: green;\"><font color=\"white\">(bereso_template-userconfig_saved)</font></div>";

		Log::useraction($user,$module,$action,"Saved user config");  // log when user_log enabled
	}
	else // wrong characters in post variables
	{
		// return message
		$userconfig_message = "<div id=\"messagepopup\" style=\"background: red;\"><font color=\"white\">(bereso_template-userconfig_error_text_characters)</font></div>";

		Log::useraction($user,$module,$action,"Saving user config failed - wrong characters");  // log when user_log enabled

		// buffer the input and display it again in the form
		if ($bereso_wakescreenlock == "wakescreenlock") { $buffer['userconfig_wakescreenlock'] = 1; } else { $buffer['userconfig_wakescreenlock'] = 0; }
		$buffer['userconfig_items_per_page'] = $bereso_items_per_page;
		if ($bereso_newline_after_hashtag_insert == "newline_after_hashtag_insert") { $buffer['userconfig_newline_after_hashtag_insert'] = 1; } else { $buffer['userconfig_newline_after_hashtag_insert'] = 0; }
		if ($bereso_ocr_checked_new_item == "ocr_checked_new_item") { $buffer['userconfig_ocr_checked_new_item'] = 1; } else { $buffer['userconfig_ocr_checked_new_item'] = 0; }
	}

	// activate the messagepopup
	$userconfig_message .=  "\n<script src=\"templates/js/show_messagepopup.js\"></script>\n";

	// set action to null to load user configuration management after saving
	$action = null;
}


// load config form
if ($action == null)
{
	$content = File::read_file("templates/userconfig.html");

	// if form was not submitted or configuration saved - load some values direct from database - else show the posted value
	if (!isset($buffer))
	{
		$buffer['userconfig_items_per_page'] = Config::get_userconfig("userconfig_items_per_page",$user);
		$buffer['userconfig_wakescreenlock'] = Config::get_userconfig("userconfig_wakescreenlock",$user);
		$buffer['userconfig_newline_after_hashtag_insert'] = Config::get_userconfig("userconfig_newline_after_hashtag_insert",$user);
		$buffer['userconfig_ocr_checked_new_item'] = Config::get_userconfig("userconfig_ocr_checked_new_item",$user);
	}

	// insert config values in template
	if ($buffer['userconfig_wakescreenlock'] == 1) { $content = str_replace("(bereso_userconfig_wakescreenlock)","checked",$content); } else { $content = str_replace("(bereso_userconfig_wakescreenlock)",null,$content); }
	$content = str_replace("(bereso_userconfig_items_per_page)",$buffer['userconfig_items_per_page'],$content);
	if ($buffer['userconfig_newline_after_hashtag_insert'] == 1) { $content = str_replace("(bereso_userconfig_newline_after_hashtag_insert)","checked",$content); } else { $content = str_replace("(bereso_userconfig_newline_after_hashtag_insert)",null,$content); }

	$content = str_replace("(bereso_userconfig_message)",$userconfig_message,$content);

	// if user has ocr permissions - show configs related to ocr
	if (User::get_ocr($user) == 1 && Config::get_config("ocr_enabled") == 1)
	{
		$content = str_replace("(bereso_userconfig_ocr)",File::read_file("templates/userconfig-ocr.html"),$content);
		// insert config values in template
		if ($buffer['userconfig_ocr_checked_new_item'] == 1) { $content = str_replace("(bereso_userconfig_ocr_checked_new_item)","checked",$content); } else { $content = str_replace("(bereso_userconfig_ocr_checked_new_item)",null,$content); }
	}
	else // no ocr permission 
	{
		$content = str_replace("(bereso_userconfig_ocr)",null,$content);
	}
}
?>