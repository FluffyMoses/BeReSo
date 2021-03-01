<?php
// List alle files in $directory and generates sql inserts for the new table

// config
$directory = "images/";
include "config.php";

// SQL connection
$sql = new mysqli($bereso['sql']['host'],$bereso['sql']['user'],$bereso['sql']['password'],$bereso['sql']['database']); 
if (mysqli_connect_errno()) {
    Log::die("Connect failed: " . mysqli_connect_error()); // log problems with SQL Connection
}
$sql->query("SET NAMES 'utf8'"); // UTF8 DB Setting

$files = scandir($directory);
$count = 0;


foreach ($files as $key => $value) {
	if ($value != "." or $value != ".."){
		$imagename = null;
		$imagename = explode(".",$value);
		$imagename_split = explode("_",$imagename[0]);
		if ($result = $sql->query("select item_id, item_imagename from bereso_item where item_imagename='".$imagename_split[0]."'"))
		{	
			while ($row = $result -> fetch_assoc())
			{
				echo "INSERT INTO bereso_images (images_item, images_image_id, images_fileextension) VALUES ('".$row['item_id']."','".$imagename_split[1]."','".$imagename[1]."');<br>";
				//echo $row['item_imagename']." - ID: " . $imagename_split[1] . "<br>";
 				$count++;
			}
		}


	}
}

$sql->close();
echo "<br><br>--Files: $count";
?>