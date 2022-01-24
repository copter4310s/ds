function goBack() {
    window.history.back();
}

function goBackthree() {
    window.history.go(-3);
}

function close_window() {
	close();
}

function showloading() {
    document.getElementById("loading").style.display = "block";
}

function hideloading() {
    document.getElementById("loading").style.display = "none";
}

function hidetbinfo(tbnumber) {
	document.getElementById("tbinfo" + tbnumber).style.display = "none";
	document.getElementById("blockanother" + tbnumber).style.display = "none";
}

function goLogin() {
	document.getElementById("btnLogin").disabled = true;
	document.getElementById("adminpassword").readOnly = true;
    document.getElementById("btnLogin-spinner").style.display = "inline-block";
    document.getElementById("btnLogin-text").style.display = "none";

    timer = setTimeout(function(){
        document.getElementById("formLogin").submit();
    }, 150);
}