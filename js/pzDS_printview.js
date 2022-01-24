var timerA;

//FIRST CALLED
function doPrint() {
	timerA = setTimeout(showPrint, 100);
}

//SECOND CALLED
function showPrint() {
	if (isMobileDevice() == true){
		showprintinfo();
	} else {
		$("#modalprintSettingDesktop").modal();
	}
}

//THIRD CALLED (MOBILE PRINT)
function showprintinfo() {
	hidePrintbtn();
	$("#modalprintSettingMobile").modal();
}

//FORTH CALLED (DESKTOP PRINT)
function doPrintDesktop() {
	hidePrintbtn();
	timerA = setTimeout(showPrintDialog, 500);
}

//FORTH CALLED (MOBILE PRINT)
function doPrintMobile() {
	hidePrintbtn();
	timerA = setTimeout(showPrintDialog, 500);
}

//FIFTH CALLED
function showPrintDialog() {
	hideSettings();
	window.print();

	setTimeout(showPrintbtn, 650);
}

function showlogin() {
	try {
		document.getElementById('noprint').style.display = 'none';	
	} catch {

	}
	document.getElementById("adminlogin").style.display = "inline-table";
	/*document.getElementById("maintb").style.display = "none";*/
}

function hidelogin() {
	document.getElementById("adminlogin").style.display = "none";
	document.getElementById("maintb").style.display = "inline-table";
	try {
		document.getElementById('noprint').style.display = 'block';	
	} catch {

	}
}

function checkloadgraph() {
    document.getElementById("loadgraph").checked = true;
}

function uncheckloadgraph() {
    document.getElementById("loadgraph").checked = false;
}

function isMobileDevice() {
    return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
};

function showSettings() {
	//CHECK IF THIS SESSION IS LOAD GRAPH THEN SET CHECKBOX, BEFORE SHOW SETTINGS
	var graphloadhere = document.getElementById("graphloadhere");
	if (graphloadhere) {
		document.getElementById("loadgraph").checked = true;
	} else {
		document.getElementById("loadgraph").checked = false;
	}

	//CHECK IF THIS SESSION ARE USED FOR ALL MONTH REPORT, LOAD GRAPH MONTH THEN SET CHECKBOX, BEFORE SHOW SETTINGS
	var loadgraphmonth = document.getElementById("loadgraphmonth");
	var allmonthgraph = document.getElementById("allmonthgraph");
	if (loadgraphmonth) {
		if (allmonthgraph) {
			document.getElementById("loadgraphmonth").checked = true;
		} else {
			document.getElementById("loadgraphmonth").checked = false;
		}
	} else {
		//DO NOTHING
	}

	$("#showSettings").modal();
}

function hideSettings() {
	$("#showSettings").modal("hide");
}

function hideonlySettings() {
	showloading();
	document.getElementById("noprint").style.display = "none";
	document.getElementById("formSettings").submit();
}

//THIS FUNCTION CALL BY printview.php IN DESKTOP MODE
function printDesktop() {
	//document.getElementById("printSettingDesktop").style.display = "none";
	
	var restorepage = document.body.innerHTML;
	var printDiv = document.getElementById("printthis").innerHTML;
	
	document.body.innerHTML = printDiv;
	setTimeout(function() {window.print()}, 500);
			
	document.body.innerHTML = restorepage;
}

function hidePrintbtn() {
	document.getElementById("noprint").style.display = "none";
}

function showPrintbtn() {
	document.getElementById("noprint").style.display = "inline";
}
