<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class image functions
// included by ../index.php
// ###################################

class Image 
{

	// Return file extension based on MIME Type of the file (Images) - with or without prefix "." true(default)/false 
	public static function get_header_fileextension($ge_path, $prefix = true)
	{
		$filetype = getimagesize($ge_path);
		if (@$filetype['mime'] == "image/jpeg") { $extension = "jpg"; }
		elseif (@$filetype['mime'] == "image/png") { $extension = "png"; }
		else { $extension = "unknown"; } // not a wanted file format => error
		// return extension
		if ($prefix == true) { $add_prefix = "."; } else { $add_prefix = null; }
		return $add_prefix . $extension;
	}
	
	// build image filename with extension from database based on item id and image id
	public static function get_filenamecomplete($gf_item_id, $gf_image_id)
	{
		global $sql;
		if ($result = $sql->query("SELECT item_imagename from bereso_item WHERE item_id='".$gf_item_id."'"))
		{	
			$row = $result -> fetch_assoc();
			// if entry with this gf_item_id exists
			if (mysqli_num_rows($result) == 1)
			{			
				if ($result2 = $sql->query("SELECT images_image_id, images_fileextension from bereso_images WHERE images_item='".$gf_item_id."' AND images_image_id='".$gf_image_id."'"))
				{	
					$row2 = $result2 -> fetch_assoc();
					// if entry with this gf_item_id and $gf_image_id exists
					if (mysqli_num_rows($result2) == 1)
					{			
						if ($row['item_imagename'] != null) 
						{
							return $row['item_imagename']."_".$row2['images_image_id'].".".$row2['images_fileextension']; // return filename
						}
					}
				}
			}

		}
		return null; // no entry found
	}

	// build image filename from database based on item id 
	public static function get_filename($gf_item_id)
	{
		global $sql;
		if ($result = $sql->query("SELECT item_imagename from bereso_item WHERE item_id='".$gf_item_id."'"))
		{	
			$row = $result -> fetch_assoc();
			if ($row['item_imagename'] != null) { return $row['item_imagename']; }
		}
		return null; // no entry found
	}

	// return fileextension from database based on item id and image id - with or without prefix "." true(default)/false 
	public static function get_fileextension($gf_item_id, $gf_image_id, $prefix = true)
	{
		global $sql;
		
		if ($result = $sql->query("SELECT images_fileextension from bereso_images WHERE images_item='".$gf_item_id."' AND images_image_id='".$gf_image_id."'"))
		{	
			$row = $result -> fetch_assoc();
			if ($row['images_fileextension'] != null) 
			{
				if ($prefix == true) { $add_prefix = "."; } else { $add_prefix = null; }
				return $add_prefix.$row['images_fileextension']; // return fileextension
			}
		}

		return null; // no entry found
	}	

	// return image foldername by user id
	public static function get_foldername_by_user_id($gfbui_user_id)
	{
		global $bereso;
		return $bereso['images'] . $gfbui_user_id . "/";
	}

	// return image foldername by share id
	public static function get_foldername_by_shareid($gfbs_shareid)
	{
		global $bereso, $sql;
		
		if ($result = $sql->query("SELECT item_user from bereso_item WHERE item_shareid='".$gfbs_shareid."'"))
		{	
			$row = $result -> fetch_assoc();
			return $bereso['images'] . $row['item_user'] . "/";
		}
	}

}
?>