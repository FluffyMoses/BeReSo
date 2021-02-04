<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Main - Index
// ###################################

// Config
include "config.php";

// Start PHP Session
session_start();

// set timestamp for this run
$timestamp = time();

// Read POST and GET variables
$user = @$_SESSION['user'];
$passwordhash = @$_SESSION['passwordhash'];
$module = @$_GET['module'];
$action = @$_GET['action'];
$tag = @$_GET['tag'];
$recipe = @$_GET['recipe'];
$recipe_image_id = @$_GET['recipe_image_id'];
$shareid = @$_GET['shareid'];
$share_image_id = @$_GET['share_image_id'];
// for list_recipes.php
if ($module == "list_recipes")
{
	$search = @$_POST['search'];
}
// for new_recipe.php
if ($module == "new_recipe") 
{
	$add_name = @$_POST['add_name'];
	$add_text = @$_POST['add_text'];
	$add_photo0 = @$_FILES['add_photo0'];
	$add_photo1 = @$_FILES['add_photo1'];
	$add_photo2 = @$_FILES['add_photo2'];
	$add_photo3 = @$_FILES['add_photo3'];
}
// for edit_recipe.php
if ($module == "edit_recipe") 
{
	$edit_name = @$_POST['edit_name'];
	$edit_text = @$_POST['edit_text'];
	$edit_photo0 = @$_FILES['edit_photo0'];
	$edit_photo1 = @$_FILES['edit_photo1'];
	$edit_photo2 = @$_FILES['edit_photo2'];
	$edit_photo3 = @$_FILES['edit_photo3'];	
	$recipe_image_id = @$_GET['recipe_image_id'];
}
// for login.php
if ($module == "login")
{
	$login_name = @$_POST['login_name']; // the login form
	$login_password = @$_POST['login_password']; // the login form
	$generate_password = @$_GET['generate_password']; // just for the action=generate_pw
}

// init variables
$output = null; // THE output variable
$navigation = null; // variable to easy add entrys to the navigation in the main.txt
$content = null; // content variable contains the content of the modules generated html
$output_default = true; // use default template for output
$output_navigation = true; // show navigation menu
  
// Classes
include "class/class.functions.php";

// Functions class instance
$f = new functions();

// check POST and GET variables to prevent SQL injections
if (!$f->is_letter($user,"a-z")) { die ("CHECK: \$user failed"); }
if (!$f->is_letter($passwordhash,"a-z0-9 SPECIALPASSWORDHASH")) { die ("CHECK: \$passwordhash failed"); }
if (!$f->is_letter($module,"a-z_")) { die ("CHECK: \$module failed"); }
if (!$f->is_letter($action,"a-z_")) { die ("CHECK: \$action failed"); }
if (!$f->is_letter($tag,"a-z0-9")) { die ("CHECK: \$tag failed"); }
if (strlen($recipe) > 0) { if (!is_numeric($recipe)) { die ("CHECK: \$recipe failed"); } }
if (strlen($recipe_image_id) > 0) { if (!is_numeric($recipe_image_id)) { die ("CHECK: \$recipe_image_id failed"); } }
if (!$f->is_letter($shareid,"a-z0-9")) { die ("CHECK: \$shareid failed"); }
if (strlen($share_image_id) > 0) { if (!is_numeric($share_image_id)) { die ("CHECK: \$share_image_id failed"); } }
// for list_recipes.php
if ($module == "list_recipes")
{
	if (!$f->is_letter($search,"a-z0-9 SPECIAL")) { die ("CHECK: \$search failed"); }
}
// for new_recipe.php
if ($module == "new_recipe") //  for new_recipe.php - user form content will not stop the script but clear the variable!
{
	$form_recipe_name_error = 0;
	$form_recipe_text_error = 0;
	if(!$f->is_letter($add_name,"a-z0-9 SPECIAL")) { $form_recipe_name_error = 1; }
	if(!$f->is_letter($add_text,"a-z0-9 SPECIAL")) { $form_recipe_text_error = 1;}
}
// for edit_recipe.php
if ($module == "edit_recipe") // for edit_recipe.php - user form content will not stop the script but clear the variable!
{
	$form_recipe_name_error = 0;
	$form_recipe_text_error = 0;
	if(!$f->is_letter($edit_name,"a-z0-9 SPECIAL")) { $form_recipe_name_error = 1; }
	if(!$f->is_letter($edit_text,"a-z0-9 SPECIAL")) { $form_recipe_text_error = 1;}
	if (strlen($recipe_image_id) > 0) { if (!is_numeric($recipe_image_id)) { die ("CHECK: \$recipe_image_id failed"); } }
}
// for login.php
if ($module == "login")
{
	if (!$f->is_letter($login_name,"a-z")) { $login_name = null; } // wrong character - reset name - login will fail and return error message
	if (!$f->is_letter($login_password,"a-z0-9 SPECIAL")) { $login_password = null; } // wrong character - reset password - login will fail and return error message
	if (!$f->is_letter($generate_password,"a-z0-9 SPECIAL")) { die ("CHECK: \$generate_password failed"); }
}

// set default page
if ($module == "") { $module = "list_tags"; }

// set default title
$title = $bereso['title']; // Browser title

// SQL connection
$sql = new mysqli($bereso['sql']['host'],$bereso['sql']['user'],$bereso['sql']['password'],$bereso['sql']['database']); 
$sql->query("SET NAMES 'utf8'"); // UTF8 DB Setting

// Check if the user is logged in
if ($f->is_logged_in($user,$passwordhash)) 
{
	if ($module == "list_tags") { include ("modules/list_tags.php"); }
	elseif ($module == "list_recipes") { include ("modules/list_recipes.php"); }
	elseif ($module == "show_recipe") { include ("modules/show_recipe.php"); }
	elseif ($module == "show_recipe_image") { include ("modules/show_recipe_image.php"); }
	elseif ($module == "new_recipe") { include ("modules/new_recipe.php"); }
	elseif ($module == "edit_recipe") { include ("modules/edit_recipe.php"); }
	elseif ($module == "delete_recipe") { include ("modules/delete_recipe.php"); }
	elseif ($module == "import") { include ("modules/import.php"); }
	elseif ($module == "login") { include ("modules/login.php"); }
}
else // not logged in => login form without menu and navigation
{	
	$output_navigation = false;
	include("modules/login.php");	
}
// always load these modules - logged in or not
if ($module == "share") { include ("modules/share.php"); } // share module for logged in and anonymous users
if ($module == "share_image") { include ("modules/share_image.php"); } // share module for logged in and anonymous users

// Close SQL connection
$sql->close();

// load default template if not allready loaded by module
if ($output_default == true) { $output = $f->read_file("templates/main.txt"); }

// content replace
if ($output_navigation == true) {
	$output = str_replace("(bereso_main_navigation)",$f->read_file("templates/main-navigation.txt"),$output);
	$output = str_replace("(bereso_navigation)",$navigation,$output);
}
else
{
	$output = str_replace("(bereso_main_navigation)",null,$output);
	$output = str_replace("(bereso_navigation)",null,$output);
}
$output = str_replace("(bereso_content)",$content,$output);
$output = str_replace("(bereso_title)",$title,$output);
$output = str_replace("(bereso_user)",$user,$output);
$output = str_replace("(bereso_url)",$bereso['url'],$output);
$output = str_replace("(bereso_recipe_images)",$bereso['recipe_images'],$output);

// echo output
header('Content-Type: text/html; charset=UTF-8'); // UTF 8 Output
echo $output;
?>