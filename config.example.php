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

// Default language template
// en or de
$bereso['default_language'] = "en";

// Main URL
$bereso['url'] = "http://bereso/";

// Image filepath
$bereso['images'] = "images/";

// Images thumbnail height
$bereso['images_thumbnail_height'] = 200;

// Amount new entry images - preview image not counting - must be at least 1 
$bereso['new_amount_images'] = 5;

// Version
$bereso['version'] = "3.3";

// Last update
$bereso['last_change'] = "21.03.2021";

// Creation Date
$bereso['created'] = "14.01.2021";

// Title
$bereso['title'] = "BeReSo";

// Appname
$bereso['appname'] = "BeReSo";

// Timezone
date_default_timezone_set('Europe/Berlin'); 

// Date time format
$bereso['datetimestring'] = "d.m.Y H:i:s";
$bereso['datestring'] = "d.m.Y";

// Logging
$bereso['log_die'] = true; // enable logging of die messages in text file
$bereso['log_die_path'] = "die.log"; // path to this textfile

// Session and Cookie timeout
$bereso['session_lifetime'] = 2592000; // 30 days => 30 * 24 * 60 * 60

// ###########################################
// NO CONFIG CHANGE NEEDED BELOW THIS LINE
// ###########################################

// Set Session and Cookie timeout
ini_set('session.gc_maxlifetime',$bereso['session_lifetime']);
ini_set('session.cookie_lifetime', $bereso['session_lifetime']);
session_set_cookie_params($bereso['session_lifetime']);

// Read config from php.ini
// Max allowed upload size in Bytes
if (Config::get_phpini_convertedsize('post_max_size') <= Config::get_phpini_convertedsize('upload_max_filesize')) { $bereso['max_upload_size'] = Config::get_phpini_convertedsize('post_max_size'); } else { $bereso['max_upload_size'] = Config::get_phpini_convertedsize('upload_max_filesize'); }
?>
