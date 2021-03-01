<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class text functions
// included by ../index.php
// ###################################

// IMPORTANT: THIS FILE MIST BE SAVED IN UTF-8 cause of the special characters! ÖÄÜß etc.

class Text 
{

	// check if string contains just letters nothing else!
	public static function is_letter($il_string,$il_pattern)
	{		
		if ($il_pattern == "a-z") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ"; } // default a-z
		elseif ($il_pattern == "a-z_") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789_"; } //  a-z plus _
		elseif ($il_pattern == "a-z-") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789-"; } //  a-z plus -
		elseif ($il_pattern == "a-z0-9") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789"; } //  a-z 0-9
		elseif ($il_pattern == "a-z0-9 ") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789 "; } //  a-z 0-9 SPACE
		elseif ($il_pattern == "a-z0-9 SPECIAL") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789 \r\n!?-#:./,_°%()[]"; } //  a-z 0-9 SPECIALCHARS		
		elseif ($il_pattern == "a-z0-9 SPECIALPASSWORDHASH") { $letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 \r\n!?-#:./,_°%()[]$"; } //  a-z 0-9 SPECIALCHARS		
		else { Log::die ("CHECK: \$il_pattern failed  ".'"'.$il_pattern.'"'); }
		
		for ($i=0;$i<strlen($il_string);$i++)
		{
			#old: if (!in_array($check_string[$i],$letters)) { return false;} // found wrong char - not working with ö,ä,ü etc (more than 1 "char") => multibyte safe substr mb_substr!
			$found_char = false; // start false and set to true if found
			for ($y=0;$y<strlen($letters);$y++)
			{
				if (mb_substr($il_string,$i,1) == mb_substr($letters,$y,1)) { $found_char = true; } // char found in letters!
			}
			
			// if this char was not found in letters - return false - wrong char!
			if ($found_char == false) { return false; }
		}
		return true; // no wrong char found
	}
		
	// Highlight Text - newline, hashtaglinks, http(s) links, etc
	public static function highlight_text($ht_text)
	{
		// # link with tag list - known problems with öäüß_ in #
		preg_match_all("/(#\w+)/", $ht_text, $matches);
		for ($i=0;$i<count($matches[0]);$i++)
		{
			$ht_text = str_replace($matches[0][$i],"<a class=\"highlitetag\" href=\"?user=(bereso_user)&module=list&tag=".str_replace("#","",$matches[0][$i])."\">".$matches[0][$i]."</a>",$ht_text);
		}	
		$ht_text = str_replace("\n","<br>",$ht_text); // new line	
		$ht_text = preg_replace('|([\w\d]*)\s?(https?://([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i', '<a class="none" target="_BLANK" href="$2">$2</a>', $ht_text); // https http insert real link
		return $ht_text;
	}
	
	// Highlight Text Share - newline, http(s) links, etc
	public static function highlight_text_share($ht_text)
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