//FOR addsell.php
function infoContinue() {
	var sellDate = document.forms["sellInfo"]["sellDate"].value;
	var list = document.forms["sellInfo"]["list"].value;
	var sellPrice = document.forms["sellInfo"]["sellPrice"].value;
	var sellProfit = document.forms["sellInfo"]["sellProfit"].value;
	var buyPrice = document.forms["sellInfo"]["buyPrice"].value;
	var sellProvince = document.forms["sellInfo"]["sellProvince"].value;
	var sellSource = document.forms["sellInfo"]["sellSource"].value;
	var deliveryCost = document.forms["sellInfo"]["deliveryCost"].value;

	if (sellDate.trim() == "" || list.trim() == "" || sellPrice.trim() == "" || sellProvince.trim() == "" || sellSource.trim() == "") {
		alert(enterCompleteInfo);
	} else {
		var sellProfit = document.forms["sellInfo"]["sellProfit"].value = eval(sellPrice - buyPrice);
		if (deliveryCost.trim() == "") {
			document.forms["sellInfo"]["deliveryCost"].value = 0;
		}
		showwarn();
	}
}

//FOR addbuy.php
function infoContinues() {
	var buyDate = document.forms["buyInfo"]["buyDate"].value;
	var list = document.forms["buyInfo"]["list"].value;
	var category = document.forms["buyInfo"]["category"].value;
	var buyPrice = document.forms["buyInfo"]["buyPrice"].value;

	if (buyDate.trim() == "" || list.trim() == "" || category.trim() == "" || buyPrice.trim() == "") {
		alert(enterCompleteInfo);
	} else {
		showwarns();
	}
}

//FOR addbuy.php
function showwarns() {
	document.getElementById("buyDate").readOnly = true;
	document.getElementById("list").readOnly = true;
	document.getElementById("category").readOnly = true;
	document.getElementById("buyPrice").readOnly = true;
	
	document.getElementById("buyDate").classList.add("readonly");
	document.getElementById("list").classList.add("readonly");
	document.getElementById("buyPrice").classList.add("readonly");
	
	document.getElementById("btn3").style.display = "inline";
	document.getElementById("btn2").style.display = "inline";
	document.getElementById("btn1").style.display = "none";
}

function hidewarns() {
	document.getElementById("buyDate").readOnly = false;
	document.getElementById("list").readOnly = false;
	document.getElementById("category").readOnly = false;
	document.getElementById("buyPrice").readOnly = false;
	
	document.getElementById("buyDate").classList.remove("readonly");
	document.getElementById("list").classList.remove("readonly");
	document.getElementById("buyPrice").classList.remove("readonly");
	
	document.getElementById("btn3").style.display = "none";
	document.getElementById("btn2").style.display = "none";
	document.getElementById("btn1").style.display = "inline";
}

//FOR editdata.php (AVALIBLE)
function infoContinuees() {
	var buyDate = document.forms["buyInfo"]["buyDate"].value;
	var list = document.forms["buyInfo"]["list"].value;
	var category = document.forms["buyInfo"]["category"].value;
	var buyPrice = document.forms["buyInfo"]["buyPrice"].value;

	if (buyDate.trim() == "" || list.trim() == "" || category.trim() == "" || buyPrice.trim() == "") {
		alert(enterCompleteInfo);
	} else {
		showwarnes();
	}
}

//FOR editdata.php (NOT AVALIBLE)
function infoContinuee() {
	var sellDate = document.forms["sellInfo"]["sellDate"].value;
	var list = document.forms["sellInfo"]["list"].value;
	var sellPrice = document.forms["sellInfo"]["sellPrice"].value;
	var sellProfit = document.forms["sellInfo"]["sellProfit"].value;
	var buyPrice = document.forms["sellInfo"]["buyPrice"].value;
	var sellProvince = document.forms["sellInfo"]["sellProvince"].value;
	var sellSource = document.forms["sellInfo"]["sellSource"].value;
	var deliveryCost = document.forms["sellInfo"]["deliveryCost"].value;

	if (sellDate.trim() == "" || list.trim() == "" || sellPrice.trim() == "" || sellProvince.trim() == "" || sellSource.trim() == "") {
		alert(enterCompleteInfo);
	} else {
		var sellProfit = document.forms["sellInfo"]["sellProfit"].value = eval(sellPrice - buyPrice);
		if (deliveryCost.trim() == "") {
			document.forms["sellInfo"]["deliveryCost"].value = 0;
		}
		showwarne();
	}
}

//editdata.php (NOT AVALIBLE)
function showwarne() {
	document.getElementById("buyDate").readOnly = true;
	document.getElementById("sellDate").readOnly = true;
	document.getElementById("list").readOnly = true;
	//document.getElementById("category").readOnly = true;
	document.getElementById("sellPrice").readOnly = true;
	document.getElementById("buyPrice").readOnly = true;
	document.getElementById("sellProvince").readOnly = true;
	document.getElementById("sellSource").readOnly = true;
	document.getElementById("buyPrice").readOnly = true;
	document.getElementById("deliveryCost").readOnly = true;
	
	document.getElementById("buyDate").classList.add("readonly");
	document.getElementById("sellDate").classList.add("readonly");
	document.getElementById("list").classList.add("readonly");
	document.getElementById("sellPrice").classList.add("readonly");
	document.getElementById("buyPrice").classList.add("readonly");
	document.getElementById("deliveryCost").classList.add("readonly");

	//CHECK IF sellProfit IS NEGATIVE
	if (document.getElementById("sellProfit").value < 0) {
		document.getElementById("sellProfit").style.color = "red";
	} else {
		document.getElementById("sellProfit").style.color = "black";
	}
	
	document.getElementById("btn3").style.display = "inline";
	document.getElementById("btn2").style.display = "inline";
	document.getElementById("btn1").style.display = "none";
}

//editdata.php (AVALIBLE)
function showwarnes() {
	document.getElementById("buyDates").readOnly = true;
	document.getElementById("lists").readOnly = true;
	document.getElementById("categorys").readOnly = true;
	document.getElementById("buyPrices").readOnly = true;
	
	document.getElementById("buyDates").classList.add("readonly");
	document.getElementById("lists").classList.add("readonly");
	document.getElementById("buyPrices").classList.add("readonly");
	
	document.getElementById("btn33").style.display = "inline";
	document.getElementById("btn22").style.display = "inline";
	document.getElementById("btn11").style.display = "none";
}

//editdata.php (NOT AVALIBLE)
function hidewarne() {
	document.getElementById("buyDate").readOnly = false;
	document.getElementById("sellDate").readOnly = false;
	document.getElementById("list").readOnly = false;
	//document.getElementById("category").readOnly = false;
	document.getElementById("sellPrice").readOnly = false;
	document.getElementById("buyPrice").readOnly = false;
	document.getElementById("sellProvince").readOnly = false;
	document.getElementById("sellSource").readOnly = false;
	document.getElementById("buyPrice").readOnly = false;
	document.getElementById("deliveryCost").readOnly = false;
	
	document.getElementById("buyDate").classList.remove("readonly");
	document.getElementById("sellDate").classList.remove("readonly");
	document.getElementById("list").classList.remove("readonly");
	document.getElementById("sellPrice").classList.remove("readonly");
	document.getElementById("buyPrice").classList.remove("readonly");
	document.getElementById("deliveryCost").classList.remove("readonly");
		
	document.getElementById("sellPrice").style.color = "black";
	
	document.getElementById("btn3").style.display = "none";
	document.getElementById("btn2").style.display = "none";
	document.getElementById("btn1").style.display = "inline";
}

//editdata.php (AVALIBLE)
function hidewarnes() {
	document.getElementById("buyDates").readOnly = false;
	document.getElementById("lists").readOnly = false;
	document.getElementById("categorys").readOnly = false;
	document.getElementById("buyPrices").readOnly = false;
	
	document.getElementById("buyDates").classList.remove("readonly");
	document.getElementById("lists").classList.remove("readonly");
	document.getElementById("buyPrices").classList.remove("readonly");
	
	document.getElementById("btn33").style.display = "none";
	document.getElementById("btn22").style.display = "none";
	document.getElementById("btn11").style.display = "inline";
}

//FOR addsell.php
function showwarn() {
	document.getElementById("sellDate").readOnly = true;
	document.getElementById("list").readOnly = true;
	//document.getElementById("category").readOnly = true;
	document.getElementById("sellPrice").readOnly = true;
	//document.getElementById("sellProfit").readOnly = true;
	document.getElementById("sellProvince").readOnly = true;
	document.getElementById("sellSource").readOnly = true;
	document.getElementById("deliveryCost").readOnly = true;
	
	document.getElementById("sellDate").classList.add("readonly");
	document.getElementById("sellPrice").classList.add("readonly");
	document.getElementById("deliveryCost").classList.add("readonly");

	//CHECK IF sellProfit IS NEGATIVE
	if (document.getElementById("sellProfit").value < 0) {
		document.getElementById("sellProfit").style.color = "red";
	} else {
		document.getElementById("sellProfit").style.color = "black";
	}
	
	document.getElementById("btn3").style.display = "inline";
	document.getElementById("btn2").style.display = "inline";
	document.getElementById("btn1").style.display = "none";
}

function hidewarn() {
	document.getElementById("sellDate").readOnly = false;
	document.getElementById("list").readOnly = false;
	//document.getElementById("category").readOnly = false;
	document.getElementById("sellPrice").readOnly = false;
	//document.getElementById("sellProfit").readOnly = false;
	document.getElementById("sellProvince").readOnly = false;
	document.getElementById("sellSource").readOnly = false;
	document.getElementById("deliveryCost").readOnly = false;
	
	document.getElementById("sellDate").classList.remove("readonly");
	document.getElementById("sellPrice").classList.remove("readonly");
	document.getElementById("deliveryCost").classList.remove("readonly");

	document.getElementById("sellPrice").style.color = "black";
	
	document.getElementById("btn3").style.display = "none";
	document.getElementById("btn2").style.display = "none";
	document.getElementById("btn1").style.display = "inline";
}

//FOR undosell.php
function showwarnsl() {
	addReadonly("selectYear");
	isshowwarn = 1;

	document.getElementById("btn3").style.display = "inline";
	document.getElementById("btn2").style.display = "inline";
	document.getElementById("btn1").style.display = "none";
}

function hidewarnsl() {
	removeReadonly("selectYear");
	isshowwarn = 0;

	document.getElementById("btn3").style.display = "none";
	document.getElementById("btn2").style.display = "none";
	document.getElementById("btn1").style.display = "inline";
}

function showlogin() {
	document.getElementById("adminlogin").style.display = "inline-table";
	document.getElementById("maintb").style.display = "none";
	setTimeout(function(){ document.getElementById('loading').style.display = 'none'; }, 1000);
}

function hidelogin() {
	document.getElementById("adminlogin").style.display = "none";
	document.getElementById("maintb").style.display = "inline-table";
}

//FOR addsell.php
function clearInfo() {
	var category = document.getElementById("category");
	var sellPrice = document.getElementById("sellPrice");
	var sellProfit = document.getElementById("sellProfit");
	var sellProvince = document.getElementById("sellProvince");
	var sellSource = document.getElementById("sellSource");
	var sellDate = document.getElementById("sellDate");
	var deliveryCost = document.getElementById("deliveryCost");
	
	var x = new Date();
    var y = x.getFullYear().toString();
    var m = (x.getMonth() + 1).toString();
    var d = x.getDate().toString();
    (d.length == 1) && (d = '0' + d);
    (m.length == 1) && (m = '0' + m);
    var yyyymmdd = y + "-" + m + "-" + d;
	
	category.value = "";
	sellPrice.value = "";
	sellProfit.value = "";
	sellProvince.value = "กรุงเทพมหานคร";
	sellSource.value = "Facebook";
	sellDate.value = yyyymmdd;
	deliveryCost.value = "";
}

//FOR addbuy.php
function clearInfos() {
	var buyDate = document.getElementById("buyDate");
	var list = document.getElementById("list");
	var category = document.getElementById("category");
	var buyPrice = document.getElementById("buyPrice");
	var deliveryCost = document.getElementById("deliveryCost");
	
	var x = new Date();
    var y = x.getFullYear().toString();
    var m = (x.getMonth() + 1).toString();
    var d = x.getDate().toString();
    (d.length == 1) && (d = '0' + d);
    (m.length == 1) && (m = '0' + m);
    var yyyymmdd = y + "-" + m + "-" + d;
	
	buyDate.value = yyyymmdd;
	list.value = "";
	category.value = "AMP";
	buyPrice.value = "";
}

//ADD READONLY AND GRAY BACKGROUND
function addReadonly(elementname) {
	document.getElementById(elementname).classList.add("readonly");
	document.getElementById(elementname).readOnly = true;
}

//REMOVE READONLY AND GRAY BACKGROUND
function removeReadonly(elementname) {
	document.getElementById(elementname).classList.remove("readonly");
	document.getElementById(elementname).readOnly = false;
}

//ADD(S) READONLY AND GRAY BACKGROUND
function addReadonlys(elementname1, elementname2) {
	document.getElementById(elementname1).classList.add("readonly");
	document.getElementById(elementname1).readOnly = true;
	document.getElementById(elementname2).classList.add("readonly");
	document.getElementById(elementname2).readOnly = true;
}

//REMOVE(s) READONLY AND GRAY BACKGROUND
function removeReadonlys(elementname1, elementname2) {
	document.getElementById(elementname1).classList.remove("readonly");
	document.getElementById(elementname1).readOnly = false;
	document.getElementById(elementname2).classList.add("readonly");
	document.getElementById(elementname2).readOnly = true;
}
