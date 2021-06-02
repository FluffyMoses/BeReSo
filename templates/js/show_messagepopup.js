// Bereso
// BEst REcipe SOftware
// ###################################
// shows messagepopup for a few seconds (status messages: success and error)
// included by ../../index.php (via new.php, edit.php, etc.)
// ###################################

var mpu = document.getElementById("messagepopup");
mpu.className = "show";
setTimeout(function(){ mpu.className = mpu.className.replace("show", ""); }, 5000);	