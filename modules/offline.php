<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Offline
// included by ../index.php
// ###################################

// disable navigation
$output_navigation = false;
// disable main template
$output_default = false;

// load template
$output = File::read_file("templates/offline.html");

// insert stylesheet file directly (no need to cache more than one file)
$output = str_replace("(bereso_offline_stylesheet_main)",File::read_file("templates/css/main.css"),$output);
?>