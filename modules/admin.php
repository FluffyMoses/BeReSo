<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Admin
// included by ../index.php
// ###################################


// check if user is admin
if (User::is_admin($user))
{
	$admin_config_message = null; // init error/success message variable

	// list admincenter navigation
	if ($action == null)
	{
		$content = File::read_file("templates/admin.html");

		// Statistic information - replaces		
		$content = str_replace("(bereso_admin_center_statistic_user)",mysqli_num_rows($sql->query("SELECT * from bereso_user")),$content); // User count
		$content = str_replace("(bereso_admin_center_statistic_items)",mysqli_num_rows($sql->query("SELECT * from bereso_item")),$content); // Item count
		$content = str_replace("(bereso_admin_center_statistic_items_images)",mysqli_num_rows($sql->query("SELECT * from bereso_images")),$content); // Images count
		$content = str_replace("(bereso_admin_center_statistic_items_ocr)",mysqli_num_rows($sql->query("SELECT * from bereso_item WHERE item_ocr='1'")),$content); // Item OCR count
		$content = str_replace("(bereso_admin_center_statistic_items_shared)",mysqli_num_rows($sql->query("SELECT * from bereso_item WHERE LENGTH(item_shareid) > 0")),$content); // Item share count
	}


	// BeReSo configuration
	if ($action == "saveconfig") // save configuration
	{
		// if all post variables are ok
		if ($form_config_error == 0)
		{
			// save all configs
			Config::set_config("url",$bereso_url);
			if ($bereso_httpsredirect == "httpsredirect") { $bereso_httpsredirect = 1; } else { $bereso_httpsredirect = 0; } // if checked set to 1 - else 0
			Config::set_config("https_redirect",$bereso_httpsredirect);
			Config::set_config("images",$bereso_images);
			Config::set_config("images_thumbnail_height",$bereso_images_thumbnail_height);
			Config::set_config("timezone",$bereso_timezone);
			Config::set_config("datetimestring",$bereso_datetime);
			Config::set_config("session_lifetime",$bereso_sessionlifetime);
			Config::set_config("new_amount_images",$bereso_new_amount_images);
			Config::set_config("ocr_password",$bereso_ocr_password);
			if ($bereso_ocr_enabled == "ocr_enabled") { $bereso_ocr_enabled = 1; } else { $bereso_ocr_enabled = 0; } // if checked set to 1 - else 0
			Config::set_config("ocr_enabled",$bereso_ocr_enabled);
			Config::set_config("login_motd",$bereso_login_motd);

			// return message
			$admin_config_message = "<font color=\"green\">(bereso_template-admin_config_saved)</font>";
		}
		else // wrong characters in post variables
		{
			// return message
			$admin_config_message = "<font color=\"red\">(bereso_template-admin_config_error_text_characters)</font>";
		}

		// set config in running script
		$bereso['url'] = $bereso_url;
		if ($bereso_httpsredirect == 1 or $bereso_httpsredirect == "httpsredirect") { $bereso['https_redirect'] = true; } else { $bereso['https_redirect'] = false; }
		$bereso['images'] = $bereso_images;
		$bereso['images_thumbnail_height'] = $bereso_images_thumbnail_height;
		$bereso['timezone'] = $bereso_timezone;
		$bereso['datetimestring'] = $bereso_datetime;
		$bereso['session_lifetime'] = $bereso_sessionlifetime;
		$bereso['new_amount_images'] = $bereso_new_amount_images;

		// buffer the rest of the config settings that have no $bereso value - if the script cannot save it displays the text/value last submitted instead of the db value
		$buffer['ocr_enabled'] = $bereso_ocr_enabled;
		$buffer['ocr_password'] = $bereso_ocr_password;
		$buffer['login_motd'] = $bereso_login_motd;
		
		// set action to config to load configuration management after saving
		$action = "config";
	}
	
	if ($action == "config") // show and edit configuration
	{
		$content = File::read_file("templates/admin-config.html");

		// if form was not submitted and configuration saved - load some values direct from database - else show the posted value
		if (!isset($buffer))
		{
			$buffer['ocr_enabled'] = Config::get_config("ocr_enabled");
			$buffer['ocr_password'] = Config::get_config("ocr_password");
			$buffer['login_motd'] = Config::get_config("login_motd");
		}

		// insert config values in template
		$content = str_replace("(bereso_admin_config_url)",$bereso['url'],$content);
		if ($bereso['https_redirect'] == true) { $content = str_replace("(bereso_admin_config_httpsredirect)","checked",$content); } else { $content = str_replace("(bereso_admin_config_httpsredirect)",null,$content); }
		$content = str_replace("(bereso_admin_config_images)",$bereso['images'],$content);
		$content = str_replace("(bereso_admin_config_images_thumbnail_height)",$bereso['images_thumbnail_height'],$content);
		$content = str_replace("(bereso_admin_config_timezone)",$bereso['timezone'],$content);
		$content = str_replace("(bereso_admin_config_datetime)",$bereso['datetimestring'],$content);
		$content = str_replace("(bereso_admin_config_sessionlifetime)",$bereso['session_lifetime'],$content);
		$content = str_replace("(bereso_admin_config_new_amount_images)",$bereso['new_amount_images'],$content);
		$content = str_replace("(bereso_admin_config_message)",$admin_config_message,$content);
		$content = str_replace("(bereso_admin_config_ocr_password)",$buffer['ocr_password'],$content);
		if ($buffer['ocr_enabled'] == true or $buffer['ocr_enabled'] == "1") { $content = str_replace("(bereso_admin_config_ocr_enabled)","checked",$content); } else { $content = str_replace("(bereso_admin_config_ocr_enabled)",null,$content); }
		$content = str_replace("(bereso_admin_config_login_motd)",$buffer['login_motd'],$content);


	}
	
}
else // User is not an admin
{
	Log::die ("CHECK: user is no admin");
}

?>

