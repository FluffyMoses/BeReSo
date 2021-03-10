<?php

// includes
include "config.php";
include("classes/image.php");
include("classes/log.php");

// SQL connection
$sql = new mysqli($bereso['sql']['host'],$bereso['sql']['user'],$bereso['sql']['password'],$bereso['sql']['database']); 
if (mysqli_connect_errno()) {
    Log::die("DEBUG_move_images_to_user_folder.php - Connect failed: " . mysqli_connect_error()); // log problems with SQL Connection
}
$sql->query("SET NAMES 'utf8'"); // UTF8 DB Setting

$user_id = @$_GET['user_id'];

if ($user_id > 0 && is_numeric($user_id))
{
	$sql_list_items = "SELECT item_id, item_name, item_imagename from bereso_item WHERE item_user='".$user_id."'"; 
	if ($result = $sql->query($sql_list_items))
	{	
		while ($row = $result -> fetch_assoc())
		{
			echo "<b>Renaming Item ".$row['item_id']." (".$row['item_name']."): ".$row['item_imagename']."</b><br>";
			if ($result2 = $sql->query("SELECT images_image_id from bereso_images WHERE images_item='".$row['item_id']."'")) 
			{	
				while ($row2 = $result2 -> fetch_assoc())
				{
					$status = null;
					$old_file = $bereso['images'].Image::get_filenamecomplete($row['item_id'],$row2['images_image_id']);
					$new_file = Image::get_foldername_by_user_id($user_id).Image::get_filenamecomplete($row['item_id'],$row2['images_image_id']);
					rename ($old_file,$new_file);
					if (file_exists($new_file)) { $status = "<font color=\"green\">sucess"; } else { $status = "<font color=\"red\">failed"; }
					echo "$status</font> renaming file: " . $old_file . " to " . $new_file . "<br>";
				}
			}
		}
	}
}
else
{
	echo "set DEBUG_move_images_to_user_folder.php?user_id=USER_ID to start migration of all file that belongs to this user!";	
}


$sql->close();
?>