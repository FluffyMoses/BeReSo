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


    // get config from database
	public static function get_config($gc_name)
	{
		global $sql;
        if ($result = $sql->query("SELECT config_value from bereso_config WHERE config_name='$gc_name'"))
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

	// set item favorite status true/false
	public static function set_config($sc_name,$sc_value)
	{
		global $sql;		
        $sql->query("UPDATE bereso_config SET config_value='".$sc_value."' WHERE config_name='$sc_name'");
	}

}
?>