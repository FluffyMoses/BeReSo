<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class config functions
// included by ../index.php
// ###################################

class Config 
{
    // read php.ini value and convert to bytes (values with sizes "K","M" or "G")
    public static function get_phpini_convertedsize($gpc_config)
    {
        $config_value = ini_get($gpc_config); // read php config
        if (substr($config_value,strlen($config_value)-1,1) == "K") // convert when KB
        {
            $config_value = substr($config_value,0,strlen($config_value)-1);
            $config_value = $config_value * 1024; // bytes
        }
        elseif (substr($config_value,strlen($config_value)-1,1) == "M") // convert when MB
        {
            $config_value = substr($config_value,0,strlen($config_value)-1);
            $config_value = $config_value * 1024 * 1024; // bytes
        }
        elseif (substr($config_value,strlen($config_value)-1,1) == "G") // convert when MB
        {
            $config_value = substr($config_value,0,strlen($config_value)-1);
            $config_value = $config_value * 1024 * 1024 * 1024; // bytes
        }
        return $config_value;
    }


    // get system config from database (user id == 0)
	public static function get_config($gc_name)
	{
		global $sql;
        if ($result = $sql->query("SELECT config_value from bereso_config WHERE config_name='$gc_name' AND config_user='0'"))
		{
			$row = $result -> fetch_assoc();
			
			// check if value exists
			if (!empty($row))
			{
				return $row['config_value'];
				
			}
			else 
			{
				return "ERROR_MISSING_CONFIG";
			}
		}                		
	}	

	// set system config (user id == 0)
	public static function set_config($sc_name,$sc_value)
	{
		global $sql;		
        $sql->query("UPDATE bereso_config SET config_value='".$sc_value."' WHERE config_name='$sc_name' AND config_user='0'");
	}


    // get user config from database - set default value if not exists
	public static function get_userconfig($gu_name,$gu_user)
	{
		global $sql,$bereso,$module,$action;
		$userid = User::get_id_by_name($gu_user); 
        if ($result = $sql->query("SELECT config_value from bereso_config WHERE config_name='$gu_name' AND config_user='".$userid."'"))
		{
			$row = $result -> fetch_assoc();
			
			// check if value exists
			if (!empty($row))
			{
				return $row['config_value'];								
			}
			else // entry does not exist - set default value
			{
				$sql->query("INSERT INTO bereso_config (config_name, config_value, config_user) VALUES ('".$gu_name."','".$bereso[$gu_name]."','".$userid."')");
				Log::useraction($gu_user,$module,$action,"Set default user value $gu_name (".$bereso[$gu_name].")");
				return $bereso[$gu_name];
			}
		}                		
	}	

	// set user config
	public static function set_userconfig($su_name,$su_value,$su_user)
	{
		global $sql;		
		$userid = User::get_id_by_name($su_user); 
        $sql->query("UPDATE bereso_config SET config_value='".$su_value."' WHERE config_name='$su_name' AND config_user='".$userid."'");
	}

}
?>