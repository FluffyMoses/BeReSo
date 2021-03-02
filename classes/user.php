<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class user functions
// included by ../index.php
// ###################################

class User 
{

	// get templatename by user_id
	public static function get_template_name($gtn_user)
	{
		global $sql;	
        if ($result = $sql->query("SELECT template_name from bereso_template WHERE template_id='".User::get_template_id($gtn_user)."'"))
		{
			$row = $result -> fetch_assoc();
			// return the matching template name
			if (!empty($row))
			{
				return $row['template_name'];
			}
		}
		return null; 
	}

	// get template id of a user by user_id
	public static function get_template_id($gti_user)
	{
		global $sql;	
        if ($result = $sql->query("SELECT user_template from bereso_user WHERE user_name='$gti_user'"))
		{
			$row = $result -> fetch_assoc();
			// return the matching template id
			if (!empty($row))
			{
				return $row['user_template'];
			}
		}
		return "0"; // 0 for not logged in users - system default value
	}
	
	// check if logged in
	public static function is_logged_in($ili_user,$ili_passwordhash)
	{
		global $sql;		
        if ($result = $sql->query("SELECT user_name, user_pwhash from bereso_user WHERE user_name='$ili_user'"))
		{
			$row = $result -> fetch_assoc();
			// check if user exists and password matches hashed password
			if (!empty($row) && $row['user_pwhash'] == $ili_passwordhash) // not empty and pw hash matches
			{
				return true;
				
			}
			else 
			{
				return false;
			}
		}                		
	}
	
	// Hash password  and return value
	public static function generate_password_hash($gph_password)
	{
		return password_hash($gph_password,PASSWORD_DEFAULT);
	}

	// get user_id by user_name
	public static function get_id_by_name($gibn_user)
	{
		global $sql;
        if ($result = $sql->query("SELECT user_id from bereso_user WHERE user_name='$gibn_user'"))
		{
			$row = $result -> fetch_assoc();
			
			// check if user exists and return id
			if (!empty($row))
			{
				return $row['user_id'];
			}
			else 
			{
				return "0";
			}
		}                		
	}	
	
	
	// get user_name by user_id
	public static function get_name_by_id($gnbi_user)
	{
		global $sql;
        if ($result = $sql->query("SELECT user_name from bereso_user WHERE user_id='$gnbi_user'"))
		{
			$row = $result -> fetch_assoc();
			
			// check if user exists and return name
			if (!empty($row))
			{
				return $row['user_name'];
			}
			else 
			{
				return "0";
			}
		}                		
	}		

	// get last listed tag for this user
	public static function get_last_list($gllt_user)
	{
		global $sql;
        if ($result = $sql->query("SELECT user_last_list from bereso_user WHERE user_name='$gllt_user'"))
		{
			$row = $result -> fetch_assoc();
			return $row['user_last_list'];
		}
	}

	// set last listed tag for this user
	public static function set_last_list($sllt_user,$sllt_tag)
	{
		global $sql;
        $sql->query("UPDATE bereso_user SET user_last_list='".$sllt_tag."' WHERE user_name='$sllt_user'");
	}
}
?>