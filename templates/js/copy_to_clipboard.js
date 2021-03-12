// Copy to clipboard
function copyToClipboard(text) {
	var dummy = document.createElement("textarea");
	document.body.appendChild(dummy);
	dummy.textContent = text;
	dummy.select();
	document.execCommand("copy");
	document.body.removeChild(dummy);
}