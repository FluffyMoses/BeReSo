// Bereso
// BEst REcipe SOftware
// ###################################
// shows waitpopup until page is reloaded (while waiting for uploads and saving)
// included by ../../index.php (via new.php, edit.php, etc.)
// ###################################
function show_waitpopup()
{
	var wpu = document.getElementById("waitpopup");
	wpu.className = "show";
}
