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
		exit();
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

// Generate and return password hash
if ($action == "generate_pw") {
	die(User::generate_password_hash($generate_password)); // regular die no (Log::die) logging of the hash value!
}

// Login form
if (!(User::is_logged_in($user,$passwordhash))) 
{
	$content .= File::read_file("templates/login-form.html");
	$content = str_replace("(bereso_login_message)",$login_message,$content);
}
?>