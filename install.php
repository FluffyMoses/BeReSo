<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Install
// ###################################

// ###################################
// Howto install:
// Edit config.php first!

// Run install.php
// 1) checks installation requirements
// 2) creates tables in database
// 3) inserts templates and template texts
// 4) creates first user account
// 5) delete install.php file

// Login with your new created user
// ###################################

// Classes
include("classes/config.php");
include("classes/file.php");
include("classes/user.php");
include("classes/text.php");


// Config
include("config.php");


// Installer language - use bereso default language if available
if ($bereso['default_language'] == "de" ) { $installer['language'] = "de"; }
else { $installer['language'] = "en"; } // english is always the fallback language

// check tables
$installer['tables'] = array(
	"bereso_group",
	"bereso_images",
	"bereso_item",
	"bereso_tags",
	"bereso_template",
	"bereso_template_text",
	"bereso_user",
);


// check php extensions loaded
$installer['phpextensions'] = array(
	"gd",
	"mysqli",
	"mbstring",
);


// SQL scripts that will be executed
$installer['sql'] = array(
	"sql/create_tables.sql",						// create bereso tables in database

	// german template
	"sql/de/template_de_0_base.sql",				// german base template
	"sql/de/template_de_1_rezeptverwaltung.sql",	// german template recipe management
	"sql/de/template_de_2_kreativ.sql",				// german template creative articles
	"sql/de/template_de_3_projektverwaltung.sql",	// german template project management

	// english template
	"sql/en/template_en_0_base.sql",				// english base template
	"sql/en/template_en_4_recipemanagement.sql",	// english template recipe management
);


// Installer languages text

// german
$installer['text']['de']['bereso_installer'] = "BeReSo Installer";
$installer['text']['de']['sql_connection_error'] = "Pr&uuml;fe \$bereso['sql'] in der Datei config.php<br><br>SQL Verbindung fehlgeschlagen mit dem Fehler:";
$installer['text']['de']['sql_connection_success'] = "SQL Verbindung erfolgreich hergestellt";
$installer['text']['de']['imagefolder_success'] = "Schreibberechtigung auf den Bilderordner:<br><br>";
$installer['text']['de']['imagefolder_error'] = "Keine Schreibberechtigung auf den Bilderordner:<br><br>";
$installer['text']['de']['user_exists'] = "Benutzer sind bereits erstellt in der Tabelle bereso_user:<br><br>";
$installer['text']['de']['user_not_exists'] = "Erstelle Benutzer:";
$installer['text']['de']['table_exists'] = "Tabellen existieren:";
$installer['text']['de']['table_not_exists'] = "Tabellen existieren nicht:";
$installer['text']['de']['create_tables'] = "Erstelle Tabellen und f&uuml;ge Templates ein";
$installer['text']['de']['create_user'] = "Erstelle Benutzer";
$installer['text']['de']['query_sql_error'] = "SQL Query Fehler:";
$installer['text']['de']['installation_successfull'] = "Installation erfolgreich!<br><br>L&ouml;sche die install.php und den sql Ordner vom Webserver und starte BeReSo!";
$installer['text']['de']['user_name'] = "Name (nur a-z, A-Z und - erlaubt)";
$installer['text']['de']['user_password'] = "Passwort";
$installer['text']['de']['user_name_error'] = "Name enh&auml;lt ung&uuml;ltige Zeichen.<br>";
$installer['text']['de']['user_password_error'] = "Passwort enh&auml;lt ung&uuml;ltige Zeichen.<br>";
$installer['text']['de']['user_template'] = "Template";
$installer['text']['de']['phpextensions'] = "PHP Extensions geladen";
$installer['text']['de']['user_createfolder_error'] = "Fehler beim erstellen des Benutzer Bilderordners: ";

// english
$installer['text']['en']['bereso_installer'] = "BeReSo Installer";
$installer['text']['en']['sql_connection_error'] = "Check \$bereso['sql'] in config.php<br><br>SQL Connection failed with error:";
$installer['text']['en']['sql_connection_success'] = "SQL connection established successfully";
$installer['text']['en']['imagefolder_success'] = "Imagefolder has write-permissions:<br><br>";
$installer['text']['en']['imagefolder_error'] = "Imagefolder missing write-permissions:<br><br>";
$installer['text']['en']['user_exists'] = "Users already created in table bereso_users:<br><br>";
$installer['text']['en']['user_not_exists'] = "Create User:";
$installer['text']['en']['table_exists'] = "Tables exists:";
$installer['text']['en']['table_not_exists'] = "Tables not exists:";
$installer['text']['en']['create_tables'] = "Create tables and install templates";
$installer['text']['en']['create_user'] = "Create user";
$installer['text']['en']['query_sql_error'] = "SQL query error:";
$installer['text']['en']['installation_successfull'] = "Installation successfull!<br><br>Delete the install.php file and the sql folder from your webserver and run BeReSo!";
$installer['text']['en']['user_name'] = "Name (nur a-z, A-Z and - allowed)";
$installer['text']['en']['user_password'] = "Password";
$installer['text']['en']['user_name_error'] = "Name contains forbidden characters.<br>";
$installer['text']['en']['user_password_error'] = "Password contains forbidden characters.<br>";
$installer['text']['en']['user_template'] = "Template";
$installer['text']['en']['phpextensions'] = "PHP extensions loaded";
$installer['text']['en']['user_createfolder_error'] = "Error while creating the user image folder: ";


// Installer HTML templates
$installer['template']['main'] = '
<html>
	<head>
		<title>(bereso_title)</title>
		<link rel="stylesheet" href="templates/css/main.css">
	</head>
	<body>
		<br>
		<center>
			<table width="600" cellpadding="0" cellspacing="0" border="0"><tr><td>
				<p class="headline1">(installer_bereso_installer) (bereso_version)</p>
				<br>
				(installer_content)
		</center>
	</body>
</html>
';
$installer['template']['requirements_sql'] = '<div class="boxed">(installer_requirements_sql)</div><br>';
$installer['template']['query_sql'] = '<div class="boxed">(installer_query_sql)</div><br>';
$installer['template']['requirements_imagefolder'] = '<div class="boxed">(installer_requirements_imagefolder)</div><br>';
$installer['template']['requirements_user_exists'] = '<div class="boxed">(installer_requirements_user_exists)</div><br>';
$installer['template']['requirements_user_not_exists'] = '
<div class="boxed">
	(installer_requirements_user_not_exists)<br><br>
	<form enctype="multipart/form-data" action="install.php?action=create_user" method="POST">
		(installer_user_name)<br>
		<input name="user_name" type="text" style="width:100%;" /><br>
		(installer_user_password)<br>
		<input name="user_password" type="password" style="width:100%;" />	<br>
		(installer_user_template)<br>
		<select name="user_templateid">
			(installer_template_options)
		</select>
		<br><font color="red">(installer_user_error)</font><br>	
		<input type="submit" value="(installer_requirements_create_user)" />
	</form>
</div>
<br>
';
$installer['template']['requirements_phpextension'] = '<div class="boxed">(installer_phpextensions):<br><br>(installer_requirements_phpextension_items)</div><br>';
$installer['template']['requirements_phpextension_item'] = '(installer_requirements_phpextension_item)<br>';
$installer['template']['requirements_table_item'] = '(installer_requirements_table_item)<br>';
$installer['template']['requirements_table_exists'] = '<div class="boxed">(installer_requirements_table_exists)</div><br>';
$installer['template']['requirements_table_not_exists'] = '<div class="boxed">(installer_requirements_table_not_exists)<br><form enctype="multipart/form-data" action="install.php?action=create_tables" method="POST"><input type="submit" value="(installer_requirements_create_tables)" /></form></div><br>';
$installer['template']['installation_successfull'] = '<div class="boxed">(installer_installation_successfull)<br><br><a class="none" href="index.php">BeReSo index.php</a></div><br>';
$installer['template']['font_green_open'] = '<font color="green">';
$installer['template']['font_green_close'] = '</font>';
$installer['template']['font_red_open'] = '<font color="red">';
$installer['template']['font_red_close'] = '</font>';	
$installer['template']['template_options'] = '
	<option value="1">DE - Rezeptverwaltung</option>	
	<option value="2">DE - Kreativ</option>
	<option value="3">DE - Projektverwaltung</option>
	<option value="4">EN - Recipe management</option>
';


// init variables
$output = $installer['template']['main']; // THE output variable - init with main html code
$content = null; // content variable
$content_tables = null;
$content_extension = null;
$content_usererror = null;


// GET and POST variables
$action = @$_GET['action'];
$user_name = @$_POST['user_name'];
$user_password = @$_POST['user_password'];
$user_templateid = @$_POST['user_templateid'];


// check the post variables
if ($action == "create_user") // only check variables if posted
{ 
	if (!Text::is_letter($user_name,"a-z-") or strlen($user_name) == 0) { // check if name is ok
		$check_post['user'] = false;
		echo $installer['language'];
		$content_usererror .= $installer['text'][$installer['language']]['user_name_error'];
	}
	else
	{
		$check_post['user'] = true;
		if (!Text::is_letter($user_password,"a-z0-9 SPECIAL") or strlen($user_password) == 0) // check if password is ok
		{
			$check_post['user'] = false;
			$content_usererror .= $installer['text'][$installer['language']]['user_password_error'];
		}
		else
		{
			$check_post['user'] = true; 
		}
	}
}


// Requirements

//  init requirements
$check_requirement['sql'] = false; // check requirements: sql connection working
$check_requirement['imagefolder'] = false; // check requirements: image folder write access
$check_requirement['tables_exist'] = true; // check requirements: db tables exist - start true -> false if not exists down below
$check_requirement['user_exists'] = false; // check requirements: user does not exist
$check_requirement['phpextensions'] = true; // check requirements: php extensions loaded - start true -> false if not loaded down below

// PHP extensions - check requirements
for ($i=0;$i<count($installer['phpextensions']);$i++)
{
	if (extension_loaded($installer['phpextensions'][$i]))
	{ 		
		$content_extension .= str_replace("(installer_requirements_phpextension_item)",$installer['template']['font_green_open'].$installer['phpextensions'][$i].$installer['template']['font_green_close'],$installer['template']['requirements_phpextension_item']); // load requirements_phpextension_item template and insert phpextension item
	}
	else
	{
		$check_requirement['phpextensions'] = false;
		$content_extension .= str_replace("(installer_requirements_phpextension_item)",$installer['template']['font_red_open'].$installer['phpextensions'][$i].$installer['template']['font_red_close'],$installer['template']['requirements_phpextension_item']); // load requirements_phpextension_item template and insert phpextension item
		
	}
}

// SQL connection - check requirements and establish
if ($check_requirement['phpextensions'] == true)
{
	$sql = @new mysqli($bereso['sql']['host'],$bereso['sql']['user'],$bereso['sql']['password'],$bereso['sql']['database']); 
	if (mysqli_connect_errno()) {
		$content_sqlerror = mysqli_connect_error();
	}
	else
	{
		$check_requirement['sql'] = true; // sql connection working
		$sql->query("SET NAMES 'utf8'"); // UTF8 DB Setting
	}	
}

// images folder - check requirements
if (is_writable($bereso['images'])){
	$check_requirement['imagefolder'] = true;	
}

// DB tables - check requirements
if ($check_requirement['phpextensions'] == true && $check_requirement['sql'] == true)
{
	for ($i=0;$i<count($installer['tables']);$i++)
	{
		$table_exists = $sql->query("SELECT 1 FROM " . $installer['tables'][$i]);
		if ($table_exists == true)
		{ 		
			$content_tables .= str_replace("(installer_requirements_table_item)",$installer['template']['font_green_open'].$installer['tables'][$i].$installer['template']['font_green_close'],$installer['template']['requirements_table_item']); // load requirements_table_item template and insert table item
		}
		else
		{
			$check_requirement['tables_exist'] = false;
			$content_tables .= str_replace("(installer_requirements_table_item)",$installer['template']['font_red_open'].$installer['tables'][$i].$installer['template']['font_red_close'],$installer['template']['requirements_table_item']); // load requirements_table_item template and insert table item
		}
	}
}

// User creation - check requirements 
if ($check_requirement['tables_exist'] == true && $check_requirement['phpextensions'] == true && $check_requirement['sql'] == true) // only if database is already created
{
	if ($result = $sql->query("SELECT * from bereso_user"))
	{
		if (mysqli_num_rows($result) > 0) // User already exists
		{
			$check_requirement['user_exists'] = true;			
		}
		else
		{
			$check_requirement['user_exists'] = false;			
		}
	}
}


// install actions

// create tables and insert templates
if ($action == "create_tables")
{
	// run all sql scripts
	$sql_file = null;
	for ($i=0;$i<count($installer['sql']);$i++)
	{
		$sql_file .= File::read_file($installer['sql'][$i])."\n";
	}
	if (!$sql->multi_query($sql_file)) 
	{
		$content = str_replace("(installer_query_sql)",$installer['template']['font_red_open'].$installer['text'][$installer['language']]['query_sql_error']."<br> " . $sql->error.$installer['template']['font_red_close'],$installer['template']['query_sql']); // load query_sql template and insert error message
		$output = str_replace("(installer_content)",$content,$output); // insert content in output template
		die($output); // end the install script
	}

	header('Location: install.php'); // Redirect to the startpage
	exit(); // stops the rest of the script from running 
}

// create user
if ($action == "create_user" && $check_post['user'] == true)
{
	// check if name, password and templateid are not empty
	if(strlen($user_name) > 0 && strlen($user_password) > 0 && is_numeric($user_templateid))
	{
		$sql->query("INSERT INTO bereso_user (user_name,user_pwhash,user_template) VALUES ('".$user_name."','".User::generate_password_hash($user_password)."','".$user_templateid."')"); // save new user to the database
		$add_id = $sql->insert_id;
		// create image folder for this user
		if (!@mkdir($bereso['images'].$add_id)) 
		{
			die($installer['text'][$installer['language']]['user_createfolder_error'] . $bereso['images'].$add_id);
		}
	}

	header('Location: install.php'); // Redirect to the startpage
	exit(); // stops the rest of the script from running 
}


// build output Content

// php extensions loaded
$content .= str_replace("(installer_requirements_phpextension_items)",$content_extension,$installer['template']['requirements_phpextension']);
$content = str_replace("(installer_phpextensions)",$installer['text'][$installer['language']]['phpextensions'],$content);

// sql connection - connected
if ($check_requirement['phpextensions'] == true)
{
	if ($check_requirement['sql'] == true)
	{
			$content .= str_replace("(installer_requirements_sql)",$installer['template']['font_green_open'].$installer['text'][$installer['language']]['sql_connection_success'].$installer['template']['font_green_close'],$installer['template']['requirements_sql']); // load requirements_sql template and insert success message
	}
	else
	{
		$content .= str_replace("(installer_requirements_sql)",$installer['template']['font_red_open'].$installer['text'][$installer['language']]['sql_connection_error']."<br> " . $content_sqlerror . $installer['template']['font_red_close'],$installer['template']['requirements_sql']); // load requirements_sql template and insert error message
	}
}

// images folder - writeable
if ($check_requirement['imagefolder'] == true)
{
	$content .= str_replace("(installer_requirements_imagefolder)",$installer['template']['font_green_open'].$installer['text'][$installer['language']]['imagefolder_success'] . $bereso['images'].$installer['template']['font_green_close'],$installer['template']['requirements_imagefolder']); // load requirements_imagefolder template and insert success message
}
else
{
	$content .= str_replace("(installer_requirements_imagefolder)",$installer['template']['font_red_open'].$installer['text'][$installer['language']]['imagefolder_error'] . $bereso['images'].$installer['template']['font_red_close'],$installer['template']['requirements_imagefolder']); // load requirements_imagefolder template and insert error message
}

// tables - exist
if ($check_requirement['phpextensions'] == true)
{
	if ($check_requirement['imagefolder'] == true)
	{
		if ($check_requirement['sql'] == true)
		{
			if ( $check_requirement['tables_exist'] == true)
			{
				$content .= str_replace("(installer_requirements_table_exists)",$content_tables,$installer['template']['requirements_table_exists']); // load table_exists template and insert success message
			}
			else
			{
				$content .= str_replace("(installer_requirements_table_not_exists)",$content_tables,$installer['template']['requirements_table_not_exists']); // load table_exists template and insert error message
				$content = str_replace("(installer_requirements_create_tables)",$installer['text'][$installer['language']]['create_tables'],$content);
			}
		}
	}
}

// user not created
if ($check_requirement['phpextensions'] == true)
{
	if ($check_requirement['imagefolder'] == true)
	{
		if ($check_requirement['tables_exist'] == true)
		{
			if ($check_requirement['sql'] == true)
			{
				if ($check_requirement['user_exists'] == false)
				{
					$content .= str_replace("(installer_requirements_user_not_exists)",$installer['template']['font_red_open'].$installer['text'][$installer['language']]['user_not_exists'].$installer['template']['font_red_close'],$installer['template']['requirements_user_not_exists']); // load requirements_user_not_exists template and insert user not exists message
					$content = str_replace("(installer_requirements_create_user)",$installer['text'][$installer['language']]['create_user'],$content);
					$content = str_replace("(installer_user_name)",$installer['text'][$installer['language']]['user_name'],$content);
					$content = str_replace("(installer_user_password)",$installer['text'][$installer['language']]['user_password'],$content);
					$content = str_replace("(installer_user_template)",$installer['text'][$installer['language']]['user_template'],$content);
					$content = str_replace("(installer_template_options)",$installer['template']['template_options'],$content);
					// insert error message for username or password
					if (strlen($content_usererror) > 0) { $content = str_replace("(installer_user_error)",$content_usererror,$content); } else { $content = str_replace("(installer_user_error)",null,$content); }
				}
				else
				{
					$content .= str_replace("(installer_requirements_user_exists)",$installer['template']['font_green_open'].$installer['text'][$installer['language']]['user_exists'] . mysqli_num_rows($result).$installer['template']['font_green_close'],$installer['template']['requirements_user_exists']); // load requirements_user_exists template and insert user exists message
				}
			}
		}
	}
}

// if all checks are successfull - bereso is installed -> message delete the installer
if ($check_requirement['tables_exist'] == true && $check_requirement['user_exists'] == true && $check_requirement['imagefolder'] == true && $check_requirement['phpextensions'])
{
	$content .= str_replace("(installer_installation_successfull)",$installer['text'][$installer['language']]['installation_successfull'],$installer['template']['installation_successfull']); // load installation_successfull message
}


// replaces
$output = str_replace("(installer_content)",$content,$output); // insert content in output template
$output = str_replace("(bereso_title)",$installer['text'][$installer['language']]['bereso_installer']." ".$bereso['version'],$output); // insert title
$output = str_replace("(bereso_version)",$bereso['version'],$output); // insert bereso version
$output = str_replace("(installer_bereso_installer)",$installer['text'][$installer['language']]['bereso_installer'],$output); // insert installer text in main template


// Close SQL connection
if ($check_requirement['phpextensions'] == true && $check_requirement['sql'] == true)
{
	$sql->close();
}


// output header
header('Content-Type: text/html; charset=UTF-8'); // UTF 8 Output


// Send output
echo $output;
?>