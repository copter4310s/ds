function hidetbconn() {
	document.getElementById("tbconn").style.display = "none";
	document.getElementById("blockanother").style.display = "none";
}

function hidetbincome() {
	document.getElementById("tbincome").style.display = "none";
	document.getElementById("blockanother").style.display = "none";
}

function hidetbsource() {
	document.getElementById("tbinsource").style.display = "none";
	document.getElementById("blockanother").style.display = "none";
}

function waitGraph() {
	loadGraph();
}

function showavalible() {
	document.getElementById("adminlogin").style.display = "inline-table";
	document.getElementById("maintb").style.display = "none";
}

function hideavalible() {
	document.getElementById("adminlogin").style.display = "none";
	document.getElementById("maintb").style.display = "inline-table";
}
