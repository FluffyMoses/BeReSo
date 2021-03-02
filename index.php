<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Main - Index
// ###################################

// Config
include "config.php";

// Classes
include "classes/file.php"; // Static file Functions
include "classes/log.php"; // Static log Functions
include "classes/time.php"; // Static time Functions
include "classes/user.php"; // Static user Functions
include "classes/text.php"; // Static text Functions
include "classes/image.php"; // Static image Functions
include "classes/item.php"; // Static item Functions

// Start PHP Session
session_start();

// set timestamp for this run
$bereso['now'] = time();

// Read POST and GET variables
$user = @$_SESSION['user'];
$passwordhash = @$_SESSION['passwordhash'];
$module = @$_GET['module'];
$action = @$_GET['action'];
$tag = @$_GET['tag'];
$item = @$_GET['item'];
$item_image_id = @$_GET['item_image_id'];
$shareid = @$_GET['shareid'];
$share_image_id = @$_GET['share_image_id'];
// for list.php
if ($module == "list")
{
	$search = @$_POST['search'];
}
// for new.php
if ($module == "new") 
{
	$add_name = @$_POST['add_name'];
	$add_text = @$_POST['add_text'];
	$add_photo = @$_FILES['add_photo']; // Array of multiple file input fields
}
// for edit.php
if ($module == "edit") 
{
	$edit_name = @$_POST['edit_name'];
	$edit_text = @$_POST['edit_text'];
	$edit_photo = @$_FILES['edit_photo'];
	$item_image_id = @$_GET['item_image_id'];
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
$navigation = null; // variable to easy add entrys to the navigation in the main.html
$content = null; // content variable contains the content of the modules generated html
$output_default = true; // use default template for output
$output_navigation = true; // show navigation menu

// check POST and GET variables to prevent SQL injections
if (!Text::is_letter($user,"a-z-")) { Log::die ("CHECK: \$user failed ".'"'.$user.'"'); }
if (!Text::is_letter($passwordhash,"a-z0-9 SPECIALPASSWORDHASH")) { Log::die ("CHECK: \$passwordhash failed ".'"'.$passwordhash.'"'); }
if (!Text::is_letter($module,"a-z_")) { Log::die ("CHECK: \$module failed ".'"'.$module.'"'); }
if (!Text::is_letter($action,"a-z_")) { Log::die ("CHECK: \$action failed ".'"'.$action.'"'); }
if (!Text::is_letter($tag,"a-z0-9")) { Log::die ("CHECK: \$tag failed ".'"'.$tag.'"'); }
if (strlen($item) > 0) { if (!is_numeric($item)) { Log::die ("CHECK: \$item failed ".'"'.$item.'"'); } }
if (strlen($item_image_id) > 0) { if (!is_numeric($item_image_id)) { Log::die ("CHECK: \$item_image_id failed ".'"'.$item_image_id.'"'); } }
if (!Text::is_letter($shareid,"a-z0-9")) { Log::die ("CHECK: \$shareid failed ".'"'.$shareid.'"'); }
if (strlen($share_image_id) > 0) { if (!is_numeric($share_image_id)) { Log::die ("CHECK: \$share_image_id failed ".'"'.$share_image_id.'"'); } }
// for list.php
if ($module == "list")
{
	if (!Text::is_letter($search,"a-z0-9 SPECIAL")) { $search_is_letter_failed = true; } else { $search_is_letter_failed = false; }
}
// for new.php
if ($module == "new") //  for new.php - user form content will not stop the script but clear the variable!
{
	$form_item_name_error = 0;
	$form_item_text_error = 0;
	if(!Text::is_letter($add_name,"a-z0-9 SPECIAL")) { $form_item_name_error = 1; }
	if(!Text::is_letter($add_text,"a-z0-9 SPECIAL")) { $form_item_text_error = 1;}
}
// for edit.php
if ($module == "edit") // for edit.php - user form content will not stop the script but clear the variable!
{
	$form_item_name_error = 0;
	$form_item_text_error = 0;
	if(!Text::is_letter($edit_name,"a-z0-9 SPECIAL")) { $form_item_name_error = 1; }
	if(!Text::is_letter($edit_text,"a-z0-9 SPECIAL")) { $form_item_text_error = 1;}
}
// for login.php
if ($module == "login")
{
	if (!Text::is_letter($login_name,"a-z-")) { $login_name = null; } // wrong character - reset name - login will fail and return error message
	if (!Text::is_letter($login_password,"a-z0-9 SPECIAL")) { $login_password = null; } // wrong character - reset password - login will fail and return error message
	if (!Text::is_letter($generate_password,"a-z0-9 SPECIAL")) { Log::die ("CHECK: \$generate_password failed ".'"'.$generate_password.'"'); }
}

// set default page
if ($module == "") { $module = "list_tags"; }

// SQL connection
$sql = new mysqli($bereso['sql']['host'],$bereso['sql']['user'],$bereso['sql']['password'],$bereso['sql']['database']); 
if (mysqli_connect_errno()) {
    Log::die("Connect failed: " . mysqli_connect_error()); // log problems with SQL Connection
}
$sql->query("SET NAMES 'utf8'"); // UTF8 DB Setting

// set default title
$title = $bereso['title']; // Browser title
$title_addon = User::get_template_name($user);
if (!empty($title_addon)) { $title = $title . " - " . User::get_template_name($user); }

// Check if the user is logged in
if (User::is_logged_in($user,$passwordhash)) 
{
	if ($module == "list_tags") { include ("modules/list_tags.php"); }
	elseif ($module == "list") { include ("modules/list.php"); }
	elseif ($module == "show") { include ("modules/show.php"); }
	elseif ($module == "show_image") { include ("modules/show_image.php"); }
	elseif ($module == "new") { include ("modules/new.php"); }
	elseif ($module == "edit") { include ("modules/edit.php"); }
	elseif ($module == "delete") { include ("modules/delete.php"); }
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

// load default template if not allready loaded by module
if ($output_default == true) { $output = File::read_file("templates/main.html"); }

// content replace
if ($output_navigation == true) {
	$output = str_replace("(bereso_main_navigation)",File::read_file("templates/main-navigation.html"),$output);
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
$output = str_replace("(bereso_images)",$bereso['images'],$output);

// template replaces - based on user and the template that fits this user - plus always load system templates ID=0
if ($result = $sql->query("SELECT template_text_name, template_text_text from bereso_template_text WHERE template_text_template_id='".User::get_template_id($user)."' OR template_text_template_id='0'"))
{
	while($row = $result -> fetch_assoc())
	{
		$output = str_replace("(bereso_template-".$row['template_text_name'].")",$row['template_text_text'],$output);
	}
}

// Last list
// delete user_last_list for this user if user is not navigating in list.php or show.php or show_image.php or share_image.php or share.php or edit.php or show.php?action=random
if (($module != "show" && $module != "show_image" && $module != "list" && $module != "share" && $module != "share_image" && $module != "edit" && $module != "delete") or $action == "random")
{
	User::set_last_list($user,null); // delete the last list
}
// show last list icon and link when last list is set for this user
if (strlen(User::get_last_list($user)) > 0 && $module != "list") // do not show icon if we are still in the list menu
{
	$output = str_replace("(main-navigation-last_list)",File::read_file("templates/main-navigation-last_list.html"),$output);
	$last_list_tag = User::get_last_list($user);
	if (substr($last_list_tag,0,6) == "SEARCH") { $last_list_tag = "SEARCH"; }  // do not link the whole search string, just SEARCH
	$output = str_replace("(main-navigation-last_list_value)",$last_list_tag,$output);
}
else 
{
	$output = str_replace("(main-navigation-last_list)",null,$output);
}



// Close SQL connection
$sql->close();

// echo output
header('Content-Type: text/html; charset=UTF-8'); // UTF 8 Output

// Browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // disable caching - problem with changing and turning images
header("Cache-Control: post-check=0, pre-check=0", false); // disable caching - problem with changing and turning images
header("Pragma: no-cache"); // disable caching - problem with changing and turning images
// WORKAROUND to prevent browser from caching images (even if headers say that it should not be cached)
$output = str_replace(".jpg",".jpg?".uniqid(),$output); // add ?uniqueid that does nothing but is always a "new" image link - WORKAROUND
$output = str_replace(".png",".png?".uniqid(),$output); // add ?uniqueid that does nothing but is always a "new" image link - WORKAROUND

// Send output
echo $output;

?>