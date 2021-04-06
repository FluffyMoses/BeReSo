<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class file functions
// included by ../index.php
// ###################################

class File 
{

    // read file and return its content
    public static function read_file($rf_path)
    {
        $file = file($rf_path);
        $file_content = null;

        foreach($file AS $file_line)
        {
            $file_content .= $file_line;
        }
        return $file_content;   
    }

    // append a line with text to a file
    public static function append_file($af_path,$af_text)
    {
        $file = fopen($af_path, 'a');
        fwrite($file, $af_text."\r\n");
    }

    // returns size of all files in this directory - not recursive!
    public static function get_directorysize($gd_path,$gd_format = "B")
    {
        $directorysize = 0;
        $files = scandir($gd_path);
        foreach ($files as $file => $value)
        {           
            // only files inside this directory
            if (!is_dir($gd_path.$value))
            {    
                $directorysize = $directorysize + filesize($gd_path.$value);
            }
        }
        // convert it to $gd_format B = Byte DEFAULT | KB = Kilobyte (1024 Byte) | MB = Megabyte (1024 Kilobyte) | GB = Gigabyte (1024 Megabyte)
        if ($gd_format == "KB") { $directorysize = $directorysize / 1024; }
        elseif ($gd_format == "MB") { $directorysize = $directorysize / 1024 / 1024; }
        elseif ($gd_format == "GB") { $directorysize = $directorysize / 1024 / 1024 / 1024; }
        return round($directorysize,2); // round directorysize
    }

    // delete directory - delete all files in the folder and the folder
    public static function delete_directory($dd_path)
    {
        $files = scandir($dd_path); // scan for files in directory
        foreach ($files as $file => $value)
        {           
            // only files inside this directory
            if (!is_dir($dd_path.$value))
            {    
                unlink($dd_path.$value); // delete file
            }
        }
        rmdir($dd_path); // delete folder
    }
}
?>