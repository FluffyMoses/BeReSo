<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class image functions
// included by ../index.php
// ###################################

class Image 
{

	// Return file extension based on MIME Type of the file (Images)
	public static function get_extension($ge_path)
	{
		$filetype = getimagesize($ge_path);
		if (@$filetype['mime'] == "image/jpeg") { $extension = ".jpg"; }
		elseif (@$filetype['mime'] == "image/png") { $extension = ".png"; }
		else { $extension = ".unknown"; } // not a wanted file format => error
		// return extension
		return $extension;
	}
	
	// Test which of the known file extensions exists for given path + filename
	public static function search_extension($se_path)
	{
		if (file_exists($se_path.".jpg")) { return ".jpg"; }
		elseif (file_exists($se_path.".png")) { return ".png"; }
		else { return ".unknown"; } // not a wanted file format => error
	}	
	

}
?>