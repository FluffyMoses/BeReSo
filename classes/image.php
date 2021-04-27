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

	// check if image metainformations are stored in database
	public static function image_in_database($iid_filename)
	{
		// get filename and image_id - example filename 601ba3f3a86fa_0.png
		$explode_file = explode(".",$iid_filename); 
		if (count($explode_file) == 2) {
			$extension = $explode_file[1];
			$explode_file2 = explode("_",$explode_file[0]);
			if (count($explode_file2) == 2)
			{
				$filename = $explode_file2[0];
				$image_id = $explode_file2[1];
				if (Image::get_filenamecomplete(Image::get_itemid_by_filename($filename),$image_id) == $iid_filename) // meta informations found
				{
					return true;
				}
			}
		}
		// no meta informations found
		return false;
	}

	// rotate image
	public static function rotate($r_imagepath,$r_image_rotate_degrees)
	{
		// Load Jpg or Png
		if (Image::get_header_fileextension($r_imagepath) == ".jpg") {
			$load_image = imagecreatefromjpeg($r_imagepath);
			// rotate
			$rotate_image = imagerotate($load_image, $r_image_rotate_degrees, 0);
			imagejpeg($rotate_image,$r_imagepath);
		}
		elseif (Image::get_header_fileextension($r_imagepath) == ".png")
		{
			$load_image = imagecreatefrompng($r_imagepath);
			// rotate
			$rotate_image = imagerotate($load_image, $r_image_rotate_degrees, 0);
			imagepng($rotate_image,$r_imagepath);
		} 
		else {
			Log::die ("CHECK: image rotate($image_path) - no jpg or png ");
		}
		imagedestroy($load_image);
		imagedestroy($rotate_image);
	}

	// return exif orientation degrees
	public static function get_exif_orientation($geo_imagepath)
	{
		 $exif = exif_read_data($geo_imagepath);
		 if (empty($exif['Orientation'])) { return 0; }
		 if ($exif['Orientation'] == 3) { return 180; }
		 elseif ($exif['Orientation'] == 6) { return -90; }
		 elseif ($exif['Orientation'] == 8) { return 90; }
		 else { return 0; }
	}

	// get item id by filename 
	public static function get_itemid_by_filename($gibf_filename)
	{
		global $sql;
		if ($result = $sql->query("SELECT item_id from bereso_item WHERE item_imagename='".$gibf_filename."'"))
		{		
			$row = $result -> fetch_assoc();
			if ($row['item_id'] != null) { return $row['item_id']; }
		}
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
		if ($gfbui_user_id > 0 && is_numeric($gfbui_user_id))
		{
			return $bereso['images'] . $gfbui_user_id . "/";
		}
		else
		{
			return "ERROR";
		}
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