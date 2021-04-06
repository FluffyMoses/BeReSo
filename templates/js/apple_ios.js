// Bereso
// BEst REcipe SOftware
// ###################################
// apple ios
// included by ../main.html
// ###################################

// Mobile Safari in standalone mode
if (("standalone" in window.navigator) && window.navigator.standalone) {

	// If you want to prevent remote links in standalone web apps opening Mobile Safari, change 'remotes' to true
	var noddy, remotes = false;

	document.addEventListener('click', function (event) {

		noddy = event.target;

		while (noddy.nodeName !== "A" && noddy.nodeName !== "HTML") {
			noddy = noddy.parentNode;
		}

		if ('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes)) {
			event.preventDefault();
			document.location.href = noddy.href;
		}

	}, false);
}
