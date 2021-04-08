<?php
// Bereso
// BEst REcipe SOftware
// ###################################
// Delete taggroup
// included by ../index.php
// ###################################


// check if user is owner of this taggroup
if (Tags::is_owned_by_user($user,Tags::get_taggroupid_name($user,$taggroupid))) {
	// Delete taggroup

	// show double check
	if ($action == null)
	{
		// load template
		$content = File::read_file("templates/delete_taggroup.html");
		$content = str_replace("(bereso_delete_taggroup)",Tags::get_taggroupid_name($user,$taggroupid),$content);			
		$content = str_replace("(bereso_delete_taggroupid)",$taggroupid,$content);			
	}

	// double check successfull => really delete the taggroup
	if ($action == "confirm")
	{
		$userid = User::get_id_by_name($user); 
		// delete SQL entrys
		$sql->query("DELETE FROM bereso_group where group_id='".$taggroupid."' AND group_user='".$userid."'");
		
		header('Location: index.php', true, 302); // Redirect to the startpage
		exit(); // stops the rest of the script from running 
	}
}
else
{
	Log::die ("CHECK: delete taggroup owner failed");
}

?>