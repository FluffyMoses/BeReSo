<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Configuration
// ###################################

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

// Logging
$bereso['log_die'] = true; // enable logging of die messages (critical failures or unallowed access) in text file
$bereso['log_die_path'] = "die.log"; // path to this textfile

// Userconfig default values
$bereso['userconfig_items_per_page'] = 100; // Items per page
$bereso['userconfig_wakescreenlock'] = 0; // wake screenlock enabled
?>