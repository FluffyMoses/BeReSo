<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Edit taggroup
// included by ../index.php
// ###################################


// check if user is owner of this taggroup
if (Tags::is_owned_by_user($user,Tags::get_taggroupid_name($user,$taggroupid))) {


	// edit new taggroup
	$taggroup_edit_message = null;
	$edit_name_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content
	$edit_text_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content


	// save edited taggroup 
	if ($action == "edit")
	{
		// check if no other enty exists with this name
		$form_taggroup_doubleentry_error = false;
		// get the old name and check if it is changed
		if ($result = $sql->query("SELECT group_name FROM bereso_group WHERE group_user='".User::get_id_by_name($user)."' and group_id='".$taggroupid."'"))
		{	
			$row = $result -> fetch_assoc();
			$old_taggroup_name = $row['group_name'];
		}
		
		if (strtolower($old_taggroup_name) != strtolower($edit_name)) // Taggroup name should be changed
		{
			if (Tags::is_taggroup($user,$edit_name) == true) // another enry already exists with this name and for this user
			{
				$form_taggroup_doubleentry_error = true;
			}
		}
		

		// check if name and text are ok and another entry with this name does not exist
		if ($form_taggroup_name_error == 0 && $form_taggroup_text_error == 0 && strlen($edit_name) > 0 && $form_taggroup_doubleentry_error == false)
		{
			$sql->query("UPDATE bereso_group SET group_name='".$edit_name."', group_text='".$edit_text."' WHERE group_user='".User::get_id_by_name($user)."' AND group_id='".$taggroupid."'");
			User::set_last_taggroup($user,$edit_name); // reset last_taggroup link for the case when the name is changed
		
			$taggroup_edit_message = "<font color=\"green\">(bereso_template-edit_taggroup_entry_saved) <b>\"$edit_name\"</b></font>";

			Log::useraction($user,$module,$action,"Edited taggroup $taggroupid");  // log when user_log enabled
		
		} 
		// form not correct
		else
		{
				// init variables for logging 0/1
				$form_taggroup_doubleentry_log_error = 0;

				if ($form_taggroup_name_error == 1 or strlen($edit_name) == 0) { $taggroup_edit_message = "<font color=\"red\">(bereso_template-edit_taggroup_entry_error_name_characters)</font>"; } // name wrong char or emtpy
				elseif ($form_taggroup_text_error == 1) { $taggroup_edit_message = "<font color=\"red\">(bereso_template-edit_taggroup_entry_error_text_characters)</font>"; } // text wrong char
				elseif ($form_taggroup_doubleentry_error == true) { $taggroup_edit_message = "<font color=\"red\">(bereso_template-edit_taggroup_entry_error_name_exists)</font>"; $form_taggroup_doubleentry_log_error = 1; } // text wrong char
				$edit_name_replace = $edit_name; // if set the form will replace the text with the variable content, not with the sql loaded content
				$edit_text_replace = $edit_text; // if set the form will replace the text with the variable content, not with the sql loaded content
				Log::useraction($user,$module,$action,"Editing taggroup $taggroupid failed - Errors: name($form_taggroup_name_error) text($form_taggroup_text_error) exists($form_taggroup_doubleentry_log_error)");  // log when user_log enabled
		}	
		// load edit_taggroup-form again with message success or failure
		$action = null;
	}


	// Show form for edit taggroup
	if ($action == null){
	// tag groups
		if ($result = $sql->query("SELECT group_name, group_text FROM bereso_group WHERE group_user='".User::get_id_by_name($user)."' and group_id='".$taggroupid."'"))
		{	
			$row = $result -> fetch_assoc();
			$content = File::read_file("templates/edit_taggroup.html");
			$content = str_replace("(bereso_edit_taggroupid)",$taggroupid,$content);
			if (strlen($edit_name_replace) > 0) { $content = str_replace("(bereso_edit_taggroup_edit_name)",$edit_name_replace,$content); } else { $content = str_replace("(bereso_edit_taggroup_edit_name)",$row['group_name'],$content); } // if set the form will replace the text with the variable content, not with the sql loaded content
			if (strlen($edit_text_replace) > 0) { $content = str_replace("(bereso_edit_taggroup_edit_text)",$edit_text_replace,$content); } else { $content = str_replace("(bereso_edit_taggroup_edit_text)",$row['group_text'],$content); } // if set the form will replace the text with the variable content, not with the sql loaded content
		}

		$content = str_replace("(bereso_edit_taggroup_message)",$taggroup_edit_message,$content); // insert or clear message field

		// Load all taggroups and hashtags for the dropdown menu
		if (Config::get_userconfig("userconfig_newline_after_hashtag_insert",$user) == 1) { $insert_after_hashtag = "\n"; } else { $insert_after_hashtag = " "; }
		// tag groups
		$insert_hashtag = null;
		if ($result = $sql->query("SELECT group_name, group_text FROM bereso_group WHERE group_user='".User::get_id_by_name($user)."' ORDER BY group_name ASC"))
		{	
			while ($row = $result -> fetch_assoc())
			{
				$insert_hashtag .= File::read_file("templates/edit_taggroup-hashtag.html");
				$insert_hashtag = str_replace("(bereso_edit_taggroup_insert_hashtag_name)","=== ".$row['group_name']." ===",$insert_hashtag);
				$insert_hashtag = str_replace("(bereso_edit_taggroup_insert_hashtag_value)",null,$insert_hashtag);
				// add one whitespace character at the end for the regular expression to match when the last word is a hashtag!
				$row['group_text'] = $row['group_text'] . " ";
				preg_match_all("/(#\w+)\s/", $row['group_text'], $matches);
				natcasesort($matches[0]);

				foreach ($matches[0] as $match)	
				{			
					$match = Text::remove_whitespace($match); // remove whitespace
					$insert_hashtag .= File::read_file("templates/edit_taggroup-hashtag.html");
					$insert_hashtag = str_replace("(bereso_edit_taggroup_insert_hashtag_name)","- ".str_replace("#",null,$match),$insert_hashtag);
					$insert_hashtag = str_replace("(bereso_edit_taggroup_insert_hashtag_value)",$match.$insert_after_hashtag,$insert_hashtag);
				}		
			}
		}
		// Tags
		$insert_hashtag .= File::read_file("templates/edit_taggroup-hashtag.html");
		$insert_hashtag = str_replace("(bereso_edit_taggroup_insert_hashtag_name)","=== Tags ===",$insert_hashtag);
		$insert_hashtag = str_replace("(bereso_edit_taggroup_insert_hashtag_value)",null,$insert_hashtag);
		if ($result = $sql->query("SELECT DISTINCT bereso_tags.tags_name from bereso_tags INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".User::get_id_by_name($user)."' ORDER BY bereso_tags.tags_name ASC"))
		{	
			while ($row = $result -> fetch_assoc())
			{
				// show tag in overview if it is not part of any tag group
				if (Tags::is_tag_in_taggroup($user,$row['tags_name']) == false) 
				{
					$insert_hashtag .= File::read_file("templates/edit_taggroup-hashtag.html");
					$insert_hashtag = str_replace("(bereso_edit_taggroup_insert_hashtag_name)","- ".str_replace("#",null,$row['tags_name']),$insert_hashtag);
					$insert_hashtag = str_replace("(bereso_edit_taggroup_insert_hashtag_value)","#".$row['tags_name'].$insert_after_hashtag,$insert_hashtag);
				}
			}
		}
		$content = str_replace("(bereso_edit_taggroup_insert_hashtag)",$insert_hashtag,$content); // insert option tags of all hashtags */
	}
}
else
{
	Log::die ("CHECK: edit taggroup owner failed");
}

?>