<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Main - Index
// ###################################


// Classes
include "classes/config.php"; // Static file Functions
include "classes/file.php"; // Static file Functions
include "classes/log.php"; // Static log Functions
include "classes/time.php"; // Static time Functions
include "classes/user.php"; // Static user Functions
include "classes/text.php"; // Static text Functions
include "classes/image.php"; // Static image Functions
include "classes/item.php"; // Static item Functions
include "classes/tags.php"; // Static tags Functions


// Config
include "config.php";


// start PHP session
session_start();


// set timestamp for this run
$bereso['now'] = time();


// Read POST and GET variables
$user = @$_SESSION['user'];
$passwordhash = @$_SESSION['passwordhash'];
$module = @$_GET['module'];
$action = @$_GET['action'];
$tag = @$_GET['tag'];
$taggroup = @$_GET['taggroup'];
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
elseif ($module == "new") 
{
	$add_name = @$_POST['add_name'];
	$add_text = @$_POST['add_text'];
	$add_photo = @$_FILES['add_photo']; // Array of multiple file input fields
}
// for edit.php
elseif ($module == "edit") 
{
	$edit_name = @$_POST['edit_name'];
	$edit_text = @$_POST['edit_text'];
	$edit_photo = @$_FILES['edit_photo'];
	$item_image_id = @$_GET['item_image_id'];
}
// for new_taggroup.php
elseif ($module == "new_taggroup") 
{
	$add_name = @$_POST['add_name'];
	$add_text = @$_POST['add_text'];
}
// for edit_taggroup.php
elseif ($module == "edit_taggroup") 
{
	$edit_name = @$_POST['edit_name'];
	$edit_text = @$_POST['edit_text'];
	$taggroupid = @$_GET['taggroupid'];
}
// for delete_taggroup.php
elseif ($module == "delete_taggroup") 
{
	$taggroupid = @$_GET['taggroupid'];
}
// for login.php
elseif ($module == "login")
{
	$login_name = @$_POST['login_name']; // the login form
	$login_password = @$_POST['login_password']; // the login form
	$generate_user = @$_GET['generate_user']; // just for the action=generate_user_sqlinsert
	$generate_password = @$_GET['generate_password']; // just for the action=generate_user_sqlinsert
	$generate_template = @$_GET['generate_template']; // just for the action=generate_user_sqlinsert
}


// init variables
$output = null; // THE output variable
$navigation = null; // variable to easy add entrys to the menu navigation in the main.html
$navigation2 = null; // variable to easy add entrys to the bar navigation in the main.html
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
if (!Text::is_letter($taggroup,"a-z0-9")) { Log::die ("CHECK: \$taggroup failed ".'"'.$taggroup.'"'); }
// for list.php
if ($module == "list")
{
	if (!Text::is_letter($search,"a-z0-9 SPECIAL")) { $search_is_letter_failed = true; } else { $search_is_letter_failed = false; }
}
// for new.php
elseif ($module == "new") //  for new.php and new_taggroup.php - user form content will not stop the script but clear the variable!
{
	$form_item_name_error = 0;
	$form_item_text_error = 0;
	if(!Text::is_letter($add_name,"a-z0-9 SPECIAL")) { $form_item_name_error = 1; }
	if(!Text::is_letter($add_text,"a-z0-9 SPECIAL")) { $form_item_text_error = 1; }
}
// for edit.php
elseif ($module == "edit") // for edit.php - user form content will not stop the script but clear the variable!
{
	$form_item_name_error = 0;
	$form_item_text_error = 0;
	if(!Text::is_letter($edit_name,"a-z0-9 SPECIAL")) { $form_item_name_error = 1;  }
	if(!Text::is_letter($edit_text,"a-z0-9 SPECIAL")) { $form_item_text_error = 1;  }
}
// for new_taggroup.php
elseif ($module == "new_taggroup") //  for new_taggroup.php - user form content will not stop the script but clear the variable!
{
	$form_taggroup_name_error = 0;
	$form_taggroup_text_error = 0;
	if(!Text::is_letter($add_name,"a-z0-9")) { $form_taggroup_name_error = 1; }
	if(!Text::is_letter($add_text,"a-z0-9 SPECIAL")) { $form_taggroup_text_error = 1; }
}
// for edit_taggroup.php
elseif ($module == "edit_taggroup") // for  edit_taggroup.php - user form content will not stop the script but clear the variable!
{
	$form_taggroup_name_error = 0;
	$form_taggroup_text_error = 0;
	if(!Text::is_letter($edit_name,"a-z0-9")) { $form_taggroup_name_error = 1; }
	if(!Text::is_letter($edit_text,"a-z0-9 SPECIAL")) {  $form_taggroup_text_error = 1; }
	if (strlen($taggroupid) > 0) { if (!is_numeric($taggroupid)) { Log::die ("CHECK: \$taggroupid failed ".'"'.$taggroupid.'"'); } }
}
// for delete_taggroup.php
elseif ($module == "delete_taggroup") // for  delete_taggroup.php
{
	if (strlen($taggroupid) > 0) { if (!is_numeric($taggroupid)) { Log::die ("CHECK: \$taggroupid failed ".'"'.$taggroupid.'"'); } }
}
// for login.php
elseif ($module == "login")
{
	if (!Text::is_letter($login_name,"a-z-")) { $login_name = null; } // wrong character - reset name - login will fail and return error message
	if (!Text::is_letter($login_password,"a-z0-9 SPECIAL")) { $login_password = null; } // wrong character - reset password - login will fail and return error message
	if (!Text::is_letter($generate_user,"a-z-")) { Log::die ("CHECK: \$generate_user failed ".'"'.$generate_user.'"'); }
	if (!Text::is_letter($generate_password,"a-z0-9 SPECIAL")) { Log::die ("CHECK: \$generate_password failed ".'"'.$generate_password.'"'); }
	if (strlen($generate_template) > 0) { if (!is_numeric($generate_template)) { Log::die ("CHECK: \$generate_template failed ".'"'.$generate_template.'"'); } }
}


// set default page
if ($module == "") { $module = "list_tags"; }


// SQL connection
$sql = @new mysqli($bereso['sql']['host'],$bereso['sql']['user'],$bereso['sql']['password'],$bereso['sql']['database']); 
if (mysqli_connect_errno()) {
    Log::die("Connect failed: " . mysqli_connect_error(), false); // log problems with SQL Connection
}
$sql->query("SET NAMES 'utf8'"); // UTF8 DB Setting


// if install.php is still on the webserver while user trys to run bereso
if (file_exists("install.php")) { Log::die ("Delete install.php after setup to run BeReSo!"); }


// set default title
$title = $bereso['title']; // Browser title
$title_addon = User::get_template_name($user);
if (!empty($title_addon) && User::get_template_id($user) > 0) { $title = $title . " - " . $title_addon; }


// set language
$language = User::get_language($user);
if ($language == null) // User is not logged in -> set system default language
{
	$language = $bereso['default_language'];
}


// Load Modules

// Check if the user is logged in and load the following modules
if (User::is_logged_in($user,$passwordhash)) 
{
	if ($module == "list_tags") { include ("modules/list_tags.php"); }
	elseif ($module == "new_taggroup") { include ("modules/new_taggroup.php"); }
	elseif ($module == "edit_taggroup") { include ("modules/edit_taggroup.php"); }
	elseif ($module == "delete_taggroup") { include ("modules/delete_taggroup.php"); }
	elseif ($module == "list") { include ("modules/list.php"); }
	elseif ($module == "show") { include ("modules/show.php"); }
	elseif ($module == "show_image") { include ("modules/show_image.php"); }
	elseif ($module == "show_printpreview") { include ("modules/show_printpreview.php"); }
	elseif ($module == "new") { include ("modules/new.php"); }
	elseif ($module == "edit") { include ("modules/edit.php"); }
	elseif ($module == "delete") { include ("modules/delete.php"); }
	elseif ($module == "import") { include ("modules/import.php"); }
	elseif ($module == "login") { include ("modules/login.php"); }
}
else // user is not logged in => load login module => form without menu and navigation
{	
	$output_navigation = false;
	include("modules/login.php");	
}

// always load these modules - logged in or not
if ($module == "share") { include ("modules/share.php"); } // share module for logged in and anonymous users
if ($module == "share_image") { include ("modules/share_image.php"); } // share module for logged in and anonymous users
if ($module == "offline") { include ("modules/offline.php"); } // offline module for serviceWorker offline message


// load default template if not allready loaded by module
if ($output_default == true) { $output = File::read_file("templates/main.html"); }


// Navigation changes for many modules:

// -> Last list - backbutton on show, edit, delete
// delete user_last_list for this user if user is not navigating in list.php or show.php or show_image.php or share_image.php or share.php or edit.php or show.php?action=random
if (($module != "show" && $module != "show_image" && $module != "list" && $module != "share" && $module != "share_image" && $module != "edit" && $module != "delete" && $module != "show_printpreview") or $action == "random")
{
	User::set_last_list($user,null); // delete the last list
}
// show last list icon and link when last list is set for this user
if (strlen(User::get_last_list($user)) > 0 && $module != "list") // do not show icon if we are still in the list menu
{
	$navigation2 = File::read_file("templates/main-navigation2-last_list.html") . $navigation2; // set last tag always first
	$last_list_tag = User::get_last_list($user);
	if (substr($last_list_tag,0,6) == "SEARCH") { $last_list_tag = "SEARCH"; }  // do not link the whole search string, just SEARCH
	$navigation2 = str_replace("(main-navigation-last_list_value)",$last_list_tag,$navigation2);
}

// -> Last list_tags taggroup - "backbutton" on list_tags, delete_taggroup, edit_taggroup and list
if ($module == "list_tags" or $module == "list" or $module == "delete_taggroup" or $module == "edit_taggroup")
{
	// if module list_tags is loaded -> set the taggroup
	if ($module == "list_tags") {
		if ($action == "taggroup") { $new_taggroup = $taggroup; } else { $new_taggroup = null; }
		User::set_last_taggroup($user,$new_taggroup); // set last_taggroup to taggroup or delete it
		// set last_list_tags link - always null -> point to the main list_tags page
		$last_taggroup_value = null;
		$last_taggroup_action = null;
	}
	elseif ($module == "list" or $module == "delete_taggroup" or $module == "edit_taggroup") // only show last_taggroup on list, delete_taggroup and edit_taggroup
	{
		$last_taggroup_value = User::get_last_taggroup($user);
		// if $last_taggroup_value is set then the last list_tags list was a taggroup -> else -> point to the main list_tags page
		if (strlen($last_taggroup_value) > 0) { $last_taggroup_action = "taggroup"; } else { $last_taggroup_action = null; }
	}
	// add to navigation2 - if not on the main list_tags page
	if (($module == "list_tags" && $action == "taggroup") or $module == "list" or $module == "delete_taggroup" or $module == "edit_taggroup") {
		$navigation2 = File::read_file("templates/main-navigation2-last_list_tags.html") . $navigation2; // set last list_tag  always first
		$navigation2 = str_replace("(main-navigation-last_list_tags_action)",$last_taggroup_action,$navigation2);
		$navigation2 = str_replace("(main-navigation-last_list_tags_value)",$last_taggroup_value,$navigation2);
	}
}


// content replace
if ($output_navigation == true) { // navigation menu enabled
	$output = str_replace("(bereso_main_navigation)",File::read_file("templates/main-navigation.html"),$output);
	$output = str_replace("(bereso_navigation)",$navigation,$output);
	$output = str_replace("(bereso_navigation2)",$navigation2,$output);
}
else // navigation menu disabled
{
	$output = str_replace("(bereso_main_navigation)",null,$output);
	$output = str_replace("(bereso_navigation)",null,$output);
	$output = str_replace("(bereso_navigation2)",null,$output);
}
$output = str_replace("(bereso_content)",$content,$output); // insert content that is generated by modules
$output = str_replace("(bereso_title)",$title,$output); // insert title
$output = str_replace("(bereso_appname)",$bereso['appname'],$output); // insert appname
$output = str_replace("(bereso_user)",$user,$output); // insert user name
$output = str_replace("(bereso_url)",$bereso['url'],$output); // insert bereso url
$output = str_replace("(bereso_version)",$bereso['version'],$output); // insert bereso version


// User Image folder based on user id or shareid
$user_id = User::get_id_by_name($user);
if (is_numeric($user_id) && $user_id > 0) // User ID is set
{
	$output = str_replace("(bereso_images)",Image::get_foldername_by_user_id($user_id),$output);
}
else 
{
	if (strlen($shareid) > 0) // no user id but share id set
	{
		$output = str_replace("(bereso_images)",Image::get_foldername_by_shareid($shareid),$output);
	}
	else // nothing set - unable to load images
	{
		$output = str_replace("(bereso_images)","ERROR/",$output);
	}
}


// template replaces - based on user and the template that fits this user - plus always load system templates ID=0
if ($result = $sql->query("SELECT template_text_name, template_text_text from bereso_template_text WHERE (template_text_template_id='".User::get_template_id($user)."' OR template_text_template_id='0') AND template_text_language='".$language."'"))
{
	while($row = $result -> fetch_assoc())
	{
		$output = str_replace("(bereso_template-".$row['template_text_name'].")",$row['template_text_text'],$output);
	}
}


// Close SQL connection
$sql->close();


// output header
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