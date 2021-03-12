// Add text to textarea at cursor position
function insertAtCursor(valuetarget, valuesource) {
	document.getElementById(valuetarget).focus();
	document.execCommand('insertText', false, document.getElementById(valuesource).value);
}
