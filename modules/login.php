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
	Log::useraction($user,$module,$action,"Logout");  // log when user_log enabled
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
		// if entry with this user exists
		if (mysqli_num_rows($result) == 1)
		{
			// Verify entered password but only store salted hash in session
			if (password_verify ($login_password,$row['user_pwhash'])) 
			{
				$passwordhash = $row['user_pwhash']; // store pw hash
				$user = $row['user_name']; // username from db - case sensitive
				Log::useraction($login_name,$module,$action,"Login successful"); // log when user_log enabled
			}
			else 
			{
				Log::useraction($login_name,$module,$action,"Wrong password"); // log when user_log enabled
			}
		}
		else
		{
			Log::useraction($login_name,$module,$action,"User \"$login_name\" does not exist"); 
		}
	}

	// if login is successful
	if (User::is_logged_in($user,$passwordhash))
	{						
			// save cookies and login
			$_SESSION['user'] = $user;
			$_SESSION['passwordhash'] = $passwordhash;		
			
		header('Location: index.php', true, 302); // Redirect to the startpage after successfull login!
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