<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// New recipe
// included by ../index.php
// ###################################

// Add new recipe
$recipe_new_addmessage = null;

// save new entry and uploaded file
if ($action == "add")
{
	$form_recipe_file_type_error = false;

	// check max_file_upload size
	if ($_SERVER['CONTENT_LENGTH'] < $bereso['max_upload_size'])
	{
		// Filetype only jpg or png		
		if (file_exists($add_photo0['tmp_name'])) {
			if (!($f->get_image_extension($add_photo0['tmp_name']) == ".jpg" or $f->get_image_extension($add_photo0['tmp_name']) == ".png")) { $form_recipe_file_type_error = true; }
		}
		if (file_exists($add_photo1['tmp_name'])) {
			if (!($f->get_image_extension($add_photo1['tmp_name']) == ".jpg" or $f->get_image_extension($add_photo1['tmp_name']) == ".png")) {  $form_recipe_file_type_error = true; }
		}
		if (file_exists($add_photo2['tmp_name'])) {
			if (!($f->get_image_extension($add_photo2['tmp_name']) == ".jpg" or $f->get_image_extension($add_photo2['tmp_name']) == ".png")) { $form_recipe_file_type_error = true; }
		}
		if (file_exists($add_photo3['tmp_name'])) {
			if (!($f->get_image_extension($add_photo3['tmp_name']) == ".jpg" or $f->get_image_extension($add_photo3['tmp_name']) == ".png")) { $form_recipe_file_type_error = true; }
		}
	}
	// insert when file 1 is ok, name is longer than 1 char - preview file is ok - no specialchars in name or text
	if (@file_exists($add_photo0['tmp_name']) && @file_exists($add_photo1['tmp_name']) && strlen($add_name) > 0 && $form_recipe_name_error == 0 && $form_recipe_text_error == 0 && $form_recipe_file_type_error == false && $_SERVER['CONTENT_LENGTH'] < $bereso['max_upload_size']) {
		
		// generate uniqueid that is used for the imagename
		$add_uniqueid = uniqid();

		$sql->query("INSERT into bereso_recipe (recipe_name, recipe_text,recipe_user, recipe_imagename, recipe_timestamp_creation, recipe_timestamp_edit) VALUES ('".$add_name."','".$add_text."','".$f->get_user_id_by_user_name($user)."','".$add_uniqueid."','".$timestamp."','".$timestamp."')");
		$add_id = $sql->insert_id;
		
		// save tags
		preg_match_all("/(#\w+)/", $add_text, $matches);
		for ($i=0;$i<count($matches[0]);$i++)
		{
			// Debug: echo $matches[0][$i]."<br>";
			$sql->query("INSERT into bereso_tags (tags_name, tags_recipe) VALUES ('".str_replace("#","",$matches[0][$i])."','".$add_id."')");
		}		
			

		// change filename of photo0 and photo1 to add_uniqueid_ID.jpg/png 
		$thumbnail_path = $bereso['recipe_images'] . $add_uniqueid . "_0".$f->get_image_extension($add_photo0['tmp_name']);		
		rename($add_photo1['tmp_name'],$bereso['recipe_images'] . $add_uniqueid . "_1".$f->get_image_extension($add_photo0['tmp_name']));
		//save file 2 and 3
		if (file_exists($add_photo2['tmp_name'])) { move_uploaded_file($add_photo2['tmp_name'], $bereso['recipe_images'] . $add_uniqueid . "_2".$f->get_image_extension($add_photo2['tmp_name'])); }
		if (file_exists($add_photo3['tmp_name'])) { move_uploaded_file($add_photo3['tmp_name'], $bereso['recipe_images'] . $add_uniqueid . "_3".$f->get_image_extension($add_photo3['tmp_name'])); }
		
		// Resize Thumbnail
		$thumbnail_old_size=getimagesize($add_photo0['tmp_name']); //[0] == width; [1] == height; [2] == type; (2 == JPEG; 3 == PNG)
		$thumbnail_new_height=$bereso['recipe_images_thumbnail_height']; 
		$thumbnail_new_width = round($thumbnail_old_size[0] / ($thumbnail_old_size[1] / $thumbnail_new_height),0); // oldsize_width / (oldsize_height / newsize height)
		if ($thumbnail_old_size[2] == 2) { $old_image = imagecreatefromjpeg($add_photo0['tmp_name']); } // JPEG
		elseif ($thumbnail_old_size[2] == 3) { $old_image = imagecreatefrompng ($add_photo0['tmp_name']); } // PNG
		$new_image = ImageCreateTrueColor($thumbnail_new_width,$thumbnail_new_height);
		imagecopyresized($new_image,$old_image,0,0,0,0,$thumbnail_new_width,$thumbnail_new_height,$thumbnail_old_size[0],$thumbnail_old_size[1]);
		if ($thumbnail_old_size[2] == 2) { imagejpeg($new_image,$thumbnail_path,95); } // JPG
		elseif ($thumbnail_old_size[2] == 3) { imagepng($new_image,$thumbnail_path);  }	// PNG
		imagedestroy($new_image);
		imagedestroy($old_image);
		
		
	    $recipe_new_addmessage = "<font color=\"green\">Eintrag <b>\"$add_name\"</b> gespeichert.</font>";
		// clear $add_name and $add_text for the form
		$add_name = null;
		$add_text = null;
		
	} 
	// form not correct
	else
	{
			if ($form_recipe_name_error == 1) { $recipe_new_addmessage = "<font color=\"red\">Eintrag <b>NICHT</b> gespeichert. Name enth&auml;lt nicht erlaubte Zeichen.</font>"; } // name wrong char
			elseif ($form_recipe_text_error == 1) { $recipe_new_addmessage = "<font color=\"red\">Eintrag <b>NICHT</b> gespeichert. Text enth&auml;lt nicht erlaubte Zeichen.</font>"; } // text wrong char
			elseif ($form_recipe_file_type_error == true) { $recipe_new_addmessage = "<font color=\"red\">Eintrag <b>NICHT</b> gespeichert. Falscher Dateityp! Nur JPG und PNG Dateien verwenden!</font>"; } // Wrong filetype
			elseif ($_SERVER['CONTENT_LENGTH'] > $bereso['max_upload_size']) { $recipe_new_addmessage = "<font color=\"red\">Eintrag <b>NICHT</b> gespeichert. Maximale Datei Uploadgr&ouml;sse (". ($bereso['max_upload_size']/1024/1024)." MB - ". $bereso['max_upload_size']." Bytes) &uuml;berschritten!</font>"; } // max_upload_size exceeded
			else { $recipe_new_addmessage = "<font color=\"red\">Eintrag <b>NICHT</b> gespeichert. Name fehlt oder Vorschaubild/Seitenbild(er) Upload fehlerhaft!</font>"; } // name, preview or image1 missing
	}	
	// load new_recipe-form again with message success or failure
	$action = null;
}

// Show form for new recipe
if ($action == null){
	$content = $f->read_file("templates/new_recipe.txt");
	$content = str_replace("(bereso_new_recipe_add_name)",$add_name,$content); // if entry is saved with errors - show name again
	$content = str_replace("(bereso_new_recipe_add_text)",$add_text,$content); // if entry is saved with errors - show text again
	$content = str_replace("(bereso_new_recipe_message)",$recipe_new_addmessage,$content); // insert or clear message field
}
?>