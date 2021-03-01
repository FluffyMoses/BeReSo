<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class log functions
// included by ../index.php
// ###################################

class Log {

	// die logging - log if $bereso['log_die'] == true and die() 
	public static function die($d_logtext)
	{
		global $bereso;
		$full_logtext = Time::timestamp_to_datetime($bereso['now']) . " [".$bereso['title']." ".$bereso['version'] . "] - " . $_SERVER['REMOTE_ADDR'] . " - " . $_SERVER['REQUEST_URI'] . " - " . $d_logtext;
		if ($bereso['log_die'] == true) { File::append_file($bereso['log_die_path'],$full_logtext); } // log to textfile if enabled
		die($full_logtext); // end script and output $d_logtext
	}

}
?>