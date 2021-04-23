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
	// remove all letters and chars except the allowed
	public static function convert_letter($cl_string,$cl_pattern)
	{
		// Problems with some characters => replace them with temp chars - run the conversion and replace them back
		// => temp chars
		$cl_string = str_replace("Ü","BERESOU",$cl_string);
		$cl_string = str_replace("ü","BERESOu",$cl_string);
		$cl_string = str_replace("Ä","BERESOA",$cl_string);
		$cl_string = str_replace("ä","BERESOa",$cl_string);
		$cl_string = str_replace("Ö","BERESOO",$cl_string);
		$cl_string = str_replace("ö","BERESOo",$cl_string);
		$cl_string = str_replace("ß","BERESOss",$cl_string);

		if ($cl_pattern == "a-z0-9 SPECIAL") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789 \r\n!?-#:./,_°%()[];\$§=&*+|\""; } //  a-z 0-9 SPECIALCHARS
		else { Log::die ("CHECK: \$cl_pattern failed  ".'"'.$cl_pattern.'"',false); }

		$converted_string = null;

		for ($i=0;$i<strlen($cl_string);$i++)
		{			
			for ($y=0;$y<strlen($letters);$y++)
			{
				if (mb_substr($cl_string,$i,1) == mb_substr($letters,$y,1)) { $converted_string .= mb_substr($letters,$y,1); } // char found in letters!
			}			
		}

		// Problems with some characters => replace them with temp chars - run the conversion and replace them back
		// => real chars
		$converted_string = str_replace("BERESOU","Ü",$converted_string);
		$converted_string = str_replace("BERESOu","ü",$converted_string);
		$converted_string = str_replace("BERESOA","Ä",$converted_string);
		$converted_string = str_replace("BERESOa","ä",$converted_string);
		$converted_string = str_replace("BERESOO","Ö",$converted_string);
		$converted_string = str_replace("BERESOo","ö",$converted_string);
		$converted_string = str_replace("BERESOss","ß",$converted_string);

		// retunr the converted string - it contains only allowed characters
		return $converted_string;
	}

	// check if string contains just letters nothing else!
	public static function is_letter($il_string,$il_pattern)
	{		
		if ($il_pattern == "a-z") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ"; } // default a-z
		elseif ($il_pattern == "a-z_") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789_"; } //  a-z plus _
		elseif ($il_pattern == "a-z-") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789-"; } //  a-z plus -
		elseif ($il_pattern == "a-z0-9") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789"; } //  a-z 0-9
		elseif ($il_pattern == "a-z0-9 ") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789 "; } //  a-z 0-9 SPACE
		elseif ($il_pattern == "a-z0-9 SPECIAL") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789 \r\n!?-#:./,_°%()[];\$§=&*+|\""; } //  a-z 0-9 SPECIALCHARS		
		elseif ($il_pattern == "a-z0-9 SPECIALPASSWORDHASH") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789 \r\n!?-#:./,_°%()[];\$§=&*+|\""; } //  a-z 0-9 SPECIALPASSWORDHASH		
		elseif ($il_pattern == "a-z0-9 SPECIALHTML") { $letters = "abcdefghijklmnopqrstuvwxyzöäüßABCDEFGHIJKLMNOPQRSTUVWXYZÖÄÜ0123456789 \r\n!?-#:./,_°%()[];\$§=&*+|\"<>"; } //  a-z 0-9 SPECIALHTML		
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
		
	// Highlight text - newline, hashtaglinks, http(s) links, etc - for show.php
	public static function highlight_text_show($hts_text, $item)
	{
		global $bereso;
		// add one whitespace character at the end for the regular expression to match when the last word is a hashtag!
		$hts_text = $hts_text . " ";
		// # link with tag list - known problems with öäüß_ in #

		$hts_text = preg_replace('|([\w\d]*)\s?(https?://([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i', '<a class="none" target="_BLANK" href="$2">$2</a>', $hts_text); // https http insert real link

		preg_match_all("/(#\w+)\s/", $hts_text, $matches);
		for ($i=0;$i<count($matches[0]);$i++)
		{
			$matches[0][$i] = Text::remove_whitespace($matches[0][$i]); // remove whitespace
			$hts_text = preg_replace('/('.$matches[0][$i].')/',"<a class=\"highlitetag\" href=\"?user=(bereso_user)&module=list&tag=".str_replace("#","",$matches[0][$i])."\">".$matches[0][$i]."</a>", $hts_text);
		}
		// checkbox replace - needs to be in one line or else it will fill <br> cause of the \n endings
		$hts_text = preg_replace('/\[c\]([a-zA-Z0-9 äÄüÜöÖß]*?)\[\/c\]/', '<label class="container">$1<input type="checkbox" class="button" value="" onclick="get_http_request(\''.$bereso['url'].'index.php?module=edit&action=check&replace_text=$1&item='.$item.'\')"><span class="checkmark"></span></label>', $hts_text); // checkbox unchecked - no /i (case sensitive ignored) - no /s (new line characters allowed) - before all replaces so that the $replace_text could be found in the database
		$hts_text = preg_replace('/\[C\]([a-zA-Z0-9 äÄüÜöÖß]*?)\[\/C\]/', '<label class="container">$1<input type="checkbox" class="button" value="" onclick="get_http_request(\''.$bereso['url'].'index.php?module=edit&action=check&replace_text=$1&item='.$item.'\')" checked> <span class="checkmark"></span></label>', $hts_text); // checkbox checked - no /s (new line characters allowed) - before all replaces so that the $replace_text could be found in the database

		$hts_text = str_replace("\n","<br>",$hts_text); // new line		

		$hts_text = preg_replace('/\[b\](.*?)\[\/b\]/is', '<b>$1</b>', $hts_text); // bold
		$hts_text = preg_replace('/\[i\](.*?)\[\/i\]/is', '<i>$1</i>', $hts_text); // italic
		$hts_text = preg_replace('/\[u\](.*?)\[\/u\]/is', '<u>$1</u>', $hts_text); // underlined		
		
		return $hts_text;
	}
	
	// Highlight text share - newline, http(s) links, etc - for share and login motd
	public static function highlight_text_share($ht_text)
	{
		// add one whitespace character at the end for the regular expression to match when the last word is a hashtag!
		$ht_text = $ht_text . " ";
		// # highlight # - known problems with öäüß_ in #
		preg_match_all("/(#\w+)\s/", $ht_text, $matches);
		for ($i=0;$i<count($matches[0]);$i++)
		{
			$matches[0][$i] = Text::remove_whitespace($matches[0][$i]); // remove whitespace
			$ht_text = preg_replace('/('.$matches[0][$i].')/',"<b><font class=\"highlitetag\">".$matches[0][$i]."</font></b> ", $ht_text);
		}			
		$ht_text = str_replace("\n","<br>",$ht_text); // new line	
		$ht_text = preg_replace('/\[b\](.*?)\[\/b\]/is', '<b>$1</b>', $ht_text); // bold
		$ht_text = preg_replace('/\[i\](.*?)\[\/i\]/is', '<i>$1</i>', $ht_text); // italic
		$ht_text = preg_replace('/\[u\](.*?)\[\/u\]/is', '<u>$1</u>', $ht_text); // underlined
		$ht_text = preg_replace('/\[c\](.*?)\[\/c\]/', '<label class="container">$1<input type="checkbox" class="button" value=""> <span class="checkmark"></span></label>', $ht_text); // checkbox
		$ht_text = preg_replace('/\[C\](.*?)\[\/C\]/', '<label class="container">$1<input type="checkbox" class="button" value="" checked> <span class="checkmark"></span></label>', $ht_text); // checkbox
		$ht_text = preg_replace('|([\w\d]*)\s?(https?://([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i', '<a class="none" target="_BLANK" href="$2">$2</a>', $ht_text); // https http insert real link		
		return $ht_text;
	}	

	// Highlight text printpreview - newline, http(s) links, etc - for print preview
	public static function highlight_text_printpreview($ht_text)
	{
		// add one whitespace character at the end for the regular expression to match when the last word is a hashtag!
		$ht_text = $ht_text . " ";
		// # highlight # - known problems with öäüß_ in #
		preg_match_all("/(#\w+)\s/", $ht_text, $matches);
		for ($i=0;$i<count($matches[0]);$i++)
		{
			$matches[0][$i] = Text::remove_whitespace($matches[0][$i]); // remove whitespace
			$ht_text = preg_replace('/('.$matches[0][$i].')/',"<b><font class=\"highlitetag\">".$matches[0][$i]."</font></b>", $ht_text);
		}			
		$ht_text = str_replace("\n","<br>",$ht_text); // new line	
		$ht_text = preg_replace('/\[b\](.*?)\[\/b\]/is', '<b>$1</b>', $ht_text); // bold
		$ht_text = preg_replace('/\[i\](.*?)\[\/i\]/is', '<i>$1</i>', $ht_text); // italic
		$ht_text = preg_replace('/\[u\](.*?)\[\/u\]/is', '<u>$1</u>', $ht_text); // underlined
		$ht_text = preg_replace('/\[c\](.*?)\[\/c\]/', '<label class="container">$1<input type="checkbox" class="button" value=""> <span class="checkmark"></span></label>', $ht_text); // checkbox
		$ht_text = preg_replace('/\[C\](.*?)\[\/C\]/', '<label class="container">$1<input type="checkbox" class="button" value="" checked> <span class="checkmark"></span></label>', $ht_text); // checkbox
		$ht_text = preg_replace('|([\w\d]*)\s?(https?://([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i', '<font color="blue"><u>$2</u></font>', $ht_text); // https http insert real link		
		return $ht_text;
	}


	// remove all whitespaces from string
	public static function remove_whitespace($rw_string)
	{
		$rw_string = str_replace("\r",null,$rw_string);
		$rw_string = str_replace("\n",null,$rw_string);
		$rw_string = str_replace("\t",null,$rw_string);
		$rw_string = str_replace(" ",null,$rw_string);
		return $rw_string;
	}


	// usort callback function to sort an array of strings per lenght (returns < 0 || == 0 || > 0)
	public static function sort_strings_lenght($ssl_firststring, $ssl_secondstring)
	{
		return strlen($ssl_secondstring) - strlen($ssl_firststring);		
	}

}
?>