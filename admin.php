<?php
	error_reporting(0);
	date_default_timezone_set('Asia/Bangkok');

	//START SECTION: SET LANGUAGE
	$useLangReader = fopen("./module/lang/use.txt", "r");
	$useLang = trim(fgets($useLangReader));
	fclose($useLangReader);

	//CHECK IF READ ERROR THEN INCLUDE LANGUAGE FILE
	if (trim($useLang) == "") {
		include "./module/lang/english.php";
	} else {
		include "./module/lang/" . $useLang . ".php";
	}
	//END SECTION: SET LANGUAGE
	
	//START SECTION: LOGIN
	include "./module/login.php";
	//END SECTION: LOGIN

	//START SECTION: CONFIG
	include "./module/config.php";
	//END SECTION: CONFIG
	
	//SET VARIABLE
	$sellYesterday = 0;
	$incomeTotalYesterday = 0;
	$incomeProfitYesterday = 0;
	$sellToday = 0;
	$incomeTotalToday = 0;
	$incomeProfitToday = 0;
	$sellLastMonth = 0;
	$incomeTotalLastMonth = 0;
	$incomeProfitLastMonth = 0;
	$sellMonth = 0;
	$incomeTotalMonth = 0;
	$incomeProfitMonth = 0;
	$sellLastYear = 0;
	$incomeTotalLastYear = 0;
	$incomeProfitLastYear = 0;
	$sellMonth = 0;
	$incomeTotalYear = 0;
	$incomeProfitYear = 0;

	$allList = 0;
	$allListbuyPrice = 0;
	$avalibleList = 0;
	$avalibleListbuyPrice = 0;
	$sellList = 0;
	$sellListbuyPrice = 0;
	$totaldeliveryCost = 0;
	
	$sellMoreThanYesterday = FALSE;
	$sellMoreThanYesterdayValue = 0;
	$sellTodaySymbol = "<font color='#ff0040'>&#x25BC;</font>";
	$incomeMoreThanYesterday = FALSE;
	$incomeMoreThanYesterdayValue = 0;
	$incomeTodaySymbol = "<font color='#ff0040'>&#x25BC;</font>";
	$ProfitMoreThanYesterday = FALSE;
	$ProfitMoreThanYesterdayValue = 0;
	$ProfitTodaySymbol = "<font color='#ff0040'>&#x25BC;</font>";
	
	$sellMoreThanLastMonth = FALSE;
	$sellMoreThanLastMonthValue = 0;
	$sellMonthSymbol = "<font color='#ff0040'>&#x25BC;</font>";
	$incomeMoreThanLastMonth = FALSE;
	$incomeMoreThanLastMonthValue = 0;
	$incomeMonthSymbol = "<font color='#ff0040'>&#x25BC;</font>";
	$ProfitMoreThanLastMonth = FALSE;
	$ProfitMoreThanLastMonthValue = 0;
	$ProfitMonthSymbol = "<font color='#ff0040'>&#x25BC;</font>";
	
	$sellMoreThanLastYear = FALSE;
	$sellMoreThanLastYearValue = 0;
	$sellYearSymbol = "<font color='#ff0040'>&#x25BC;</font>";
	$incomeMoreThanLastYear = FALSE;
	$incomeMoreThanLastYearValue = 0;
	$incomeYearSymbol = "<font color='#ff0040'>&#x25BC;</font>";
	$ProfitMoreThanLastYear = FALSE;
	$ProfitMoreThanLastYearValue = 0;
	$ProfitYearSymbol = "<font color='#ff0040'>&#x25BC;</font>";

	$incomeErrorMessage = "";
	$sourceErrorMessage = "";
	
	$errortb = "";
	
	//REVERTIVE DATA
	if ($auth and $authuser != "guest") {
		//AUTH, SET CONNECTION DETAIL
		//START SECTION: SQL LOGIN
		include "./module/sqllogin.php";
		//END SECTION: SQL LOGIN

		$connectionerror = FALSE;
		$incomeError = FALSE;
		$incomeDError = FALSE;
		$incomeMError = FALSE;
		$incomeYError = FALSE;
		$sourceError = FALSE;
		$deliveryCostError = FALSE;
		$catError = FALSE;
		$listError = FALSE;
		$errorloginmessage = "";

		//CREATE AND CHECK CONNECTION
		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			$connectionerror = TRUE;
		}
		
		//CHECK IF USER CUSTOM START TIME
		if ($_POST["starttime"] != "") {
			$todayTime = strtotime($_POST["starttime"]);
		} else {
			$todayTime = time();
		}

		$timeday = date('Y-m-d', $todayTime);
		$timemonth = date('Y-m', $todayTime);
		$yesterday = date('Y-m-d',strtotime("-1 days", $todayTime));
		
		$thisyear = date('Y', $todayTime);
		$lastyear = date('Y',strtotime("-1 years", $todayTime));
		$thismonth = date('m', $todayTime);
		$thismonth -= 1;

		if (strlen($thismonth) == 1) {
			$LastMonth = $thisyear . "-0" . $thismonth;
		} else {
			$LastMonth = $thisyear . "-" . $thismonth;
		}
		
		//GET AVALIBLE LIST AND AVALIBLE LIST BUY PRICE
		$sqllist = "SELECT buyPrice FROM $tb_name WHERE avalible=1 AND YEAR(buyDate) = $thisyear";
		if ($res = mysqli_query($conn, $sqllist)) { 
			$avalibleList = mysqli_num_rows($res);
			
			while ($row = mysqli_fetch_array($res)) {
				$avalibleListbuyPrice += $row['buyPrice'];
			}
		} else { 
			//ERROR
			$listError = TRUE;
		}

		//DELAY
		usleep($delayUTime);
		
		//GET NOT AVALIBLE LIST AND NOT AVALIBLE LIST BUY PRICE
		$sqllist = "SELECT buyPrice FROM $tb_name WHERE avalible=0 AND YEAR(sellDate) = $thisyear";
		if ($res = mysqli_query($conn, $sqllist)) { 
			$sellList = mysqli_num_rows($res);
			
			while ($row = mysqli_fetch_array($res)) {
				$sellListbuyPrice += $row['buyPrice'];
			}
		} else { 
			//ERROR
			$listError = TRUE;
		}

		//DELAY
		usleep($delayUTime);
		
		//GET ALL LIST AND ALL LIST BUY PRICE
		$allList = ($sellList + $avalibleList);
		$allListbuyPrice = ($sellListbuyPrice + $avalibleListbuyPrice);
		
		//DELAY
		usleep($delayUTime);

		$sourcelistpath = "././module/sourcelist.txt";
		$sourcelistcount = $lines = count(file($sourcelistpath));
		$sourcequerycount = 0;
		
		//READ SOURCE LIST AND QUERY FOR DATA
		$fnsource = fopen($sourcelistpath,"r");
		while(! feof($fnsource))  {
			$sourcequerycount += 1;
			$resultsource = fgets($fnsource);
			$resultsource = str_replace("\n", "", $resultsource);
			
			//GET SOURCE
			$sqlsource = "SELECT id FROM $tb_name WHERE sellSource='$resultsource' AND YEAR(sellDate) = $thisyear";
			if ($res = mysqli_query($conn, $sqlsource)) { 
				$sourceResult[$sourcequerycount] = mysqli_num_rows($res);
			} else { 
				//ERROR
				$sourceError = TRUE;
				$sourceErrorMessage = $conn->error;
			} 
		}
		fclose($fnsource);
		
		//DELAY
		usleep($delayUTime);
		
		$categorylistpath = "././module/categorylist.txt";
		$categorylistcount = $lines = count(file($categorylistpath));
		$categoryquerycount = 0;
		
		//READ CATEGORY LIST AND QUERY FOR DATA
		$fn = fopen($categorylistpath,"r");
		while(! feof($fn))  {
			$categoryquerycount += 1;
			$result1 = fgets($fn);
			$result1 = str_replace("\n", "", $result1);
			
			//GET CATEGORY
			$sqlcat = "SELECT id FROM $tb_name WHERE category='$result1' AND YEAR(sellDate) = $thisyear";
			if ($res = mysqli_query($conn, $sqlcat)) { 
				$catResult[$categoryquerycount] = mysqli_num_rows($res);
			} else { 
				//ERROR
				$catError = TRUE;
			} 
		}
		fclose($fn);
		
		//DELAY
		usleep($delayUTime);

		//GET REMAINING PRODUCTS CATEGORY
		//READ CATEGORY LIST AND QUERY FOR DATA
		$categoryquerycount = 0;
		$fn = fopen($categorylistpath,"r");
		while(! feof($fn))  {
			$categoryquerycount += 1;
			$result2 = fgets($fn);
			$result2 = str_replace("\n", "", $result2);
			
			//GET CATEGORY
			$sqlremcat = "SELECT id FROM $tb_name WHERE category='$result2' AND avalible=1 AND YEAR(buyDate) = $thisyear";
			if ($res = mysqli_query($conn, $sqlremcat)) { 
				$remCatResult[$categoryquerycount] = mysqli_num_rows($res);
			} else { 
				//ERROR
				$catError = TRUE;
			} 
		}
		fclose($fn);
		
		//DELAY
		usleep($delayUTime);
		
		//GET INCOME YESTERDAY
		$sqlincomeYesterday = "SELECT sellPrice, sellProfit FROM $tb_name WHERE sellDate='$yesterday'";
		if ($res = mysqli_query($conn, $sqlincomeYesterday)) { 
			if (mysqli_num_rows($res) > 0) { 
				while ($row = mysqli_fetch_array($res)) {
					$sellYesterday = mysqli_num_rows($res);
					$incomeTotalYesterday += $row['sellPrice'];
					$incomeProfitYesterday += $row['sellProfit'];
				}
			} else { 
				//NO INCOME YESTERDAY
			} 
		} else { 
			//ERROR
			$incomeError = TRUE;
		} 
		
		//GET INCOME TODAY
		$sqlincomeToday = "SELECT sellPrice, sellProfit FROM $tb_name WHERE sellDate='$timeday'";
		if ($res = mysqli_query($conn, $sqlincomeToday)) { 
			if (mysqli_num_rows($res) > 0) { 
				while ($row = mysqli_fetch_array($res)) {
					$sellToday = mysqli_num_rows($res);
					$incomeTotalToday += $row['sellPrice'];
					$incomeProfitToday += $row['sellProfit'];
				}
			} else { 
				//NO INCOME TODAY
			} 
		} else { 
			//ERROR
			$incomeDError = TRUE;
		} 
		
		//DELAY
		usleep($delayUTime);
		
		//GET INCOME LAST MONTH
		$sqlincomeLastMonth = "SELECT sellPrice, sellProfit FROM $tb_name WHERE sellDate LIKE '%{$LastMonth}%'";
		if ($res = mysqli_query($conn, $sqlincomeLastMonth)) { 
			if (mysqli_num_rows($res) > 0) { 
				while ($row = mysqli_fetch_array($res)) {
					$sellLastMonth = mysqli_num_rows($res);
					$incomeTotalLastMonth += $row['sellPrice'];
					$incomeProfitLastMonth += $row['sellProfit'];
				}
			} else { 
				//NO INCOME LAST MONTH
			} 
		} else { 
			//ERROR
			$incomeError = TRUE;
		}
		
		//GET INCOME THIS MONTH
		$sqlincomeMonth = "SELECT sellPrice, sellProfit FROM $tb_name WHERE sellDate LIKE '%{$timemonth}%'";
		if ($res = mysqli_query($conn, $sqlincomeMonth)) { 
			if (mysqli_num_rows($res) > 0) { 
				while ($row = mysqli_fetch_array($res)) {
					$sellMonth = mysqli_num_rows($res);
					$incomeTotalMonth += $row['sellPrice'];
					$incomeProfitMonth += $row['sellProfit'];
				}
			} else { 
				//NO INCOME THIS MONTH
			} 
		} else { 
			//ERROR
			$incomeMError = TRUE;
		} 
		
		//DELAY
		usleep($delayUTime);
		
		//GET INCOME LAST YEAR
		$sqlincomeLastYear = "SELECT sellPrice, sellProfit FROM $tb_name WHERE sellDate LIKE '%{$lastyear}%'";
		if ($res = mysqli_query($conn, $sqlincomeLastYear)) { 
			if (mysqli_num_rows($res) > 0) { 
				while ($row = mysqli_fetch_array($res)) {
					$sellLastYear = mysqli_num_rows($res);
					$incomeTotalLastYear += $row['sellPrice'];
					$incomeProfitLastYear += $row['sellProfit'];
				}
			} else { 
				//NO INCOME LAST YEAR
			} 
		} else { 
			//ERROR
			$incomeYError = TRUE;
		}
		
		//GET INCOME THIS YEAR
		$sqlincomeYear = "SELECT sellPrice, sellProfit FROM $tb_name WHERE YEAR(sellDate) = $thisyear";
		if ($res = mysqli_query($conn, $sqlincomeYear)) { 
			if (mysqli_num_rows($res) > 0) { 
				while ($row = mysqli_fetch_array($res)) {
					$sellYear = mysqli_num_rows($res);
					$incomeTotalYear += $row['sellPrice'];
					$incomeProfitYear += $row['sellProfit'];
				}
			} else { 
				//NO INCOME THIS YEAR
			} 
		} else { 
			//ERROR
			$incomeYError = TRUE;
		} 
		
		//DELAY
		usleep($delayUTime);
		
		//SET GET INCOME ERROR STATE
		if ($incomeDError == TRUE or $incomeMError == TRUE) {
			$incomeError = TRUE;
			$incomeErrorMessage = $conn->error;
		} else {
			$incomeError = FALSE;
		}

		//GET DELIVERY COST
		$sqldeliveryCost = "SELECT SUM(deliveryCost) FROM $tb_name WHERE YEAR(sellDate) = $thisyear";
		if ($res = mysqli_query($conn, $sqldeliveryCost)) { 
			while ($row = mysqli_fetch_array($res)) {
				$totaldeliveryCost = $row[0];
			}
		} else { 
			//ERROR
			$deliveryCostError = TRUE;
		} 

		//DELAY
		usleep($delayUTime);
		
		//CALCULATE MONEY TODAY AND YESTERDAY
		//CHECK IF TOTAL <> YESTERDAY
		if ($incomeTotalToday > $incomeTotalYesterday) {
			//MORE THAN (+)
			$incomeMoreThanYesterday = TRUE;
			$incomeMoreThanYesterdayValue = +($incomeTotalToday - $incomeTotalYesterday);
			$incomeTodaySymbol = "<font color='#80ff00'>&#x25B2;</font>";
		} else {
			//LESS THAN OR EQUAL (-)
			$incomeMoreThanYesterday = FALSE;
			$incomeMoreThanYesterdayValue = -($incomeTotalYesterday - $incomeTotalToday);
			$incomeTodaySymbol = "<font color='#ff0040'>&#x25BC;</font>";
		}
		
		//CALCULATE PROFIT TODAY AND YESTERDAY
		//CHECK IF PROFIT <> YESTERDAY
		if ($incomeProfitToday > $incomeProfitYesterday) {
			//MORE THAN (+)
			$ProfitMoreThanYesterday = TRUE;
			$ProfitMoreThanYesterdayValue = ($incomeProfitToday - $incomeProfitYesterday);
			$ProfitTodaySymbol = "<font color='#80ff00'>&#x25B2;</font>";
		} else {
			//LESS THAN OR EQUAL (-)
			$ProfitMoreThanYesterday = FALSE;
			$ProfitMoreThanYesterdayValue = -($incomeProfitYesterday - $incomeProfitToday);
			$ProfitTodaySymbol = "<font color='#ff0040'>&#x25BC;</font>";
		}
		
		//CALCULATE sellED TODAY AND YESTERDAY
		//CHECK IF sellED <> YESTERDAY
		if ($sellToday > $sellYesterday) {
			//MORE THAN (+)
			$sellMoreThanYesterday = TRUE;
			$sellMoreThanYesterdayValue = +($sellToday - $sellYesterday);
			$sellTodaySymbol = "<font color='#80ff00'>&#x25B2;</font>";
		} else {
			//LESS THAN OR EQUAL (-)
			$sellMoreThanYesterday = FALSE;
			$sellMoreThanYesterdayValue = -($sellYesterday - $sellToday);
			$sellTodaySymbol = "<font color='#ff0040'>&#x25BC;</font>";
		}
		
		//DELAY
		usleep($delayUTime);
		
		//CALCULATE MONEY MONTH AND LAST MONTH
		//CHECK IF TOTAL <> YESTERDAY
		if ($incomeTotalMonth > $incomeTotalLastMonth) {
			//MORE THAN (+)
			$incomeMoreThanLastMonth = TRUE;
			$incomeMoreThanLastMonthValue = +($incomeTotalMonth - $incomeTotalLastMonth);
			$incomeMonthSymbol = "<font color='#80ff00'>&#x25B2;</font>";
		} else {
			//LESS THAN OR EQUAL (-)
			$incomeMoreThanLastMonth = FALSE;
			$incomeMoreThanLastMonthValue = -($incomeTotalLastMonth - $incomeTotalMonth);
			$incomeMonthSymbol = "<font color='#ff0040'>&#x25BC;</font>";
		}
		
		//CALCULATE PROFIT MONTH AND LAST MONTH
		//CHECK IF PROFIT <> YESTERDAY
		if ($incomeProfitMonth > $incomeProfitLastMonth) {
			//MORE THAN (+)
			$ProfitMoreThanLastMonth = TRUE;
			$ProfitMoreThanLastMonthValue = ($incomeProfitMonth - $incomeProfitLastMonth);
			$ProfitMonthSymbol = "<font color='#80ff00'>&#x25B2;</font>";
		} else {
			//LESS THAN OR EQUAL (-)
			$ProfitMoreThanLastMonth = FALSE;
			$ProfitMoreThanLastMonthValue = -($incomeProfitLastMonth - $incomeProfitMonth);
			$ProfitMonthSymbol = "<font color='#ff0040'>&#x25BC;</font>";
		}
		
		//CALCULATE sellED MONTH AND LAST MONTH
		//CHECK IF sellED <> YESTERDAY
		if ($sellMonth > $sellLastMonth) {
			//MORE THAN (+)
			$sellMoreThanLastMonth = TRUE;
			$sellMoreThanLastMonthValue = +($sellMonth - $sellLastMonth);
			$sellMonthSymbol = "<font color='#80ff00'>&#x25B2;</font>";
		} else {
			//LESS THAN OR EQUAL (-)
			$sellMoreThanLastMonth = FALSE;
			$sellMoreThanLastMonthValue = -($sellLastMonth - $sellMonth);
			$sellMonthSymbol = "<font color='#ff0040'>&#x25BC;</font>";
		}
		
		//DELAY
		usleep($delayUTime);
		
		//CALCULATE MONEY YEAR AND LAST YEAR
		//CHECK IF TOTAL <> YESTERDAY
		if ($incomeTotalYear > $incomeTotalLastYear) {
			//MORE THAN (+)
			$incomeMoreThanLastYear = TRUE;
			$incomeMoreThanLastYearValue = +($incomeTotalYear - $incomeTotalLastYear);
			$incomeYearSymbol = "<font color='#80ff00'>&#x25B2;</font>";
		} else {
			//LESS THAN OR EQUAL (-)
			$incomeMoreThanLastYear = FALSE;
			$incomeMoreThanLastYearValue = -($incomeTotalLastYear - $incomeTotalYear);
			$incomeYearSymbol = "<font color='#ff0040'>&#x25BC;</font>";
		}
		
		//CALCULATE PROFIT YEAR AND LAST YEAR
		//CHECK IF PROFIT <> YESTERDAY
		if ($incomeProfitYear > $incomeProfitLastYear) {
			//MORE THAN (+)
			$ProfitMoreThanLastYear = TRUE;
			$ProfitMoreThanLastYearValue = ($incomeProfitYear - $incomeProfitLastYear);
			$ProfitYearSymbol = "<font color='#80ff00'>&#x25B2;</font>";
		} else {
			//LESS THAN OR EQUAL (-)
			$ProfitMoreThanLastYear = FALSE;
			$ProfitMoreThanLastYearValue = -($incomeProfitLastYear - $incomeProfitYear);
			$ProfitYearSymbol = "<font color='#ff0040'>&#x25BC;</font>";
		}
		
		//CALCULATE sellED YEAR AND LAST YEAR
		//CHECK IF sellED <> YESTERDAY
		if ($sellYear > $sellLastYear) {
			//MORE THAN (+)
			$sellMoreThanLastYear = TRUE;
			$sellMoreThanLastYearValue = +($sellYear - $sellLastYear);
			$sellYearSymbol = "<font color='#80ff00'>&#x25B2;</font>";
		} else {
			//LESS THAN OR EQUAL (-)
			$sellMoreThanLastYear = FALSE;
			$sellMoreThanLastYearValue = -($sellLastYear - $sellYear);
			$sellYearSymbol = "<font color='#ff0040'>&#x25BC;</font>";
		}
		
		//DELAY
		usleep($delayUTime);
		
		//CHECK ERROR STATE
		if ($connectionerror) {
			//CONNECTION ERROR
			$modalWarn = _ERROR_DB_CONNECT . "<br>" . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8');
		} else if ($incomeError) {
			//INCOME ERROR
			$modalWarn = _ERROR_WHILE_LOAD_DATA_INCOME . "<br>" . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8');
		} else if ($sourceError) {
			//SOURCE ERROR
			$modalWarn = _ERROR_WHILE_LOAD_DATA_SOURCE . "<br>" . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8');
		} else if ($catError) {
			//CATEGORY ERROR
			
		} else {
			//NO ERROR FOUND (SUCESSFULLY)
		}
		
		//CLOSE CONNECTION
		$conn->close();
		
	} else {
		//NOT AUTH OR BAD USERNAME, CHECK IF ADMINPASSWORD IS BLANK
		if ($adminpassword == "da39a3ee5e6b4b0d3255bfef95601890afd80709") {
			//ADMINPASSWORD IS BLANK
		} else {
			//NOT LOGIN, CHECK IF AUTH FAILED
			if (! $authfail) {
				//NOT FAIL BUT ?
				$modalWarn = _ENTER_PASSWORD_CORRECTLY;
				
				//SAVE AUTH FAIL
				date_default_timezone_set('Asia/Bangkok');
				$date = date("Y-m-d");
				$time = date("H:i:s");
				$page = "admin.php";
				
				//CONTINUE INSERT, SET CONNECTION DETAIL
				//START SECTION: SQL LOGIN
				include "./module/sqllogin.php";
				//END SECTION: SQL LOGIN
			
				//CREATE AND CHECK CONNECTION
				$conn = new mysqli($servername, $username, $password, $dbname);

				if ($conn->connect_error) {
					$connectionerror = TRUE;
				}
	
				//INSERT AUTH FAIL
				$useros = mysqli_real_escape_string( $conn, $_SERVER['HTTP_USER_AGENT'] );
				$sql = "INSERT INTO $tb_nameLoginFail(date, time, ip, os, page) VALUES ('$date', '$time', '$clientIP', '$useros', '$page')";
				$retval = mysqli_query($conn, $sql);
   
				if(! $retval) {
		
				}
	
				//CLOSE CONNECTION
				$conn->close();
			} else {
				//AUTH FAILED
			}
		}
	}
	
	//CALCULATE REAL INCOME
	$realincomemonthprice = ($incomeTotalMonth - $incomeProfitMonth);
	$realincomeyearprice = ($incomeTotalYear - $incomeProfitYear);
	$realincomeLastMonthprice = ($incomeTotalLastMonth - $incomeProfitLastMonth);
	$realincomelastyearprice = ($incomeTotalLastYear - $incomeProfitLastYear);
?>

<html>
	<head>
		<title><?= _ADMIN_PAGE ?></title>
		<!-- BOOTSTRAP -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

		<link rel="stylesheet" href="./main.css" type="text/css" />
		<link rel="shortcut icon" type="img/icon" href="favicon.ico">
        <?php
			if ($auth && $authuser != "guest") {
				echo '<meta name="viewport" content="width=device-width, initial-scale=0.72">';
			} else {
				echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
			}
		?>
		<script src='js/pzDS_add.js'></script>
		<script src='js/pzDS_admin.js'></script>
		<script src='js/main.js'></script>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	</head>
	<body onload="waitGraph()">
		<div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-success navbar-light fixed-top">
                <img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b><?= _DATA_SYSTEMS ?></b></font></span>
            </nav>
            <br/>
            <div class="container-fluid">
                <div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;" id="adminlogin">
                    <div>
                        <center><font size="5"><b><?= _LOGIN_ADMIN_PAGE ?></b></font></center>
                    </div>
					<div style="padding: 8px;"></div>
					<div class="text-center">
						<font size="2">
							<?= _LOGIN_ADMIN_PAGE . ", " . _ENTER_PASSWORD_CORRECTLY ?>
						</font>
					</div>
					<div style="padding: 8px;"></div>
                    <div class="container-lg">
                        <form style="margin-block-end: 0;" method="POST" id="formLogin" onsubmit="goLogin() addReadonly('adminpassword')">
                            <div>
								<font size="3"><?= _PASSWORD ?></font>
                            </div>
                            <div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input type="password" id="adminpassword" name="adminpassword" class="form-control" required>
                                </div>
                            </div>
                            <div>
                                <center>
                                    <button type="button" id="btnLogin" onclick="goLogin()" class="btn btn-sm btn-success">
                                        <span class="spinner-border" id="btnLogin-spinner" style="display: none;"></span>
                                        <span id="btnLogin-text"><?= _CONTINUE ?></span>
                                    </button>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
			<!-- -->
			<center>
				<div class="container-fluid" id="maintb" style="display:none; margin-top:54px;">
					<div>
						<font size="5">
							<b><?= _ADMIN_PAGE ?></b>
						</font>
					</div>
					<div class="container-fluid pt-2">
						<div class="container-lg">
							<center>
								<font size="3">
									<div><?= _TODAY_SELL ?> <span id="sellToday"><?= number_format( $sellToday ); ?></span> <?= _LIST ?> (<?= $sellTodaySymbol . " " . number_format( $sellMoreThanYesterdayValue ); ?> <?= _LIST ?>), <?= _TODAY_INCOME ?> : <span id="incomeTotalToday"><?= number_format( $incomeTotalToday ); ?></span> <?= _CURRENCY ?> (<?= $incomeTodaySymbol . " " . number_format( $incomeMoreThanYesterdayValue ); ?> <?= _CURRENCY ?>), <?= _TODAY_PROFIT ?> : <span id="incomeProfitToday"><?= number_format( $incomeProfitToday ); ?></span> <?= _CURRENCY ?> (<?= $ProfitTodaySymbol . " " . number_format( $ProfitMoreThanYesterdayValue ); ?> <?= _CURRENCY ?>)</div>
									<div class="pb-1"></div>
									<div><?= _THIS_MONTH_SELL ?> <span id="sellMonth"><?= number_format( $sellMonth ); ?></span> <?= _LIST ?> (<?= $sellMonthSymbol . " " . number_format( $sellMoreThanLastMonthValue ); ?> <?= _LIST ?>), <?= _THIS_MONTH_INCOME ?> <span id="incomeTotalMonth"><?= number_format( $incomeTotalMonth ); ?></span> <?= _CURRENCY ?> (<?= $incomeMonthSymbol . " " . number_format( $incomeMoreThanLastMonthValue ); ?> <?= _CURRENCY ?>), <?= _THIS_MONTH_PROFIT ?> <span id="incomeProfitMonth"><?= number_format( $incomeProfitMonth ); ?></span> <?= _CURRENCY ?> (<?= $ProfitMonthSymbol . " " . number_format( $ProfitMoreThanLastMonthValue ); ?> <?= _CURRENCY ?>)</div>
									<div class="pb-1"></div>
									<div><?= _THIS_YEAR_SELL ?> <span id="sellYear"><?= number_format( $sellYear ); ?></span> <?= _LIST ?> (<?= $sellYearSymbol . " " . number_format( $sellMoreThanLastYearValue ); ?> <?= _LIST ?>), <?= _THIS_YEAR_INCOME ?> <span id="incomeTotalMonth"><?= number_format( $incomeTotalYear ); ?></span> <?= _CURRENCY ?> (<?= $incomeYearSymbol . " " . number_format( $incomeMoreThanLastYearValue ); ?> <?= _CURRENCY ?>), <?= _THIS_YEAR_PROFIT ?> <span id="incomeProfitYear"><?= number_format( $incomeProfitYear ); ?></span> <?= _CURRENCY ?> (<?= $ProfitYearSymbol . " " . number_format( $ProfitMoreThanLastYearValue ); ?> <?= _CURRENCY ?>)</div>
								</font>
							</center>
						</div>
						<hr>
						<div class="container-lg">
							<strong><?= _ALL_PRODUCT_IN_SYSTEM ?>:</strong> <?= $allList; ?> <?= _LIST ?> (<?= _VALUE ?> <?= number_format( $allListbuyPrice ); ?> <?= _CURRENCY ?>)<br>
							<strong><?= _AVALIBLE_PRODUCT ?>:</strong> <?= $avalibleList; ?> <?= _LIST ?> (<?= _VALUE ?> <?= number_format( $avalibleListbuyPrice ); ?> <?= _CURRENCY ?>)<br>
							<strong><?= _NOT_AVALIBLE_PRODUCT ?>:</strong> <?= $sellList; ?> <?= _LIST ?> (<?= _VALUE ?> <?= number_format( $sellListbuyPrice ); ?> <?= _CURRENCY ?>)<br><div class="pb-1"></div>
							<strong><?= _TOTAL_DELIVERY_COST ?>:</strong> <?= number_format( $totaldeliveryCost ); ?> <?= _CURRENCY ?><br>
						</div>
						<div class="pt-3">
							<form method="POST" id="formstarttime" style="margin-bottom: 0px;" onsubmit="addReadonly('starttime'); showloading()">
								<input type="hidden" name="token" value="<?= $totoken; ?>" />
								<div class="input-group container-sm">
									<span style="margin-top: 8px;"><?= _SELECT_BEGINNING_TIME ?>: &nbsp;</span><input class="form-control" type="date" id="starttime" name="starttime" value="<?= date('Y-m-d', $todayTime); ?>">
									<div class="input-group-append">
										<button class="btn btn-sm btn-primary" type="submit"><?= _SET ?></button>
									</div>
								</div>
							</form>
						</div>
						<div class="pt-3">
							<form action="allmonthreport.php" method="POST" id="formreportyear" style="margin-bottom: 0px;" onsubmit="addReadonly('reportyear'); showloading()">
								<input type="hidden" name="token" value="<?= $totoken; ?>" />
								<div class="input-group container-sm">
									<span style="margin-top: 8px;"><?= _VIEW_ALL_MONTH_REPORT ?>: &nbsp;</span><input class="form-control" type="number" id="reportyear" name="reportyear" value="<?= $thisyear; ?>">
									<div class="input-group-append">
										<button class="btn btn-sm btn-primary" type="submit"><?= _SEARCH ?></button>
									</div>
								</div>
							</form>
						</div>
						<div class="pt-4">
							<div><font size="4"><?= _TODAY_INCOME ?></font></div>
							<div id="pieincometoday"></div>
							<div class="pt-1"><font size="4"><?= _THIS_MONTH_INCOME ?></font></div>
							<div id="pieincomemonth"></div>
							<div class="pt-1"><font size="4"><?= _THIS_YEAR_INCOME ?></font></div>
							<div id="pieincomeyear"></div>
							<div class="pt-1"><font size="4"><?= _YESTERDAY_INCOME ?></font></div>
							<div id="pieincomeyesterday"></div>
							<div class="pt-1"><font size="4"><?= _LAST_MONTH_INCOME ?></font></div>
							<div id="pieincomelastmonth"></div>
							<div class="pt-1"><font size="4"><?= _LAST_YEAR_INCOME ?></font></div>
							<div id="pieincomelastyear"></div>
							<div class="pt-1"><font size="4"><strong><?= _CATEGORY . " (" . _AVALIBLE_PRODUCT . ")" ?></strong></font></div>
							<div id="pieremainingcat"></div>
							<div class="pt-1"><font size="4"><strong><?= _CONTACT_FROM ?></strong></font></div>
							<div id="piesource"></div>
							<div class="pt-1"><font size="4"><strong><?= _CATEGORY ?></strong></font></div>
							<div id="piecat"></div>
						</div>
						<div class="pb-3">
							<font size="2"><strong><?= _THIS_YEAR_DATA ?></strong></font>
						</div>
					</div>
				</div>
			</center>
		</div>
		<div class="blockall" id="loading" style="display:block;">
			<div class="center">
			<img src="wheel.svg" width="48" height="48" />
			</div>
		</div>
		<div class="modal fade" id="modalWarning">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<font size="5" class="modal-title"><b><?= _NOTIFY ?></b></font>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<font size="3"><span id="modalWarningContent"><?= $modalWarn ?></span></font>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><?= _CLOSE_MESSAGE ?></button>
					</div>
				</div>
			</div>
		</div>
		<noscript><img id="blockanother" width="100%" height="100%"><table border="1" id="tbjavascript" class="tbinfo"><tr><td class="curve"><div style="padding:28px 28px 28px 28px;"><font size="4"><strong><center><?= _ERROR_ENABLE_JS ?><br></center><img src="/blank.png" height="32" /><br></strong><center><a class="myButton" target="_blank" href="<?= _ENABLE_JS_SITE ?>"><?= _CONTINUE ?></a></center></font></div></td></tr></table></noscript>
	</body>
</html>

<?php 
	//CONTROL LOGIN FORM
	if ($auth and $authuser != "guest") {
		//HIDE
		if ($modalWarn != "") {
			echo "\n<script>hidelogin(); $('#modalWarning').modal();</script>";
		} else {
			echo "\n<script>hidelogin();</script>";
		}
		
		//LOAD GRAPH TODAY
		echo "
		<script>
			//GET TODAY VALUE
			var incomeTotalToday = $incomeTotalToday;
			var incomeProfitToday = $incomeProfitToday;
				
			var intITT = parseInt(incomeTotalToday, 10);
			var intIPT = parseInt(incomeProfitToday, 10);
				
			var GOODSNoProfitToday = eval(intITT - intIPT);
				
			var intGNPT = parseInt(GOODSNoProfitToday, 10);
				
			//LOAD GOOGLE CHARTS
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChartIncomeToday);

			//DRAW THE CHART AND SET THE CHART VALUES
			function drawChartIncomeToday() {
				var dataIncomeToday = google.visualization.arrayToDataTable([
					['" . _TYPE . "', '" . _VALUE . "'],
					['" . _PRINCIPAL . "', intGNPT],
					['" . _SELL_PROFIT . "', intIPT]
				]);
				//SETTINGS THE CHART
				var settingsIncomeToday = {'title':'Income Today', 'width':415, 'height':275};

				var chartIncomeToday = new google.visualization.PieChart(document.getElementById('pieincometoday'));
				chartIncomeToday.draw(dataIncomeToday, settingsIncomeToday);
			}
		</script>
		";

		//DELAY
		usleep($delayUTime);
		
		//LOAD GRAPH MONTH
		echo "
		<script>
			var intRealIncomeMonth = $realincomemonthprice;
			var intProfitMonth = $incomeProfitMonth;

			//LOAD GOOGLE CHARTS
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChartIncomeMonth);

			//DRAW THE CHART AND SET THE CHART VALUES
			function drawChartIncomeMonth() {
				var dataIncomeMonth = google.visualization.arrayToDataTable([
					['" . _TYPE . "', '" . _VALUE . "'],
					['" . _PRINCIPAL . "', intRealIncomeMonth],
					['" . _SELL_PROFIT . "', intProfitMonth]
				]);
				//SETTINGS THE CHART
				var optionsIncomeMonth = {'title':'Income This Month', 'width':415, 'height':275};

				var chartIncomeMonth = new google.visualization.PieChart(document.getElementById('pieincomemonth'));
				chartIncomeMonth.draw(dataIncomeMonth, optionsIncomeMonth);
			}	
		</script>
		";

		//DELAY
		usleep($delayUTime);
		
		//LOAD GRAPH YEAR
		echo "
		<script>
			var intRealIncomeYear = $realincomeyearprice;
			var intProfitYear = $incomeProfitYear;

			//LOAD GOOGLE CHARTS
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChartYear);

			//DRAW THE CHART AND SET THE CHART VALUES
			function drawChartYear() {
				var dataIncomeYear = google.visualization.arrayToDataTable([
					['" . _TYPE . "', '" . _VALUE . "'],
					['" . _PRINCIPAL . "', intRealIncomeYear],
					['" . _SELL_PROFIT . "', intProfitYear]
				]);
				//SETTINGS THE CHART
				var optionsIncomeYear = {'title':'Income This Year', 'width':415, 'height':275};

				var chartIncomeYear = new google.visualization.PieChart(document.getElementById('pieincomeyear'));
				chartIncomeYear.draw(dataIncomeYear, optionsIncomeYear);
			}
		</script>
		";

		//DELAY
		usleep($delayUTime);
		
		//LOAD GRAPH YESTERDAY
		echo "
		<script>
			//GET YESTERDAY VALUE
			var incomeTotalYesterday = $incomeTotalYesterday;
			var incomeProfitYesterday = $incomeProfitYesterday;
				
			var intTotalYesterday = parseInt(incomeTotalYesterday, 10);
			var intProfitYesterday = parseInt(incomeProfitYesterday, 10);
				
			var GOODSNoProfitYesterday = eval(intTotalYesterday - intProfitYesterday);
				
			//LOAD GOOGLE CHARTS
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChartYearesterday);

			//DRAW THE CHART AND SET THE CHART VALUES
			function drawChartYearesterday() {
				var dataYesterday = google.visualization.arrayToDataTable([
					['" . _TYPE . "', '" . _VALUE . "'],
					['" . _PRINCIPAL . "', GOODSNoProfitYesterday],
					['" . _SELL_PROFIT . "', intProfitYesterday]
				]);
				//SETTINGS THE CHART
				var optionsYesterday = {'title':'Income Yesterday', 'width':415, 'height':275};

				var chartYesterday = new google.visualization.PieChart(document.getElementById('pieincomeyesterday'));
				chartYesterday.draw(dataYesterday, optionsYesterday);
			}
		</script>
		";

		//DELAY
		usleep($delayUTime);
		
		//LOAD GRAPH LAST MONTH
		echo "
		<script>
			var intIncomeLastMonth = $realincomeLastMonthprice;
			var intProfitLastMonth = $incomeProfitLastMonth;

			//LOAD GOOGLE CHARTS
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChartYearMonth);

			//DRAW THE CHART AND SET THE CHART VALUES
			function drawChartYearMonth() {
				var dataLastMonth = google.visualization.arrayToDataTable([
					['" . _TYPE . "', '" . _VALUE . "'],
					['" . _PRINCIPAL . "', intIncomeLastMonth],
					['" . _SELL_PROFIT . "', intProfitLastMonth]
				]);
				//SETTINGS THE CHART
				var optionsLastMonth = {'title':'Income Last Month', 'width':415, 'height':275};

				var chartLastMonth = new google.visualization.PieChart(document.getElementById('pieincomelastmonth'));
				chartLastMonth.draw(dataLastMonth, optionsLastMonth);
			}	
		</script>
		";

		//DELAY
		usleep($delayUTime);
		
		//LOAD GRAPH LAST YEAR
		echo "
		<script>
			var intincomeLastYear = $realincomelastyearprice;
			var intprofitLastYear = $incomeProfitLastYear;

			//LOAD GOOGLE CHARTS
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChartYearYear);

			//DRAW THE CHART AND SET THE CHART VALUES
			function drawChartYearYear() {
				var dataLastYear = google.visualization.arrayToDataTable([
					['" . _TYPE . "', '" . _VALUE . "'],
					['" . _PRINCIPAL . "', intincomeLastYear],
					['" . _SELL_PROFIT . "', intprofitLastYear]
				]);
				//SETTINGS THE CHART
				var optionsLastYear = {'title':'Income Last Year', 'width':415, 'height':275};

				var chartLastYear = new google.visualization.PieChart(document.getElementById('pieincomelastyear'));
				chartLastYear.draw(dataLastYear, optionsLastYear);
			}
		</script>
		";

		//DELAY
		usleep($delayUTime);
		
		//LOAD GRAPH SOURCE
		echo "
		<script>
			//LOAD GOOGLE CHARTS
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChartSource);

			//DRAW THE CHART AND SET THE CHART VALUES
			function drawChartSource() {
				var data = google.visualization.arrayToDataTable([
					['" . _TYPE . "', '" . _VALUE . "'], \n";
					$sourcelistpath = "././module/sourcelist.txt";
					$sourcelistcount = $lines = count(file($sourcelistpath));
					$sourceechocount = 0;
					
					$fn = fopen($sourcelistpath,"r");
					while(! feof($fn))  {
						$sourceechocount += 1;
						$resultsource = fgets($fn);
						$resultsource = str_replace("\n", "", $resultsource);
						
						//CHECK IF LAST LINE
						if ($sourceechocount != $sourcelistcount) {
							echo "					['$resultsource', $sourceResult[$sourceechocount]],\n";
						} else {
							echo "					['$resultsource', $sourceResult[$sourceechocount]]";
						}
					}
					fclose($fn);
					
				echo "]);
				//SETTINGS THE CHART
				var options = {'title':'Sold Source', 'width':415, 'height':275};

				var chart = new google.visualization.PieChart(document.getElementById('piesource'));
				chart.draw(data, options);
			}
		</script>
		";

		//DELAY
		usleep($delayUTime);

		//LOAD GRAPH CATEGORY
		echo "
		<script>
			//LOAD GOOGLE CHARTS
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChartCategory);

			//DRAW THE CHART AND SET THE CHART VALUES
			function drawChartCategory() {
				var dataCategory = google.visualization.arrayToDataTable([
					['" . _TYPE . "', '" . _VALUE . "'], \n";
					$categorylistpath = "././module/categorylist.txt";
					$categorylistcount = $lines = count(file($categorylistpath));
					$categoryechocount = 0;
					
					$fn = fopen($categorylistpath,"r");
					while(! feof($fn))  {
						$categoryechocount += 1;
						$result = fgets($fn);
						$result = str_replace("\n", "", $result);
						
						//CHECK IF LAST LINE
						if ($categoryechocount != $categorylistcount) {
							echo "					['$result', $catResult[$categoryechocount]],\n";
						} else {
							echo "					['$result', $catResult[$categoryechocount]]";
						}
					}
					fclose($fn);
					
				echo "]);
				//SETTINGS THE CHART
				var optionsCategory = {'title':'Sold Category', 'width':415, 'height':275};

				var bchart = new google.visualization.PieChart(document.getElementById('piecat'));
				bchart.draw(dataCategory, optionsCategory);
			}
		</script>
		";

		//DELAY
		usleep($delayUTime);

		//LOAD GRAPH REMAINING CATEGORY
		echo "
		<script>
			//LOAD GOOGLE CHARTS
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChartCategory);

			//DRAW THE CHART AND SET THE CHART VALUES
			function drawChartCategory() {
				var dataRemainingCategory = google.visualization.arrayToDataTable([
					['" . _TYPE . "', '" . _VALUE . "'], \n";
					$categorylistpath = "././module/categorylist.txt";
					$categorylistcount = $lines = count(file($categorylistpath));
					$categoryechocount = 0;
					
					$fn = fopen($categorylistpath,"r");
					while(! feof($fn))  {
						$categoryechocount += 1;
						$result = fgets($fn);
						$result = str_replace("\n", "", $result);
						
						//CHECK IF LAST LINE
						if ($categoryechocount != $categorylistcount) {
							echo "					['$result', $remCatResult[$categoryechocount]],\n";
						} else {
							echo "					['$result', $remCatResult[$categoryechocount]]";
						}
					}
					fclose($fn);
					
				echo "]);
				//SETTINGS THE CHART
				var optionsRemainingCategory = {'title':'Remaining Category', 'width':415, 'height':275};

				var remainingcatchart = new google.visualization.PieChart(document.getElementById('pieremainingcat'));
				remainingcatchart.draw(dataRemainingCategory, optionsRemainingCategory);
				
				//LOAD GRAPH SUCESSFULLY
				setTimeout(function(){
					hideloading();			
				}, " . _HIDE_LOADING_DELAY . ");
			}
		</script>
		";
	} else {
		//SHOW
		if ($modalWarn != "") {
			echo "\n<script>showlogin(); hideloading(); $('#modalWarning').modal();</script>";
		} else {
			echo "\n<script>showlogin(); hideloading();</script>";
		}
	}
?>