<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// New taggroup
// included by ../index.php
// ###################################

// Add new taggroup
$taggroup_new_addmessage = null;


// save new taggroup 
if ($action == "add")
{
	// check if name and text are ok and taggroup does not exist
	if ($form_taggroup_name_error == 0 && $form_taggroup_text_error == 0 && strlen($add_name) > 0 && Tags::is_taggroup($user,$add_name) == false)
	{
		$sql->query("INSERT into bereso_group (group_name, group_text, group_user) VALUES ('".$add_name."','".$add_text."','".User::get_id_by_name($user)."')");
		$add_id = $sql->insert_id;
	    $taggroup_new_addmessage = "<div id=\"messagepopup\" style=\"background: green;\"><font color=\"white\">(bereso_template-new_taggroup_entry_saved) <b>\"$add_name\"</b></font></div>";
		Log::useraction($user,$module,$action,"Taggroup saved $add_id");  // log when user_log enabled

		// clear $add_name and $add_text for the form
		$add_name = null;
		$add_text = null;

		
	} 
	// form not correct
	else
	{
			// init variables for logging 0/1
			$form_taggroup_exists_error = 0;

			if ($form_taggroup_name_error == 1 or strlen($add_name) == 0) { $taggroup_new_addmessage = "<div id=\"messagepopup\" style=\"background: red;\"><font color=\"white\">(bereso_template-new_taggroup_entry_error_name_characters)</font></div>"; $form_taggroup_name_error = 1; } // name wrong char or empty
			elseif ($form_taggroup_text_error == 1) { $taggroup_new_addmessage = "<div id=\"messagepopup\" style=\"background: red;\"><font color=\"white\">(bereso_template-new_taggroup_entry_error_text_characters)</font></div>"; } // text wrong char
			elseif (Tags::is_taggroup($user,$add_name) == true) { $taggroup_new_addmessage = "<div id=\"messagepopup\" style=\"background: red;\"><font color=\"white\">(bereso_template-new_taggroup_entry_error_name_exists)</font></div>"; $form_taggroup_exists_error = 1; } // text wrong char

			Log::useraction($user,$module,$action,"Taggroup saving failed - Errors: name($form_taggroup_name_error) text($form_taggroup_text_error) exists($form_taggroup_exists_error)");  // log when user_log enabled
	}	

	// activate the messagepopup
	$taggroup_new_addmessage .=  "\n<script src=\"templates/js/show_messagepopup.js\"></script>\n";

	// load new_taggroup-form again with message success or failure
	$action = null;
}

// Show form for new taggroup
if ($action == null){
	$content = File::read_file("templates/new_taggroup.html");
	$content = str_replace("(bereso_new_taggroup_add_name)",$add_name,$content); // if entry is saved with errors - show name again
	$content = str_replace("(bereso_new_taggroup_add_text)",$add_text,$content); // if entry is saved with errors - show text again
	$content = str_replace("(bereso_new_taggroup_message)",$taggroup_new_addmessage,$content); // insert or clear message field

	// Load all taggroups and hashtags for the dropdown menu
	if (Config::get_userconfig("userconfig_newline_after_hashtag_insert",$user) == 1) { $insert_after_hashtag = "\n"; } else { $insert_after_hashtag = " "; }
	// tag groups
	$insert_hashtag = null;
	if ($result = $sql->query("SELECT group_name, group_text FROM bereso_group WHERE group_user='".User::get_id_by_name($user)."' ORDER BY group_name ASC"))
	{	
		while ($row = $result -> fetch_assoc())
		{
			$insert_hashtag .= File::read_file("templates/new_taggroup-hashtag.html");
			$insert_hashtag = str_replace("(bereso_new_taggroup_insert_hashtag_name)","=== ".$row['group_name']." ===",$insert_hashtag);
			$insert_hashtag = str_replace("(bereso_new_taggroup_insert_hashtag_value)",null,$insert_hashtag);
			// add one whitespace character at the end for the regular expression to match when the last word is a hashtag!
			$row['group_text'] = $row['group_text'] . " ";
			preg_match_all("/(#\w+)\s/", $row['group_text'], $matches);
			natcasesort($matches[0]);

			foreach ($matches[0] as $match)	
			{			
				$match = Text::remove_whitespace($match); // remove whitespace
				$insert_hashtag .= File::read_file("templates/new_taggroup-hashtag.html");
				$insert_hashtag = str_replace("(bereso_new_taggroup_insert_hashtag_name)","- ".str_replace("#",null,$match),$insert_hashtag);
				$insert_hashtag = str_replace("(bereso_new_taggroup_insert_hashtag_value)",$match.$insert_after_hashtag,$insert_hashtag);
			}		
		}
	}
	// Tags
	$insert_hashtag .= File::read_file("templates/new_taggroup-hashtag.html");
	$insert_hashtag = str_replace("(bereso_new_taggroup_insert_hashtag_name)","=== Tags ===",$insert_hashtag);
	$insert_hashtag = str_replace("(bereso_new_taggroup_insert_hashtag_value)",null,$insert_hashtag);
	if ($result = $sql->query("SELECT DISTINCT bereso_tags.tags_name from bereso_tags INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".User::get_id_by_name($user)."' ORDER BY bereso_tags.tags_name ASC"))
	{	
		while ($row = $result -> fetch_assoc())
		{
			// show tag in overview if it is not part of any tag group
			if (Tags::is_tag_in_taggroup($user,$row['tags_name']) == false) 
			{
				$insert_hashtag .= File::read_file("templates/new_taggroup-hashtag.html");
				$insert_hashtag = str_replace("(bereso_new_taggroup_insert_hashtag_name)","- ".str_replace("#",null,$row['tags_name']),$insert_hashtag);
				$insert_hashtag = str_replace("(bereso_new_taggroup_insert_hashtag_value)","#".$row['tags_name'].$insert_after_hashtag,$insert_hashtag);
			}
		}
	}
	$content = str_replace("(bereso_new_taggroup_insert_hashtag)",$insert_hashtag,$content); // insert option tags of all hashtags */

	// Load additional image uploads - preview 0 and image 1 are always hardcoded in the template
	$content_optional_images = null;
	for ($i=2;$i<=$bereso['new_amount_images'];$i++)
	{
		$content_optional_images .= File::read_file("templates/new-optional_images.html");
		$content_optional_images = str_replace("(bereso_new_item_image_optional_image_id)",$i,$content_optional_images);
	}	
	$content = str_replace("(bereso_new_item_optional_images)",$content_optional_images,$content); // Insert additional images into main template
}
?>