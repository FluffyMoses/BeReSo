<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class log functions
// included by ../index.php
// ###################################

class Log 
{

	// die logging - log if $bereso['log_die'] == true and die() 
	public static function die($d_logtext,$d_loguserid = true)
	{
		global $bereso,$user;
		if ($d_loguserid == true) // when userid should be logged
		{
			$userid = User::get_id_by_name($user);
		}
		else // do not check user id - for example when sql connection problems should be logged
		{
			$userid = 0;
		}
		$full_logtext = Time::timestamp_to_datetime($bereso['now']) . " [".$bereso['title']." ".$bereso['version'] . "] - " . $_SERVER['REMOTE_ADDR'] . " - User ID: " . $userid . " - " . $_SERVER['REQUEST_URI'] . " - " . $d_logtext;
		if ($bereso['log_die'] == true) { File::append_file($bereso['log_die_path'],$full_logtext); } // log to textfile if enabled
		die($full_logtext); // end script and output $d_logtext
	}


	// log user actions - only if enabled in database config - user_log == 1
	public static function useraction($u_user, $u_module, $u_action, $u_text)
	{
		global $bereso, $sql; 
		// check if logging of user actions is enabled
		if (Config::get_config("user_log") == 1) 
		{
			$sql->query("INSERT INTO bereso_log (log_user, log_timestamp, log_datetime, log_module, log_action, log_text, log_clientip) VALUES ('".User::get_id_by_name($u_user)."','".$bereso['now']."','".Time::timestamp_to_datetime($bereso['now'])."','".$u_module."','".$u_action."','".$u_text."','".$_SERVER['REMOTE_ADDR']."')");
		}
	}


	// log agent ocr actions - only if enabled in database config - agent_ocr_log == 1
	public static function agentocr($a_action, $a_text)
	{
		global $bereso, $sql; 
		// check if logging of agent ocr actions is enabled
		if (Config::get_config("agent_ocr_log") == 1) 
		{
			$sql->query("INSERT INTO bereso_log (log_user, log_timestamp, log_datetime, log_module, log_action, log_text, log_clientip) VALUES ('0','".$bereso['now']."','".Time::timestamp_to_datetime($bereso['now'])."','agent_ocr','".$a_action."','".$a_text."','".$_SERVER['REMOTE_ADDR']."')");
		}
	}
}
?>