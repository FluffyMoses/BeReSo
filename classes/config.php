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
}
?>