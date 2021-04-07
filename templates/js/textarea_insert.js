// Bereso
// BEst REcipe SOftware
// ###################################
// textarea insert
// included by ../main.html
// ###################################

// Add text to textarea at cursor position
function insertAtCursor(valuetarget, valuesource) {
	// "new" version that also should work with firefox
	const insertvalue = document.getElementById(valuetarget).value;
	const selection_start_position = document.getElementById(valuetarget).selectionStart;
	const selection_end_position = document.getElementById(valuetarget).selectionEnd;
	document.getElementById(valuetarget).value = document.getElementById(valuetarget).value.slice(0, selection_start_position) + document.getElementById(valuesource).value + document.getElementById(valuetarget).value.slice(selection_end_position);

	/* old version - doesn't work with firefox:
	document.getElementById(valuetarget).focus();
	document.execCommand('insertText', false, document.getElementById(valuesource).value);
	*/
}
