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
	    $taggroup_new_addmessage = "<font color=\"green\">(bereso_template-new_taggroup_entry_saved) <b>\"$add_name\"</b></font>";
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

			if ($form_taggroup_name_error == 1 or strlen($add_name) == 0) { $taggroup_new_addmessage = "<font color=\"red\">(bereso_template-new_taggroup_entry_error_name_characters)</font>"; $form_taggroup_name_error = 1; } // name wrong char or empty
			elseif ($form_taggroup_text_error == 1) { $taggroup_new_addmessage = "<font color=\"red\">(bereso_template-new_taggroup_entry_error_text_characters)</font>"; } // text wrong char
			elseif (Tags::is_taggroup($user,$add_name) == true) { $taggroup_new_addmessage = "<font color=\"red\">(bereso_template-new_taggroup_entry_error_name_exists)</font>"; $form_taggroup_exists_error = 1; } // text wrong char

			Log::useraction($user,$module,$action,"Taggroup saving failed - Errors: name($form_taggroup_name_error) text($form_taggroup_text_error) exists($form_taggroup_exists_error)");  // log when user_log enabled
	}	
	// load new_taggroup-form again with message success or failure
	$action = null;
}

// Show form for new taggroup
if ($action == null){
	$content = File::read_file("templates/new_taggroup.html");
	$content = str_replace("(bereso_new_taggroup_add_name)",$add_name,$content); // if entry is saved with errors - show name again
	$content = str_replace("(bereso_new_taggroup_add_text)",$add_text,$content); // if entry is saved with errors - show text again
	$content = str_replace("(bereso_new_taggroup_message)",$taggroup_new_addmessage,$content); // insert or clear message field

	// load all hashtags and add all in the dropdown menu
	if ($result = $sql->query("SELECT DISTINCT bereso_tags.tags_name from bereso_tags INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_item.item_user='".User::get_id_by_name($user)."' ORDER BY bereso_tags.tags_name ASC"))
	{	
		$insert_hashtag = null;
		while ($row = $result -> fetch_assoc())
		{
			$insert_hashtag .= File::read_file("templates/new_taggroup-hashtag.html");
			$insert_hashtag = str_replace("(bereso_new_taggroup_insert_hashtag_name)",$row['tags_name'],$insert_hashtag);
			$insert_hashtag = str_replace("(bereso_new_taggroup_insert_hashtag_value)","#".$row['tags_name']." ",$insert_hashtag);
		}
	}
	$content = str_replace("(bereso_new_taggroup_insert_hashtag)",$insert_hashtag,$content); // insert option tags of all hashtags 

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