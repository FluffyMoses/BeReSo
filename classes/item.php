<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class item functions
// included by ../index.php
// ###################################

class Item 
{

	// check if item is owned by user
	public static function is_owned_by_user($iobu_user,$iobu_item_id)
	{
		global $sql;
		$userid = User::get_id_by_name($iobu_user); 
        if ($result = $sql->query("SELECT item_name from bereso_item WHERE item_user='$userid' and item_id='$iobu_item_id'"))
		{
			$row = $result -> fetch_assoc();
			
			// check if item is owned by user
			if (!empty($row))
			{
				return true;
				
			}
			else 
			{
				return false;
			}
		}                		
	}	

	// return ocr text by item id
	public static function get_ocr_text($got_item_id)
	{
		global $sql;		
        if ($result = $sql->query("SELECT item_ocr_text from bereso_item WHERE item_id='$got_item_id'"))
		{
			$row = $result -> fetch_assoc();
			// check if item exists
			if (!empty($row))
			{
				return $row['item_ocr_text'];
			}
			else
			{
				return null;
			}
		}
	}


	// set item favorite status true/false
	public static function set_favorite($sf_item_id,$sf_status)
	{
		global $sql;		
		if ($sf_status == true) { $set_status = 1; } else { $set_status = 0; } // status variable for db entry		
        $sql->query("UPDATE bereso_item SET item_favorite='".$set_status."' WHERE item_id='$sf_item_id'");
	}

	// get favorite status true/false
	public static function get_favorite($gf_item_id)
	{
		global $sql;		
        if ($result = $sql->query("SELECT item_favorite from bereso_item WHERE item_id='$gf_item_id'"))
		{
			$row = $result -> fetch_assoc();
			if ($row['item_favorite'] == 1) 
			{
				return true;
			}
			else
			{
				return false;
			}
		}                		
	}	


	// get item share id
	public static function get_share_id($gsi_item_id)
	{
		global $sql;		
        if ($result = $sql->query("SELECT item_shareid from bereso_item WHERE item_id='$gsi_item_id'"))
		{
			$row = $result -> fetch_assoc();
			return $row['item_shareid']; // return item_shareid (empty or not)
		}                		
	}	

	// get name of item by id
	public static function get_name($gn_id) 
	{
		global $sql;
        if ($result = $sql->query("SELECT item_name from bereso_item WHERE item_id='".$gn_id."'"))
		$row = $result -> fetch_assoc();
		return $row['item_name'];
	}			
		
	
	// get number of items of user
	public static function get_number($gn_user) 
	{
		global $sql;
        if ($result = $sql->query("SELECT * from bereso_item WHERE item_user='".User::get_id_by_name($gn_user)."'"))
		return mysqli_num_rows($result);
	}		

	// get number of items of user tag
	public static function get_number_by_tag($gnbt_tag_name,$gnbti_user) 
	{
		global $sql;
        if ($result = $sql->query("SELECT * from bereso_tags  INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_tags.tags_name='$gnbt_tag_name' AND bereso_item.item_user='".User::get_id_by_name($gnbti_user)."'"))
		return mysqli_num_rows($result);
	}		

	// get number of shared items of user
	public static function get_sharednumber($gs_user) 
	{
		global $sql;
        if ($result = $sql->query("SELECT * from bereso_item WHERE item_user='".User::get_id_by_name($gs_user)."' AND LENGTH(item_shareid) > 0"))
		return mysqli_num_rows($result);
	}	
	
	// get number of favorite items of user
	public static function get_favoritenumber($gs_user) 
	{
		global $sql;
        if ($result = $sql->query("SELECT * from bereso_item WHERE item_user='".User::get_id_by_name($gs_user)."' AND item_favorite = '1'"))
		return mysqli_num_rows($result);
	}		
}
?>