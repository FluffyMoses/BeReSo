<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Login
// included by ../index.php
// ###################################

$login_message = null;

// logout user and show login form again
if ($action == "logout")
{
	$login_message .= File::read_file("templates/login-message_logout.html");
	// Delete Cookies and clear login
	$_SESSION['user'] = null;
	$_SESSION['password'] = null;
	$user = null;
	$password = null;	
	session_destroy();
	$output_navigation = false;
}

// Do login
if ($action == "dologin")
{
	// read the right case sensitive user spelling and the pw hash from the database table 
	$query = "SELECT user_name, user_pwhash from bereso_user WHERE user_name='".$login_name."'";
    if ($result = $sql->query($query))
	{
		$row = $result -> fetch_assoc();
		// if entry with this share id exists
		if (mysqli_num_rows($result) == 1)
		{
			// Verify entered password but only store salted hash in session
			if (password_verify ($login_password,$row['user_pwhash'])) 
			{
				$passwordhash = $row['user_pwhash']; // store pw hash
				$user = $row['user_name']; // username from db - case sensitive
			}
		}
	}

	// if login is successful
	if (User::is_logged_in($user,$passwordhash))
	{						
			// save cookies and login
			$_SESSION['user'] = $user;
			$_SESSION['passwordhash'] = $passwordhash;		
			
		header('Location: '.$bereso['url'],true, 301 ); // Redirect to the startpage after successfull login!
		exit(); // stops the rest of the script from running 
	}
	else // not successful login 
	{	
		// Errormessage and destroy session
		$login_message .= File::read_file("templates/login-message_error.html");
		$_SESSION['user'] = null;
		$_SESSION['passwordhash'] = null;
		$user = null;
		$passwordhash = null;			
		session_destroy();
	}

}

// Generate SQL INSERT with password hash -- execute it to add new user
if ($action == "generate_user_sqlinsert") {
	if (strlen($generate_user) > 0) 
	{
		if (strlen($generate_user) > 0) {
			if (is_numeric($generate_template) && $generate_template > 0)
			{
				if (User::get_id_by_name($generate_user) == 0) // User does not exist
				{
					$content = "-- (bereso_template-login_execute_sql_query) ".$generate_user."<br>\nINSERT INTO bereso_user (user_name,user_pwhash,user_template) VALUES ('".$generate_user."','".User::generate_password_hash($generate_password)."','".$generate_template."');"; // regular die no (Log::die) logging of the hash value!
				}
				else // User exists
				{
					$content = "(bereso_template-login_execute_sql_query_user_exists)".$generate_user;
				}
			}
			else
			{
				Log::die("generate_user_sqlinsert: generate_template mut be greater than 0");
			}
		}
		else
		{
			Log::die("generate_user_sqlinsert: generate_password not set");
		}
	} 
	else 
	{
		Log::die("generate_user_sqlinsert: generate_user not set");
	}

}

// Login form
if (!(User::is_logged_in($user,$passwordhash))) 
{
	$content .= File::read_file("templates/login-form.html");
	$content = str_replace("(bereso_login_message)",$login_message,$content);

	// insert motd if set
	if (strlen(Config::get_config("login_motd")) > 0)
	{
		$content = str_replace("(bereso_login_motd)",File::read_file("templates/login-message_motd.html"),$content); // insert the motd template into the login template
		$content = str_replace("(bereso_login_motd_value)",Config::get_config("login_motd"),$content); // insert the motd value from the database into the template
	}
	else
	{
		$content = str_replace("(bereso_login_motd)",null,$content); // no motd - delete the replace placeholder
	}

}
?>