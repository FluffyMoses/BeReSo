<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Configuration
// ###################################

// php.ini - important php modules:
// extension=gd // image conversion
// extension=mysqli // mysql and mariadb connections
// extension=mbstring // mb_substr => multibyte safe substring (for example ü,ö,ä are two chars in regular substr or $string[ID] char

// PHP Error Level
// Simple Errors Fehler: E_ERROR | E_WARNING | E_PARSE
// All Errors: E_ALL
// No Errors: 0
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// SQL configuration
$bereso['sql']['host'] = "HOST";
$bereso['sql']['user'] = "DBUser";
$bereso['sql']['password'] = "DBPassword";
$bereso['sql']['database'] = "DBName";

// Main URL
$bereso['url'] = "http://bereso/";

// Image filepath
$bereso['images'] = "images/";

// Images thumbnail height
$bereso['images_thumbnail_height'] = 200;

// Amount new entry images - preview image not counting - must be at least 1 
$bereso['new_amount_images'] = 5;

// Version
$bereso['version'] = "2.6";

// Last update
$bereso['last_change'] = "10.03.2021";

// Creation Date
$bereso['created'] = "14.01.2021";

// Title
$bereso['title'] = "BeReSo";

// Timezone
date_default_timezone_set('Europe/Berlin'); 

// Date time format
$bereso['datetimestring'] = "d.m.Y H:i:s";
$bereso['datestring'] = "d.m.Y";

// Max allowed upload size
	// example config in php.ini:
	//post_max_size = 60M
	//upload_max_filesize = 60M
$bereso['max_upload_size'] = 60 * 1024 * 1024; // Size in Bytes

// Logging
$bereso['log_die'] = true; // enable logging of die messages in text file
$bereso['log_die_path'] = "die.log"; // path to this textfile

// Session and Cookie timeout
ini_set('session.gc_maxlifetime', 30*24*60*60);
ini_set('session.cookie_lifetime', 30*24*60*60);

?>
