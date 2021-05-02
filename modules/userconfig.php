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

		// return message
		$userconfig_message = "<font color=\"green\">(bereso_template-userconfig_saved)</font>";

		Log::useraction($user,$module,$action,"Saved user config");  // log when user_log enabled
	}
	else // wrong characters in post variables
	{
		// return message
		$userconfig_message = "<font color=\"red\">(bereso_template-userconfig_error_text_characters)</font>";

		Log::useraction($user,$module,$action,"Saving user config failed - wrong characters");  // log when user_log enabled

		// buffer the input and display it again in the form
		if ($bereso_wakescreenlock == "wakescreenlock") { $buffer['userconfig_wakescreenlock'] = 1; } else { $buffer['userconfig_wakescreenlock'] = 0; }
		$buffer['userconfig_items_per_page'] = $bereso_items_per_page;
		if ($bereso_newline_after_hashtag_insert == "newline_after_hashtag_insert") { $buffer['userconfig_newline_after_hashtag_insert'] = 1; } else { $buffer['userconfig_newline_after_hashtag_insert'] = 0; }
	}

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
	}

	// insert config values in template
	if ($buffer['userconfig_wakescreenlock'] == 1) { $content = str_replace("(bereso_userconfig_wakescreenlock)","checked",$content); } else { $content = str_replace("(bereso_userconfig_wakescreenlock)",null,$content); }
	$content = str_replace("(bereso_userconfig_items_per_page)",$buffer['userconfig_items_per_page'],$content);
	if ($buffer['userconfig_newline_after_hashtag_insert'] == 1) { $content = str_replace("(bereso_userconfig_newline_after_hashtag_insert)","checked",$content); } else { $content = str_replace("(bereso_userconfig_newline_after_hashtag_insert)",null,$content); }

	$content = str_replace("(bereso_userconfig_message)",$userconfig_message,$content);
}
?>