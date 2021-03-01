<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class time functions
// included by ../index.php
// ###################################

class Time {

	// converts timestamp into human readable date and time
	public static function timestamp_to_datetime($ttd_timestamp) 
	{
		global $bereso;
		return date($bereso['datetimestring'],$ttd_timestamp); 
	}	

}
?>