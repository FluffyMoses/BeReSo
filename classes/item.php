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

	// set item ocr text
	public static function set_ocr_text($sot_item_id,$sot_text)
	{
		global $sql;		
		// if text is null write true null into the table
		if ($sot_text == null) 
		{
			$sql->query("UPDATE bereso_item SET item_ocr_text=NULL WHERE item_id='$sot_item_id'");
		}
		else // no null - write text
		{
			$sql->query("UPDATE bereso_item SET item_ocr_text='".$sot_text."' WHERE item_id='$sot_item_id'");
		}
	}

	// return ocr by item id
	public static function get_ocr($go_item_id)
	{
		global $sql;		
        if ($result = $sql->query("SELECT item_ocr from bereso_item WHERE item_id='$go_item_id'"))
		{
			$row = $result -> fetch_assoc();
			// check if item exists
			if ($row['item_ocr'] == 1) 
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	// set item rating
	public static function set_rating($sr_item_id,$sr_rating)
	{
		global $sql;		
		$sql->query("UPDATE bereso_item SET item_rating='".$sr_rating."' WHERE item_id='$sr_item_id'");
	}

	// return rating by item id
	public static function get_rating($gr_item_id)
	{
		global $sql;		
        if ($result = $sql->query("SELECT item_rating from bereso_item WHERE item_id='$gr_item_id'"))
		{
			$row = $result -> fetch_assoc();
			// check if item exists
			if (@is_numeric($row['item_rating']))
			{
				return $row['item_rating'];
			}
			else
			{
				return 0;
			}
		}
	}

	// set item ocr status true/false
	public static function set_ocr($so_item_id,$so_status)
	{
		global $sql;		
		if ($so_status == true) { $set_status = 1; } else { $set_status = 0; } // status variable for db entry		
        $sql->query("UPDATE bereso_item SET item_ocr='".$set_status."' WHERE item_id='$so_item_id'");
	}

	// return ocr searchable by item id
	public static function get_ocr_searchable($gos_item_id)
	{
		global $sql;		
        if ($result = $sql->query("SELECT item_ocr_searchable from bereso_item WHERE item_id='$gos_item_id'"))
		{
			$row = $result -> fetch_assoc();
			// check if item exists
			if ($row['item_ocr_searchable'] == 1) 
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	// set item ocr searchable true/false
	public static function set_ocr_searchable($sos_item_id,$sos_status)
	{
		global $sql;		
		if ($sos_status == true) { $set_status = 1; } else { $set_status = 0; } // status variable for db entry		
        $sql->query("UPDATE bereso_item SET item_ocr_searchable='".$set_status."' WHERE item_id='$sos_item_id'");
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

	// get number of ocr items of user
	public static function get_ocrnumber($gs_user) 
	{
		global $sql;
        if ($result = $sql->query("SELECT * from bereso_item WHERE item_user='".User::get_id_by_name($gs_user)."' AND item_ocr = '1'"))
		return mysqli_num_rows($result);
	}	

	// get number of rated items of user
	public static function get_ratednumber($gs_user) 
	{
		global $sql;
        if ($result = $sql->query("SELECT * from bereso_item WHERE item_user='".User::get_id_by_name($gs_user)."' AND item_rating > '0'"))
		return mysqli_num_rows($result);
	}	
}
?>