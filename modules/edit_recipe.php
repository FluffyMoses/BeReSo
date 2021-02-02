<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Edit recipe
// included by ../index.php
// ###################################

// check if user is owner of this recipe
if ($f->is_recipe_owned_by_user($user,$recipe)) {
	// Edit recipe
	$recipe_edit_addmessage = null;
	$edit_name_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content
	$edit_text_replace = null; // if set the form will replace the text with the variable content, not with the sql loaded content

	// save new entry and uploaded file
	if ($action == "edit")
	{
		// name is longer than 1 char -  no specialchars in name or text
		if (strlen($edit_name) > 0 && is_numeric($recipe) && $form_recipe_name_error == 0 && $form_recipe_text_error == 0) {
						
			// delete all tags and save the "new" one
			$sql->query("DELETE FROM bereso_tags WHERE tags_recipe='".$recipe."'");
			// save tags
			preg_match_all("/(#\w+)/", $edit_text, $matches);
			for ($i=0;$i<count($matches[0]);$i++)
			{
				$sql->query("INSERT into bereso_tags (tags_name, tags_recipe) VALUES ('".str_replace("#","",$matches[0][$i])."','".$recipe."')");
			}			
			
			// save name and text in recipe
			$sql->query("UPDATE bereso_recipe SET recipe_name='".$edit_name."', recipe_text='".$edit_text."', recipe_timestamp_edit='".$timestamp."' WHERE recipe_id='".$recipe."'");
			
			$recipe_edit_addmessage = "<font color=\"green\">Eintrag <b>\"$edit_name\"</b> gespeichert.</font>";		
		} 
		// form not correct
		else
		{
				if ($form_recipe_name_error == 1) { $recipe_edit_addmessage = "<font color=\"red\">Eintrag <b>NICHT</b> gespeichert. Name enth&auml;lt nicht erlaubte Zeichen.</font>"; } // name wrong char
				elseif ($form_recipe_text_error == 1) { $recipe_edit_addmessage = "<font color=\"red\">Eintrag <b>NICHT</b> gespeichert. Text enth&auml;lt nicht erlaubte Zeichen.</font>"; } // text wrong char
				else { $recipe_edit_addmessage = "<font color=\"red\">Eintrag <b>NICHT</b> gespeichert. Name fehlt!</font>"; } // name missing
				$edit_name_replace = $edit_name; // if set the form will replace the text with the variable content, not with the sql loaded content
				$edit_text_replace = $edit_text; // if set the form will replace the text with the variable content, not with the sql loaded content
		}	
		// load edit-form again with message success or failure
		$action = null;
	}
	
	
	// Delete Image (confirm form)
	if ($action == "delete_image")
	{
		if ($result = $sql->query("SELECT recipe_imagename from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($user)."' AND recipe_id='".$recipe."'"))
		{	
			$row = $result -> fetch_assoc();			
			$content = $f->read_file("templates/edit_recipe-image-delete.txt");
			$content = str_replace("(bereso_edit_recipe_imagename)",$row['recipe_imagename'],$content);
			$content = str_replace("(bereso_edit_recipe_image_id)",$recipe_image_id,$content);
			$content = str_replace("(bereso_edit_recipe_id)",$recipe,$content);
			$content = str_replace("(bereso_edit_recipe_image_extension)",$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id),$content);
			
			
			// add to navigation
			$navigation .= $f->read_file("templates/edit_recipe-navigation-delete.txt");	
			$navigation = str_replace("(bereso_edit_recipe_id)",$recipe,$navigation);			
		}
	}
	
	
	// Delete Image Confirmed 
	if ($action == "confirm_delete_image")
	{
		if ($result = $sql->query("SELECT recipe_imagename from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($user)."' AND recipe_id='".$recipe."'"))
		{	
			$row = $result -> fetch_assoc();			
			if ($recipe_image_id == 2 or $recipe_image_id == 3) 
			{
				// change timestamp_edit
				$sql->query("UPDATE bereso_recipe SET recipe_timestamp_edit='".$timestamp."' WHERE recipe_id='".$recipe."'");
				
				@unlink ($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id.$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id)); // delete file 			
				$action = null; // Load Edit Form again	
			}
		}
	}	
	
	// Upload Image
	if ($action == "upload_image")
	{
		if ($result = $sql->query("SELECT recipe_imagename from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($user)."' AND recipe_id='".$recipe."'"))
		{	
			$row = $result -> fetch_assoc();		
			

			// Thumbnail upload - needs resizing!
			if ($recipe_image_id == 0 && file_exists($edit_photo0['tmp_name']))
			{
				// Check Fileextensions				
				if (!($f->get_image_extension($edit_photo0['tmp_name']) == ".jpg" or $f->get_image_extension($edit_photo0['tmp_name']) == ".png")) 
				{
					$recipe_edit_addmessage = "<font color=\"red\">Bild <b>NICHT</b> gespeichert. Falscher Dateityp! Nur JPG und PNG Dateien verwenden!</font>"; // fileextension
				}
				else
				{					
					// delete "old" image file 
					@unlink ($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id.".jpg"); 
					@unlink ($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id.".png"); 

					// Resize Thumbnail
					$thumbnail_path = $bereso['recipe_images'] . $row['recipe_imagename'] . "_0".$f->get_image_extension($edit_photo0['tmp_name']);
					$thumbnail_old_size=getimagesize($edit_photo0['tmp_name']); //[0] == width; [1] == height; [2] == type; (2 == JPEG; 3 == PNG)
					$thumbnail_new_height=$bereso['recipe_images_thumbnail_height']; 
					$thumbnail_new_width = round($thumbnail_old_size[0] / ($thumbnail_old_size[1] / $thumbnail_new_height),0); // oldsize_width / (oldsize_height / newsize height)
					if ($thumbnail_old_size[2] == 2) { $old_image = imagecreatefromjpeg($edit_photo0['tmp_name']); } // JPEG
					elseif ($thumbnail_old_size[2] == 3) { $old_image = imagecreatefrompng ($edit_photo0['tmp_name']); } // PNG
					$new_image = ImageCreateTrueColor($thumbnail_new_width,$thumbnail_new_height);
					imagecopyresized($new_image,$old_image,0,0,0,0,$thumbnail_new_width,$thumbnail_new_height,$thumbnail_old_size[0],$thumbnail_old_size[1]);
					if ($thumbnail_old_size[2] == 2) { imagejpeg($new_image,$thumbnail_path,95); } // JPG
					elseif ($thumbnail_old_size[2] == 3) { imagepng($new_image,$thumbnail_path);  }	// PNG
					imagedestroy($new_image);
					imagedestroy($old_image);		

					// change timestamp_edit
					$sql->query("UPDATE bereso_recipe SET recipe_timestamp_edit='".$timestamp."' WHERE recipe_id='".$recipe."'");					
					
					$recipe_edit_addmessage = "<font color=\"green\">Bild gespeichert.</font>";	
				}
			}
			else // other recipe images upload
			{
				if ($recipe_image_id == 1 && file_exists($edit_photo1['tmp_name']))
				{
					// Check Fileextensions
					if (!($f->get_image_extension($edit_photo1['tmp_name']) == ".jpg" or $f->get_image_extension($edit_photo1['tmp_name']) == ".png"))
					{
						$recipe_edit_addmessage = "<font color=\"red\">Bild <b>NICHT</b> gespeichert. Falscher Dateityp! Nur JPG und PNG Dateien verwenden!</font>";  // fileextension					
					}
					else 
					{
						// delete "old" image file 
						@unlink ($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id.".jpg"); 
						@unlink ($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id.".png"); 
						// Copy File
						move_uploaded_file($edit_photo1['tmp_name'], $bereso['recipe_images'] . $row['recipe_imagename'] . "_1".$f->get_image_extension($edit_photo1['tmp_name']));		
						
						// change timestamp_edit
						$sql->query("UPDATE bereso_recipe SET recipe_timestamp_edit='".$timestamp."' WHERE recipe_id='".$recipe."'");			
						
						$recipe_edit_addmessage = "<font color=\"green\">Bild gespeichert.</font>";							
					}
				}
				elseif ($recipe_image_id == 2 && file_exists($edit_photo2['tmp_name']))
				{
					// Check Fileextensions
					if (!($f->get_image_extension($edit_photo2['tmp_name']) == ".jpg" or $f->get_image_extension($edit_photo2['tmp_name']) == ".png")) 
					{
						$recipe_edit_addmessage = "<font color=\"red\">Bild <b>NICHT</b> gespeichert. Falscher Dateityp! Nur JPG und PNG Dateien verwenden!</font>";  // fileextension					
					}
					else
					{
						// delete "old" image file 
						@unlink ($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id.".jpg"); 
						@unlink ($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id.".png"); 						
						// Copy File						
						move_uploaded_file($edit_photo2['tmp_name'], $bereso['recipe_images'] . $row['recipe_imagename'] . "_2".$f->get_image_extension($edit_photo2['tmp_name']));			
												
						// change timestamp_edit
						$sql->query("UPDATE bereso_recipe SET recipe_timestamp_edit='".$timestamp."' WHERE recipe_id='".$recipe."'");		
						
						$recipe_edit_addmessage = "<font color=\"green\">Bild gespeichert.</font>";	
					}
				}
				elseif ($recipe_image_id == 3 && file_exists($edit_photo3['tmp_name']))
				{
					// Check Fileextensions
					if (!($f->get_image_extension($edit_photo3['tmp_name']) == ".jpg" or $f->get_image_extension($edit_photo3['tmp_name']) == ".png"))
					{
						$recipe_edit_addmessage = "<font color=\"red\">Bild <b>NICHT</b> gespeichert. Falscher Dateityp! Nur JPG und PNG Dateien verwenden!</font>";  // fileextension					
					}
					else
					{
						// delete "old" image file 
						@unlink ($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id.".jpg"); 
						@unlink ($bereso['recipe_images'].$row['recipe_imagename']."_".$recipe_image_id.".png"); 							
						// Copy File						
						move_uploaded_file($edit_photo3['tmp_name'], $bereso['recipe_images'] . $row['recipe_imagename'] . "_3".$f->get_image_extension($edit_photo3['tmp_name']));	
						
						// change timestamp_edit
						$sql->query("UPDATE bereso_recipe SET recipe_timestamp_edit='".$timestamp."' WHERE recipe_id='".$recipe."'");		
						
						$recipe_edit_addmessage = "<font color=\"green\">Bild gespeichert.</font>";						
						}
				}										
			}
			
						
			$action = null; // Load Edit Form again	
		}
	}		
	
	// sharing on/off
	if ($action == "share")
	{
		$recipe_sharing = $f->get_recipe_share_id($recipe);
		
		// Recipe shared? 
		if (strlen($recipe_sharing) > 0) 
		{
			// recipe shared - disable share - delete link
			$sql->query("UPDATE bereso_recipe SET recipe_shareid='' WHERE recipe_id='".$recipe."'");		
		}
		else // recipe not shared - enable share - create link
		{
			$sql->query("UPDATE bereso_recipe SET recipe_shareid='".uniqid()."' WHERE recipe_id='".$recipe."'");		
		}		
		// redirect back to show_recipe.php
		header('Location: ?module=show_recipe&recipe='.$recipe,true, 301 ); 
		exit();		
	}
	

	// Show form for new recipe
	if ($action == null){
		if ($result = $sql->query("SELECT recipe_name, recipe_text, recipe_imagename from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($user)."' AND recipe_id='".$recipe."'"))
		{	
			$row = $result -> fetch_assoc();
			
			$content = $f->read_file("templates/edit_recipe.txt");	
			if (strlen($edit_name_replace) > 0) { $content = str_replace("(bereso_edit_recipe_name)",$edit_name_replace,$content); } else { $content = str_replace("(bereso_edit_recipe_name)",$row['recipe_name'],$content); } // if set the form will replace the text with the variable content, not with the sql loaded content
			if (strlen($edit_text_replace) > 0) { $content = str_replace("(bereso_edit_recipe_text)",$edit_text_replace,$content); } else { $content = str_replace("(bereso_edit_recipe_text)",$row['recipe_text'],$content); } // if set the form will replace the text with the variable content, not with the sql loaded content
			$content = str_replace("(bereso_edit_recipe_message)",$recipe_edit_addmessage,$content); // insert or clear message field
			
			// Images
			// 0
			$content = str_replace("(bereso_edit_recipe_image_0)",$f->read_file("templates/edit_recipe-image.txt"),$content);
			$content = str_replace("(bereso_edit_recipe_image_image)",$f->read_file("templates/edit_recipe-image-image.txt"),$content);			
			$content = str_replace("(bereso_edit_recipe_imagename)",$row['recipe_imagename'],$content);
			$content = str_replace("(bereso_edit_recipe_image_id)","0",$content);
			$content = str_replace("(bereso_edit_recipe_image_extension)",$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_"."0"),$content);
			$content = str_replace("(bereso_edit_recipe_image_description)","Vorschaubild",$content);
			$content = str_replace("(bereso_edit_recipe_image_delete)",null,$content);
			// 1
			$content = str_replace("(bereso_edit_recipe_image_1)",$f->read_file("templates/edit_recipe-image.txt"),$content);
			$content = str_replace("(bereso_edit_recipe_image_image)",$f->read_file("templates/edit_recipe-image-image.txt"),$content);			
			$content = str_replace("(bereso_edit_recipe_imagename)",$row['recipe_imagename'],$content);
			$content = str_replace("(bereso_edit_recipe_image_id)","1",$content);
			$content = str_replace("(bereso_edit_recipe_image_extension)",$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_"."1"),$content);
			$content = str_replace("(bereso_edit_recipe_image_description)","Rezeptseite 1",$content);
			$content = str_replace("(bereso_edit_recipe_image_delete)",null,$content);
			// 2
			$content = str_replace("(bereso_edit_recipe_image_2)",$f->read_file("templates/edit_recipe-image.txt"),$content);
			if (file_exists($bereso['recipe_images'].$row['recipe_imagename']."_"."2".$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_"."2")))
			{
				$content = str_replace("(bereso_edit_recipe_image_image)",$f->read_file("templates/edit_recipe-image-image.txt"),$content);
				$content = str_replace("(bereso_edit_recipe_image_delete)",$f->read_file("templates/edit_recipe-image-form-delete.txt"),$content);	
			}
			else 
			{
				$content = str_replace("(bereso_edit_recipe_image_image)",null,$content);
				$content = str_replace("(bereso_edit_recipe_image_delete)",null,$content);	
			}			
			$content = str_replace("(bereso_edit_recipe_imagename)",$row['recipe_imagename'],$content);
			$content = str_replace("(bereso_edit_recipe_image_id)","2",$content);
			$content = str_replace("(bereso_edit_recipe_image_extension)",$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_"."2"),$content);
			$content = str_replace("(bereso_edit_recipe_image_description)","Rezeptseite 2",$content);			
			// 3
			$content = str_replace("(bereso_edit_recipe_image_3)",$f->read_file("templates/edit_recipe-image.txt"),$content);
			if (file_exists($bereso['recipe_images'].$row['recipe_imagename']."_"."3".$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_"."3")))
			{		
				$content = str_replace("(bereso_edit_recipe_image_image)",$f->read_file("templates/edit_recipe-image-image.txt"),$content);
				$content = str_replace("(bereso_edit_recipe_image_delete)",$f->read_file("templates/edit_recipe-image-form-delete.txt"),$content);			
			}
			else 
			{
				$content = str_replace("(bereso_edit_recipe_image_image)",null,$content);
				$content = str_replace("(bereso_edit_recipe_image_delete)",null,$content);			
			}				
			$content = str_replace("(bereso_edit_recipe_imagename)",$row['recipe_imagename'],$content);
			$content = str_replace("(bereso_edit_recipe_image_id)","3",$content);
			$content = str_replace("(bereso_edit_recipe_image_extension)",$f->search_image_extension($bereso['recipe_images'].$row['recipe_imagename']."_"."3"),$content);
			$content = str_replace("(bereso_edit_recipe_image_description)","Rezeptseite 3",$content);			

		
			
			$content = str_replace("(bereso_edit_recipe_id)",$recipe,$content);
			
			// add to navigation
			$navigation .= $f->read_file("templates/edit_recipe-navigation.txt");	
			$navigation = str_replace("(bereso_edit_recipe_id)",$recipe,$navigation);		
		}
	}
}
else
{
	die ("CHECK: edit owner failed");
}