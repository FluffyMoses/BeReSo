<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Config (dynamic)
// included by ../index.php
// ###################################

// Load dynamic config 

// Load database stored settings
$bereso['session_lifetime'] = Config::get_config("session_lifetime"); // Session lifetime
$bereso['datetimestring'] = Config::get_config("datetimestring"); // Date time format
$bereso['timezone'] = Config::get_config("timezone"); // timezone
$bereso['new_amount_images'] = Config::get_config("new_amount_images"); // Amount new entry images - preview image not counting - must be at least 1 
$bereso['images_thumbnail_height'] = Config::get_config("images_thumbnail_height"); // Images thumbnail height
if (Config::get_config("https_redirect") == "1") { $bereso['https_redirect'] = true; } else { $bereso['https_redirect'] = false; } // Redirect to HTTPS when HTTP is requested - $bereso['url'] musst be https:// 
$bereso['images'] = Config::get_config("images"); // Image filepath
$bereso['url'] = Config::get_config("url"); // Main URL - must end with "/"

// set timezone
date_default_timezone_set($bereso['timezone']); 

// Set Session and Cookie timeout
ini_set('session.gc_maxlifetime',$bereso['session_lifetime']);
ini_set('session.cookie_lifetime', $bereso['session_lifetime']);
session_set_cookie_params($bereso['session_lifetime']);

// Read config from php.ini
// Max allowed upload size in Bytes
if (Config::get_phpini_convertedsize('post_max_size') <= Config::get_phpini_convertedsize('upload_max_filesize')) { $bereso['max_upload_size'] = Config::get_phpini_convertedsize('post_max_size'); } else { $bereso['max_upload_size'] = Config::get_phpini_convertedsize('upload_max_filesize'); }
?>