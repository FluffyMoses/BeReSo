<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Class tags functions
// included by ../index.php
// ###################################

class Tags 
{
	// check if taggroup is owned by user
	public static function is_owned_by_user($iobu_user,$iobu_taggroup)
	{
		global $sql;
		$userid = User::get_id_by_name($iobu_user); 
        if ($result = $sql->query("SELECT group_name from bereso_group WHERE group_user='$userid' and group_name='$iobu_taggroup'"))
		{
			$row = $result -> fetch_assoc();
			
			// check if taggroup is owned by user
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


	// check if taggroup exists
	public static function is_taggroup($it_user,$it_name)
	{
		global $sql;		
		$userid = User::get_id_by_name($it_user);
        if ($result = $sql->query("SELECT group_name from bereso_group WHERE group_name='$it_name' AND group_user='".$userid."'"))
		{			
			// if one or more entrys are detected for this user -> group exists -> return true
			if (mysqli_num_rows($result) > 0) { return true; } else { return false; }
		}   
	}


	// get taggroup id by name
	public static function get_taggroup_id($gti_user,$gti_name)
	{
		global $sql;		
		$userid = User::get_id_by_name($gti_user);
        if ($result = $sql->query("SELECT group_id from bereso_group WHERE group_name='$gti_name' AND group_user='".$userid."'"))
		{			
			$row = $result -> fetch_assoc();
			return $row['group_id'];
		}   
	}


	// get taggroup name by id
	public static function get_taggroupid_name($gtn_user,$gtn_taggroupid)
	{
		global $sql;		
		$userid = User::get_id_by_name($gtn_user);
        if ($result = $sql->query("SELECT group_name from bereso_group WHERE group_id='$gtn_taggroupid' AND group_user='".$userid."'"))
		{			
			$row = $result -> fetch_assoc();
			return $row['group_name'];
		}   
	}


	// get all hashtags that are inside a taggroup text and return them comma seperated, without the # and in ''
	public static function get_taggroup_hashtags_csv($gtti_user,$gtti_name)
	{
		global $sql;		
		$userid = User::get_id_by_name($gtti_user);
        if ($result = $sql->query("SELECT group_text from bereso_group WHERE group_name='$gtti_name' AND group_user='".$userid."'"))
		{
			$row = $result -> fetch_assoc();
			// scan for hashtags
			$hashtag_list = null;
			preg_match_all("/(#\w+)/", $row['group_text'], $matches);
			for ($i=0;$i<count($matches[0]);$i++)
			{
				$hashtag_list .= "'".str_replace("#","",$matches[0][$i])."',";
			}	
			// hashtags exist
			if (strlen($hashtag_list) > 0) {
				return substr($hashtag_list,0,strlen($hashtag_list)-1); // delete the last ,
			}
			// no hashtags exist -> return '#' (will result in an emtpty sql query)
			else
			{
				return "'#'";
			}
		}   
	}


	// check if tag is part of a taggroup for this user
	public static function is_tag_in_taggroup($itit_user,$itit_hashtag)
	{
		global $sql;		
		$userid = User::get_id_by_name($itit_user);
		// list all tag groups that contain %#$itit_hashtag%
        if ($result = $sql->query("SELECT group_text from bereso_group WHERE group_text LIKE '%#$itit_hashtag%' AND group_user='".$userid."'"))
		{
			while ($row = $result -> fetch_assoc())
			{
				// need to check again with the preg_match_all cause the like sql query returns all hashtags beginning with $taggroup too
				preg_match_all("/(#\w+)/", $row['group_text'], $matches);
				for ($i=0;$i<count($matches[0]);$i++)
				{
					if (strtolower($matches[0][$i]) == strtolower("#".$itit_hashtag)) { return true; } // found the exact hashtag in one of the taggroups => return true
					
				}				
			}
		}   
		return false; // returns false if hashtag is not found in one of the taggroups
	}
}
?>