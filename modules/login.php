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
	$login_message .= $f->read_file("templates/login-message_logout.txt");
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
	
	// if login is successful
	if ($f->is_logged_in($login_name,$login_password))
	{
		// read the right case sensitive user spelling from the database table
        if ($result = $sql->query("SELECT user_name from bereso_user WHERE user_name='$login_name'"))
		{
			$row = $result -> fetch_assoc();
			
			// save cookies and login
			$_SESSION['user'] = $row['user_name'];
			$_SESSION['password'] = $login_password;
			$user = $row['user_name'];
			$password = $login_password;			
		}
			
		header('Location: '.$bereso['url'],true, 301 ); // Redirect to the startpage after successfull login!
		exit();
	}
	else // not successful login 
	{	
		// Errormessage and destroy session
		$login_message .= $f->read_file("templates/login-message_error.txt");
		$_SESSION['user'] = null;
		$_SESSION['password'] = null;
		$user = null;
		$password = null;			
		session_destroy();
	}

}

// Generate and return password hash
if ($action == "generate_pw") {
	die($f->generate_password_hash($generate_password));
}

// Login form
if (!($f->is_logged_in($user,$password))) 
{
	$content .= $f->read_file("templates/login-form.txt");
	$content = str_replace("(bereso_login_message)",$login_message,$content);
}
?>