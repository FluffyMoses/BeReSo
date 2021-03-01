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

}
?>