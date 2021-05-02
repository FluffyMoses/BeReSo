// Bereso
// BEst REcipe SOftware
// ###################################
// textarea insert
// included by ../main.html
// ###################################

// Add text to textarea at cursor position
function insertAtCursor(valuetarget, valuesource) {
	const selection_start_position = document.getElementById(valuetarget).selectionStart;
	const selection_end_position = document.getElementById(valuetarget).selectionEnd;	
	document.getElementById(valuetarget).value = document.getElementById(valuetarget).value.slice(0, selection_start_position) + document.getElementById(valuesource).value + document.getElementById(valuetarget).value.slice(selection_end_position);
	document.getElementById(valuetarget).focus();
    document.getElementById(valuetarget).setSelectionRange(selection_start_position+document.getElementById(valuesource).value.length, selection_start_position+document.getElementById(valuesource).value.length); // set cursor position at the end of the added hashtag	
}

// Add text to textarea before and after selection
function insertbeginendselection(valuetarget, beginn, end) {
	const selection_start_position = document.getElementById(valuetarget).selectionStart;
	const selection_end_position = document.getElementById(valuetarget).selectionEnd;
    document.getElementById(valuetarget).value = document.getElementById(valuetarget).value.substring(0, selection_start_position)+ beginn+ document.getElementById(valuetarget).value.substring(selection_start_position, selection_end_position)+ end+ document.getElementById(valuetarget).value.substring(selection_end_position, document.getElementById(valuetarget).value.length);
}
