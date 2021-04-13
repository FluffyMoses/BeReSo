// Bereso
// BEst REcipe SOftware
// ###################################
// http
// included by ../main.html
// ###################################

function get_http_request (url)
{
	const http = new XMLHttpRequest();
	http.open("GET", url);
	http.send();

	/* Log to console */
	http.onreadystatechange = (e) => {
	  console.log(http.responseText)
	}
}