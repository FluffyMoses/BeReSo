<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class Functions
// included by ../index.php
// ###################################

class functions 
{
	// read file and return its content
	function read_file($fr_path)
	{
		$file = file($fr_path);
		$file_content = null;

		foreach($file AS $file_line)
		{
			   $file_content .= $file_line;
		}
		return $file_content;   
	}

	// converts timestamp into human readable date and time
	function timestamp_to_datetime($ttd_timestamp) 
	{
		global $bereso;
		return date($bereso['datetimestring'],$ttd_timestamp); 
	}	
	
	// check if logged in
	function is_logged_in($li_user,$li_passwordhash)
	{
		global $sql;		
        if ($result = $sql->query("SELECT user_name, user_pwhash from bereso_user WHERE user_name='$li_user'"))
		{
			$row = $result -> fetch_assoc();
			
			// check if user exists and password matches hashed password
			if (!empty($row) && $row['user_pwhash'] == $li_passwordhash) // not empty and pw hash matches
			{
				return true;
				
			}
			else 
			{
				return false;
			}
		}                		
	}
	
	// Hash password  and return value
	function generate_password_hash($gph_password)
	{
		return password_hash($gph_password,PASSWORD_DEFAULT);
	}
	
	// check if recipe is owned by user
	function is_recipe_owned_by_user($irobu_user,$irobu_recipe_id)
	{
		global $sql,$f;
		$userid = $f->get_user_id_by_user_name($irobu_user); 
        if ($result = $sql->query("SELECT recipe_name from bereso_recipe WHERE recipe_user='$userid' and recipe_id='$irobu_recipe_id'"))
		{
			$row = $result -> fetch_assoc();
			
			// check if recipe is owned by user
			if (!empty($row))
			{
				return true;
				
			}
			else 
			{
				return false;
			}
		}                		
	}	
	
	// get recipe share id
	function get_recipe_share_id($grsi_recipe_id)
	{
		global $sql;		
        if ($result = $sql->query("SELECT recipe_shareid from bereso_recipe WHERE recipe_id='$grsi_recipe_id'"))
		{
			$row = $result -> fetch_assoc();
			return $row['recipe_shareid']; // return recipe_sharing (empty or not)
		}                		
	}		
	
	// check if string contains just letters nothing else!
	function is_letter($il_string,$il_pattern)
	{				
		if ($il_pattern == "a-z") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ"; } // default a-z
		elseif ($il_pattern == "a-z_") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789_"; } //  a-z plus _
		elseif ($il_pattern == "a-z0-9") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789"; } //  a-z 0-9
		elseif ($il_pattern == "a-z0-9 ") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789 "; } //  a-z 0-9 SPACE
		elseif ($il_pattern == "a-z0-9 SPECIAL") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789 \r\n!?-#:./,_°%()"; } //  a-z 0-9 SPECIALCHARS		
		elseif ($il_pattern == "a-z0-9 SPECIALPASSWORDHASH") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789 \r\n!?-#:./,_°%()$"; } //  a-z 0-9 SPECIALCHARS		
		else { die ("CHECK: \$il_pattern failed".$il_pattern); }
		
		for ($i=0;$i<strlen($il_string);$i++)
		{
			#old: if (!in_array($check_string[$i],$letters)) { return false;} // found wrong char - not working with ö,ä,ü etc (more than 1 "char") => multibyte safe substr mb_substr!
			
			$found_char = false; // start false and set to true if found
			for ($y=0;$y<strlen($letters);$y++)
			{
				if (mb_substr($il_string,$i,1) == mb_substr($letters,$y,1)) { $found_char = true; }// char found in letters!
			}
			
			// if this char was not found in letters - return false - wrong char!
			if ($found_char == false) { return false; }
		}
		return true; // no wrong char found
	}
		
	
	// get user_id by user_name
	function get_user_id_by_user_name($guibun_user)
	{
		global $sql;
        if ($result = $sql->query("SELECT user_id from bereso_user WHERE user_name='$guibun_user'"))
		{
			$row = $result -> fetch_assoc();
			
			// check if user exists and return id
			if (!empty($row))
			{
				return $row['user_id'];
			}
			else 
			{
				return "0";
			}
		}                		
	}	
	
	
	// get user_name by user_id
	function get_user_name_by_user_id($gunbui_user)
	{
		global $sql;
        if ($result = $sql->query("SELECT user_name from bereso_user WHERE user_id='$gunbui_user'"))
		{
			$row = $result -> fetch_assoc();
			
			// check if user exists and return name
			if (!empty($row))
			{
				return $row['user_name'];
			}
			else 
			{
				return "0";
			}
		}                		
	}		
	
	// get name of recipe by id
	function get_recipename($gr_id) 
	{
		global $sql;
        if ($result = $sql->query("SELECT recipe_name from bereso_recipe WHERE recipe_id='".$gr_id."'"))
		$row = $result -> fetch_assoc();
		return $row['recipe_name'];
	}			
		
	
	// get number of recipes of user
	function get_recipenumber($gr_user) 
	{
		global $sql,$f;
        if ($result = $sql->query("SELECT * from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($gr_user)."'"))
		return mysqli_num_rows($result);
	}		
	
	// get number of shared recipes of user
	function get_sharedrecipenumber($gr_user) 
	{
		global $sql,$f;
        if ($result = $sql->query("SELECT * from bereso_recipe WHERE recipe_user='".$f->get_user_id_by_user_name($gr_user)."' AND LENGTH(recipe_shareid) > 0"))
		return mysqli_num_rows($result);
	}		
	

	// get number of recipes of user tag
	function get_recipenumber_by_tag_id($grbti_tag_name,$grbti_user) 
	{
		global $sql,$f;
        if ($result = $sql->query("SELECT * from bereso_tags  INNER JOIN bereso_recipe ON bereso_tags.tags_recipe = bereso_recipe.recipe_id WHERE bereso_tags.tags_name='$grbti_tag_name' AND bereso_recipe.recipe_user='".$f->get_user_id_by_user_name($grbti_user)."'"))
		return mysqli_num_rows($result);
	}		
	
	// Return file extension based on MIME Type of the file (Images)
	function get_image_extension($gie_path)
	{
		$filetype = getimagesize($gie_path);
		if (@$filetype['mime'] == "image/jpeg") { $extension = ".jpg"; }
		elseif (@$filetype['mime'] == "image/png") { $extension = ".png"; }
		else { $extension = ".unknown"; } // not a wanted file format => error
		// return extension
		return $extension;
	}
	
	// Test which of the known file extensions exists for given path + filename
	function search_image_extension($sie_path)
	{
		if (file_exists($sie_path.".jpg")) { return ".jpg"; }
		elseif (file_exists($sie_path.".png")) { return ".png"; }
		else { return ".unknown"; } // not a wanted file format => error
	}	
	
	// Highlight Text - newline, hashtaglinks, http(s) links, etc
	function highlight_text($ht_text)
	{
		// # link with tag recipe list - known problems with öäüß_ in #
		preg_match_all("/(#\w+)/", $ht_text, $matches);
		for ($i=0;$i<count($matches[0]);$i++)
		{
			$ht_text = str_replace($matches[0][$i],"<a class=\"highlitetag\" href=\"?user=(bereso_user)&module=list_recipes&tag=".str_replace("#","",$matches[0][$i])."\">".$matches[0][$i]."</a>",$ht_text);
		}	
		$ht_text = str_replace("\n","<br>",$ht_text); // new line	
		$ht_text = preg_replace('|([\w\d]*)\s?(https?://([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i', '<a class="none" target="_BLANK" href="$2">$2</a>', $ht_text); // https http insert real link
		return $ht_text;
	}
	
	// Highlight Text - newline, http(s) links, etc
	function highlight_text_share($ht_text)
	{
		// # highlight # - known problems with öäüß_ in #
		preg_match_all("/(#\w+)/", $ht_text, $matches);
		for ($i=0;$i<count($matches[0]);$i++)
		{
			$ht_text = str_replace($matches[0][$i],"<b><font color=\"#ff0000\">".$matches[0][$i]."</font></b>",$ht_text);
		}			
		$ht_text = str_replace("\n","<br>",$ht_text); // new line	
		$ht_text = preg_replace('|([\w\d]*)\s?(https?://([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i', '<a class="none" target="_BLANK" href="$2">$2</a>', $ht_text); // https http insert real link
		return $ht_text;
	}	
}
?>