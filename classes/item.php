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
	public static function get_number_by_tag_id($gnbti_tag_name,$gnbti_user) 
	{
		global $sql;
        if ($result = $sql->query("SELECT * from bereso_tags  INNER JOIN bereso_item ON bereso_tags.tags_item = bereso_item.item_id WHERE bereso_tags.tags_name='$gnbti_tag_name' AND bereso_item.item_user='".User::get_id_by_name($gnbti_user)."'"))
		return mysqli_num_rows($result);
	}		

	// get number of shared items of user
	public static function get_sharednumber($gs_user) 
	{
		global $sql;
        if ($result = $sql->query("SELECT * from bereso_item WHERE item_user='".User::get_id_by_name($gs_user)."' AND LENGTH(item_shareid) > 0"))
		return mysqli_num_rows($result);
	}		
}
?>