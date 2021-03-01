<?php
// List alle files in $directory and compares it extension to the header information

// config
$directory = "images/";

// function
function get_extension($ge_path)
{
	$filetype = getimagesize($ge_path);
	if (@$filetype['mime'] == "image/jpeg") { $extension = "jpg"; }
	elseif (@$filetype['mime'] == "image/png") { $extension = "png"; }
	else { $extension = ".unknown"; } // not a wanted file format => error
	// return extension
	return $extension;
}

$files = scandir($directory);
$count = 0;




foreach ($files as $key => $value) {
	if ($value != "." or $value != ".."){

		// get extension
		$split_value = explode(".",$value);

		if ($split_value[1] == "jpg") // jpg
		{
			if ( get_extension($directory.$value) == "jpg") { $color = "green"; } else { $color = "red"; }
			echo "<font color=\"$color\">Header: ". get_extension($directory.$value)." - Fileextension: jpg - Filename: ".$value."</font><br>";
			$count++;
		}
		elseif ($split_value[1] == "png") // png
		{
			if ( get_extension($directory.$value) == "png") { $color = "green"; } else { $color = "red"; }
			echo "<font color=\"$color\">Header: ". get_extension($directory.$value)." - Fileextension: png - Filename: ".$value."</font><br>";
			$count++;
		}

	}
}
echo "<br><br>Files: $count";
?>