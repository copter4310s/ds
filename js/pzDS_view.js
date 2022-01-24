function submitCustomcom() {
	var customcom = document.getElementById("customcoms");
	var customcoms = document.getElementById("customcoms");
	customcom.value = customcom.value.replace("\"", "(u201D");
	
	customcoms.classList.add("readonly");
	customcoms.readOnly = true;
	
	document.getElementById("formcustomcom").submit();
}

function hidePrintbtn() {
	document.getElementById("noprint").style.display = "none";
}

function showPrintbtn() {
	document.getElementById("noprint").style.display = "inline";
}
