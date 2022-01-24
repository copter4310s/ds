<?php
	date_default_timezone_set('Asia/Bangkok');
	error_reporting(0);

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
	
	//GET REPORT YEAR
	$reportyear = $_POST["reportyear"];
	
	//SET VARIABLES
	$reporttb = "";
	$reportjan = 0;
	$reportfeb = 0;
	$reportmar = 0;
	$reportqu1 = 0;
	$reportavgqu1 = 0;
	$reportapr = 0;
	$reportmay = 0;
	$reportjun = 0;
	$reportqu2 = 0;
	$reportavgqu2 = 0;
	$reportjul = 0;
	$reportaug = 0;
	$reportsep = 0;
	$reportqu3 = 0;
	$reportavgqu3 = 0;
	$reportoct = 0;
	$reportnov = 0;
	$reportdec = 0;
	$reportqu4 = 0;
	$reportavgqu4 = 0;
	$reportavgall = 0;
	$reportall = 0;

	$deliveryjan = 0;
	$deliveryfeb = 0;
	$deliverymar = 0;
	$deliveryqu1 = 0;
	$deliveryavgqu1 = 0;
	$deliveryapr = 0;
	$deliverymay = 0;
	$deliveryjun = 0;
	$deliveryqu2 = 0;
	$deliveryavgqu2 = 0;
	$deliveryjul = 0;
	$deliveryaug = 0;
	$deliverysep = 0;
	$deliveryqu3 = 0;
	$deliveryavgqu3 = 0;
	$deliveryoct = 0;
	$deliverynov = 0;
	$deliverydec = 0;
	$deliveryqu4 = 0;
	$deliveryavgqu4 = 0;
	$deliveryavgall = 0;
	$deliveryall = 0;
	
	$nodata = FALSE;

	//REVERTIVE EVERY MONTH
	if ($auth AND $authuser != "guest") {
		//CHECK IF REPORTYEAR IS BLANK OR ILLEGAL VARIABLE TYPE
		settype($reportyear, "integer");
		if (trim($reportyear) == "" or gettype($reportyear) != "integer") {
			//BLANK
		} else {
			//CHECK IF reportyear IS NOT SET THEN SET TO CURRENT YEAR
			if (! isset($_POST["reportyear"])) {
				$reportyear = date("Y");
			}

			//NOT BLANK, SET CONNECTION DETAIL
			//START SECTION: SQL LOGIN
			include "./module/sqllogin.php";
			//END SECTION: SQL LOGIN
			
			//SET VARIABLES
			$connectionerror = FALSE;
			$revertiveerror = FALSE;
			$errormessage = "";

			//CREATE AND CHECK CONNECTION
			$conn = new mysqli($servername, $username, $password, $dbname);

			if ($conn->connect_error) {
				$connectionerror = TRUE;
			}

			//SET QUERY COMMAND
			$sqlreportjan = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-01%'";
			$sqlreportfeb = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-02%'";
			$sqlreportmar = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-03%'";
			$sqlreportapr = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-04%'";
			$sqlreportmay = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-05%'";
			$sqlreportjun = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-06%'";
			$sqlreportjul = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-07%'";
			$sqlreportaug = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-08%'";
			$sqlreportsep = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-09%'";
			$sqlreportoct = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-10%'";
			$sqlreportnov = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-11%'";
			$sqlreportdec = "SELECT sellPrice FROM $tb_name WHERE sellDate LIKE '%$reportyear-12%'";

			$sqldeliveryjan = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-01%'";
			$sqldeliveryfeb = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-02%'";
			$sqldeliverymar = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-03%'";
			$sqldeliveryapr = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-04%'";
			$sqldeliverymay = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-05%'";
			$sqldeliveryjun = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-06%'";
			$sqldeliveryjul = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-07%'";
			$sqldeliveryaug = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-08%'";
			$sqldeliverysep = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-09%'";
			$sqldeliveryoct = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-10%'";
			$sqldeliverynov = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-11%'";
			$sqldeliverydec = "SELECT deliveryCost FROM $tb_name WHERE sellDate LIKE '%$reportyear-12%'";
			
			$reportasjson->reportyear = $reportyear;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE JANUARY
			if ($res = mysqli_query($conn, $sqlreportjan)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportjan += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportjan = $reportjan;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE FEBUARY
			if ($res = mysqli_query($conn, $sqlreportfeb)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportfeb += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportfeb = $reportfeb;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE MARCH
			if ($res = mysqli_query($conn, $sqlreportmar)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportmar += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportmar = $reportmar;

			//DELAY
			usleep($delayUTime);
			
			//CALCULATE FOR FIRST QUARTER
			$reportqu1 = ($reportjan + $reportfeb + $reportmar);
			$reportasjson->reportqu1 = $reportqu1;

			//CALCULATE FOR AVERAGE FIRST QUARTER
			$reportavgqu1 = ($reportqu1 / 3);
			$reportasjson->reportavgqu1 = $reportavgqu1;
			usleep($delayUTime);
			
			//REVERTIVE APRIL
			if ($res = mysqli_query($conn, $sqlreportapr)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportapr += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportapr = $reportapr;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE MAY
			if ($res = mysqli_query($conn, $sqlreportmay)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportmay += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportmay = $reportmay;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE JUNE
			if ($res = mysqli_query($conn, $sqlreportjun)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportjun += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportjun = $reportjun;

			//DELAY
			usleep($delayUTime);
			
			//CALCULATE FOR SECOND QUARTER
			$reportqu2 = ($reportapr + $reportmay + $reportjun);
			$reportasjson->reportqu2 = $reportqu2;

			//CALCULATE FOR AVERAGE SECOND QUARTER
			$reportavgqu2 = ($reportqu2 / 3);
			$reportasjson->reportavgqu2 = $reportavgqu2;
			usleep($delayUTime);

			//REVERTIVE JULY
			if ($res = mysqli_query($conn, $sqlreportjul)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportjul += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportjul = $reportjul;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE AUGUST
			if ($res = mysqli_query($conn, $sqlreportaug)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportaug += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportaug = $reportaug;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE SEPTEMBER
			if ($res = mysqli_query($conn, $sqlreportsep)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportsep += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportsep = $reportsep;

			//DELAY
			usleep($delayUTime);
			
			//CALCULATE FOR THIRD QUARTER
			$reportqu3 = ($reportjul + $reportaug + $reportsep);
			$reportasjson->reportqu3 = $reportqu3;

			//CALCULATE FOR AVERAGE THIRD QUARTER
			$reportavgqu3 = ($reportqu3 / 3);
			$reportasjson->reportavgqu3 = $reportavgqu3;
			usleep($delayUTime);

			//REVERTIVE OCTOBER
			if ($res = mysqli_query($conn, $sqlreportoct)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportoct += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportoct = $reportoct;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE NOVEMBER
			if ($res = mysqli_query($conn, $sqlreportnov)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportnov += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportnov = $reportnov;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE DECEMBER
			if ($res = mysqli_query($conn, $sqlreportdec)) { 
				while ($row = mysqli_fetch_array($res)) {
					$reportdec += $row['sellPrice'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->reportdec = $reportdec;

			//DELAY
			usleep($delayUTime);
			
			//CALCULATE FOR FORTH QUARTER
			$reportqu4 = ($reportoct + $reportnov + $reportdec);
			$reportasjson->reportqu4 = $reportqu4;
			
			//CALCULATE FOR AVERAGE FORTH QUARTER
			$reportavgqu4 = ($reportqu4 / 3);
			$reportasjson->reportavgqu4 = $reportavgqu4;
			usleep($delayUTime);
			
			//CALCULATE FOR ALL QUARTER
			$reportall = ($reportqu1 + $reportqu2 + $reportqu3 + $reportqu4);
			$reportasjson->reportall = $reportall;
			usleep($delayUTime);
			
			//CALCULATE FOR AVERAGE ALL QUARTER
			$reportavgall = ($reportall / 12);
			$reportasjson->reportavgall = $reportavgall;
			usleep($delayUTime);

			//REVERTIVE DELIVERY COST JANUARY
			if ($res = mysqli_query($conn, $sqldeliveryjan)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliveryjan += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliveryjan = $deliveryjan;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE DELIVERY COST FEBUARY
			if ($res = mysqli_query($conn, $sqldeliveryfeb)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliveryfeb += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliveryfeb = $deliveryfeb;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE DELIVERY COST MARCH
			if ($res = mysqli_query($conn, $sqldeliverymar)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliverymar += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliverymar = $deliverymar;

			//DELAY
			usleep($delayUTime);
			
			//CALCULATE FOR FIRST QUARTER
			$deliveryqu1 = ($deliveryjan + $deliveryfeb + $deliverymar);
			$reportasjson->deliveryqu1 = $deliveryqu1;

			//CALCULATE FOR AVERAGE FIRST QUARTER
			$deliveryavgqu1 = ($deliveryqu1 / 3);
			$reportasjson->deliveryavgqu1 = $deliveryavgqu1;
			usleep($delayUTime);
			
			//REVERTIVE DELIVERY COST APRIL
			if ($res = mysqli_query($conn, $sqldeliveryapr)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliveryapr += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliveryapr = $deliveryapr;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE DELIVERY COST MAY
			if ($res = mysqli_query($conn, $sqldeliverymay)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliverymay += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliverymay = $deliverymay;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE DELIVERY COST JUNE
			if ($res = mysqli_query($conn, $sqldeliveryjun)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliveryjun += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliveryjun = $deliveryjun;

			//DELAY
			usleep($delayUTime);
			
			//CALCULATE FOR SECOND QUARTER
			$deliveryqu2 = ($deliveryapr + $deliverymay + $deliveryjun);
			$reportasjson->deliveryqu2 = $deliveryqu2;

			//CALCULATE FOR AVERAGE SECOND QUARTER
			$deliveryavgqu2 = ($deliveryqu2 / 3);
			$reportasjson->deliveryavgqu2 = $deliveryavgqu2;
			usleep($delayUTime);

			//REVERTIVE DELIVERY COST JULY
			if ($res = mysqli_query($conn, $sqldeliveryjul)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliveryjul += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliveryjul = $deliveryjul;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE DELIVERY COST AUGUST
			if ($res = mysqli_query($conn, $sqldeliveryaug)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliveryaug += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliveryaug = $deliveryaug;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE DELIVERY COST SEPTEMBER
			if ($res = mysqli_query($conn, $sqldeliverysep)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliverysep += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliverysep = $deliverysep;

			//DELAY
			usleep($delayUTime);
			
			//CALCULATE FOR THIRD QUARTER
			$deliveryqu3 = ($deliveryjul + $deliveryaug + $deliverysep);
			$reportasjson->deliveryqu3 = $deliveryqu3;

			//CALCULATE FOR AVERAGE THIRD QUARTER
			$deliveryavgqu3 = ($deliveryqu3 / 3);
			$reportasjson->deliveryavgqu3 = $deliveryavgqu3;
			usleep($delayUTime);

			//REVERTIVE DELIVERY COST OCTOBER
			if ($res = mysqli_query($conn, $sqldeliveryoct)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliveryoct += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliveryoct = $deliveryoct;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE DELIVERY COST NOVEMBER
			if ($res = mysqli_query($conn, $sqldeliverynov)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliverynov += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliverynov = $deliverynov;

			//DELAY
			usleep($delayUTime);
			
			//REVERTIVE DELIVERY COST DECEMBER
			if ($res = mysqli_query($conn, $sqldeliverydec)) { 
				while ($row = mysqli_fetch_array($res)) {
					$deliverydec += $row['deliveryCost'];
				}
			} else { 
				//ERROR
				$revertiveerror = TRUE;
				$errormessage = $conn->error;
			}
			$reportasjson->deliverydec = $deliverydec;

			//DELAY
			usleep($delayUTime);
			
			//CALCULATE FOR FORTH QUARTER
			$deliveryqu4 = ($deliveryoct + $deliverynov + $deliverydec);
			$reportasjson->deliveryqu4 = $deliveryqu4;
			
			//CALCULATE FOR AVERAGE FORTH QUARTER
			$deliveryavgqu4 = ($deliveryqu4 / 3);
			$reportasjson->deliveryavgqu4 = $deliveryavgqu4;
			usleep($delayUTime);
			
			//CALCULATE FOR ALL QUARTER
			$deliveryall = ($deliveryqu1 + $deliveryqu2 + $deliveryqu3 + $deliveryqu4);
			$reportasjson->deliveryall = $deliveryall;
			usleep($delayUTime);

            //CALCULATE FOR AVERAGE ALL QUARTER
			$deliveryavgall = ($deliveryall / 12);
			$reportasjson->deliveryavgall = $deliveryavgall;
			usleep($delayUTime);
			
			//CHECK ERROR STATE
			if ($connectionerror == TRUE) {
				//CONNECTION ERROR
				$modalWarn = _ERROR_DB_CONNECT . "<br>" . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8');
			} else if ($revertiveerror == TRUE) {
				//REVERTIVE ERROR
				$modalWarn = _ERROR_WHILE_UPDATE_DATA . "<br>" . htmlspecialchars($errormessage, ENT_QUOTES, 'UTF-8');
			} else if ($reportall == 0 AND $reportavgall == 0 AND $reportqu1 == 0 AND $reportqu2 == 0 AND $reportqu3 == 0 AND $reportqu4 == 0) {
				//ZERO DATA RETURN
				$nodata = TRUE;
				$modalWarn = _WARN_NO_DATA_FOUND;
			} else {
				//NO ERROR FOUND (SUCCESSFULLY)
			}
			
			//CLOSE CONNECTION
			$conn->close();
			
			//JSON ENCODE FOR PRINTING AND FREE MEMORY
			$encodedreport = json_encode($reportasjson);
			$reportasjson = null;
			$encodedreport = base64_encode($encodedreport);
		}
	} else {
		//LOGIN ERROR, CHECK IF ADMINPASSWORD IS BLANK
		if ($adminpassword == "da39a3ee5e6b4b0d3255bfef95601890afd80709") {
			//BLANK, NOTHING
		} else {
			//SHOW NOT AUTH
			$modalWarn = _ENTER_PASSWORD_CORRECTLY;
			
			//SAVE AUTH FAIL
			date_default_timezone_set('Asia/Bangkok');
			$date = date("Y-m-d");
			$time = date("H:i:s");
			$page = "allmonthreport.php";
			
		
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
		}
	}
?>

<html>
	<head>
		<title><?= _MONTHLY_SALES_SUMMARY ?></title>
		<!-- BOOTSTRAP -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

		<link rel='stylesheet' href='./main.css' type='text/css' />
		<link rel='shortcut icon' type='img/icon' href='favicon.ico'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script src='js/pzDS_add.js'></script>
		<script src='js/main.js'></script>
		<script>
			function hideloading() {
				setTimeout(function(){
					var auth = "<?= str_replace("==", "", base64_encode(sha1($authString))); ?>";
					var authuser = "<?= str_replace("==", "", base64_encode(sha1($authuser))); ?>";
					
					if (auth != "OTdjZGJkYzdmZWZmODI3ZWZiMDgyYTZiNmRkMjcyNzIzN2NkNDlmZA" && authuser != "MzU2NzVlNjhmNGI1YWY3Yjk5NWQ5MjA1YWQwZmM0Mzg0MmYxNjQ1MA") {
						//WAITING FOR PAGE TO LOAD COMPLETE
						var intervalLoad = setInterval(function() {
							if (document.readyState === 'complete') {
								clearInterval(intervalLoad);
					
								setTimeout(function(){
									document.getElementById('noprint').style.display = 'block';	
									document.getElementById('loading').style.display = 'none';
								}, <?= _HIDE_LOADING_DELAY ?>);
								
								clearTimeout(waittimeout);
							}    
						}, 300);
			
						//WAITING FOR PAGE TO LOAD COMPLETE TIMEOUT (10 SECONDS)
						var waittimeout = setTimeout(function(){
							clearInterval(intervalLoad);
							document.getElementById('noprint').style.display = 'block';	
							document.getElementById('loading').style.display = 'none';
						}, <?= _HIDE_LOADING_TIMEOUT ?>);
					} else {
						document.getElementById('noprint').style.display = 'none';	
						document.getElementById('loading').style.display = 'none';
					}
				}, <?= _HIDE_LOADING_DELAY ?>);
			}
		</script>
	</head>
	<body>
		<div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-success navbar-light fixed-top">
                <img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b><?= _DATA_SYSTEMS ?></b></font></span>
            </nav>
            <br/>
            <div class="container-fluid">
                <div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;" id="adminlogin">
                    <div>
                        <center><font size="5"><b><?= _LOGIN_MONTHLY_SALES_SUMMARY ?></b></font></center>
                    </div>
					<div style="padding: 8px;"></div>
					<div class="text-center">
						<font size="2">
							<?= _LOGIN_MONTHLY_SALES_SUMMARY . ", " . _ENTER_PASSWORD_CORRECTLY ?>
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
							<div class="pt-3">
								<center>
									<font size="2"><?= _ENTER_ALL_MONTH_REPORT_BY_PAGE ?></font>
								</center>
							</div>
							<input type="hidden" name="reportyear" value="<?= date("Y", time()); ?>" />
                        </form>
                    </div>
                </div>
            </div>
			<!-- -->
			<center>
				<div class="container-fluid" id="maintb" style="display:none; margin-top:54px;">
					<div>
						<font size="5">
							<b><?= _MONTHLY_SALES_SUMMARY ?></b>
						</font>
					</div>
					<div class="container-md pt-2 pl-0 pr-0">
						<div class="pb-2">
							<font size="3">
									<?= _CURRENTLY_VIEW_YEAR ?> : <?= $reportyear; ?>
							</font>
						</div>
						<div class="pb-2">
							<table class="table table-bordered" style="margin-bottom:0;">
								<thead>
									<tr class="table-active text-center">
										<th width="33%">
											<font size="4"><?= _MONTH ?></font>
										</th>
										<th width="33.5%">
											<font size="4"><?= _INCOME ?></font>
										</th>
										<th width="33.5%">
											<font size="4"><?= _DELIVERY_COST ?></font>
										</th>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _JANUARY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportjan); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryjan); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _FEBUARY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportfeb); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryfeb); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _MARCH ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportmar); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliverymar); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _APRIL ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportapr); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryapr); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _MAY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportmay); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliverymay); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _JUNE ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportjun); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryjun); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _JULY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportjul); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryjul); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _AUGUST ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportaug); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryaug); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _SEPTEMBER ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportsep); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliverysep); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _OCTOBER ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportoct); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryoct); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _NOVEMBER ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportnov); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliverynov); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _DECEMBER ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportdec); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliverydec); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _TOTAL_FIRST_QUARTER ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportqu1); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryqu1); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _TOTAL_SECOND_QUARTER ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportqu2); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryqu2); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _TOTAL_THIRD_QUARTER ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportqu3); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryqu3); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="text-center">
										<td>
											<font size="3"><?= _TOTAL_FORTH_QUARTER ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportqu4); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryqu4); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
									<tr class="table-active text-center">
										<td>
											<font size="3"><?= _TOTAL ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($reportall); ?> <?= _CURRENCY ?></font>
										</td>
										<td>
											<font size="3"><?= number_format($deliveryall); ?> <?= _CURRENCY ?></font>
										</td>
									</tr>
								</thead>
							</table>
						</div>
						<div>
							<table border="0">
								<tr class="text-center">
									<td>
										<font size="3"><?= _AVERAGE_SALARY_FIRST_QUARTER ?></font>
									</td>
									<td>
										<div class="p-3"></div>
									</td>
									<td>
										<font size="3"><?= number_format( $reportavgqu1, 2 ); ?> <?= _CURRENCY ?></font>
									</td>
								</tr>
								<tr class="text-center">
									<td>
										<font size="3"><?= _AVERAGE_SALARY_SECOND_QUARTER ?></font>
									</td>
									<td>
										<div class="p-3"></div>
									</td>
									<td>
										<font size="3"><?= number_format( $reportavgqu2, 2 ); ?> <?= _CURRENCY ?></font>
									</td>
								</tr>
								<tr class="text-center">
									<td>
										<font size="3"><?= _AVERAGE_SALARY_THIRD_QUARTER ?></font>
									</td>
									<td>
										<div class="p-3"></div>
									</td>
									<td>
										<font size="3"><?= number_format( $reportavgqu3, 2 ); ?> <?= _CURRENCY ?></font>
									</td>
								</tr>
								<tr class="text-center">
									<td>
										<font size="3"><?= _AVERAGE_SALARY_FORTH_QUARTER ?></font>
									</td>
									<td>
										<div class="p-3"></div>
									</td>
									<td>
										<font size="3"><?= number_format( $reportavgqu4, 2 ); ?> <?= _CURRENCY ?></font>
									</td>
								</tr>
								<tr class="text-center">
									<td>
										<font size="3"><?= _AVERAGE_SALARY_ALL_YEAR ?></font>
									</td>
									<td>
										<div class="p-3"></div>
									</td>
									<td>
										<font size="3"><?= number_format( $reportavgall, 2 ); ?> <?= _CURRENCY ?></font>
									</td>
								</tr>
							</table>
						</div>
						<hr>
						<div class="pb-4">
							<div id="allmonthgraph">

							</div>
						</div>
					</div>
				</div>
				<div class="p-3"></div>
			</center>
			<div style="position: fixed; right: 25px; bottom: 55px; display: none;" id="noprint" class="fadein">
				<center>
					<form action="printview.php" method="POST" target="_blank" style="margin-block-end: 0px;">
						<input type="hidden" name="token" value="<?= $totoken; ?>" />
						<input type='hidden' name='allmonthreport' value='<?= $encodedreport; ?>' />
						<input type="hidden" name="loadgraphmonth" value="1" />
						<?php if (! $revertiveerror AND ! $connectionerror AND ! $nodata) { echo "\n<button class='printbutton' id='printButton' style='font-size: 13px;' type='submit'>" . _PRINT_THIS . "</button>"; } ?>
					</form>
				</center>
			</div>
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
		<noscript><img id='blockanother' width='100%' height='100%'><table border='1' id='tbjavascript' class='tbinfo'><tr><td class='curve'><div style='padding:28px 28px 28px 28px;'><font size='4'><strong><center><?=_ERROR_ENABLE_JS ?><br></center><img src='/blank.png' height='32' /><br></strong><center><a class='myButton' target='_blank' href='<?= _ENABLE_JS_SITE ?>'><?= _CONTINUE ?></a></center></font></div></td></tr></table></noscript>
	</body>
</html>
<?php
	//CONTROL LOGIN FORM
	if ($auth AND $authuser != "guest") {
		//HIDE
		if ($modalWarn != "") {
			echo "\n<script>hidelogin(); $('#modalWarning').modal(); hideloading();</script>";
		} else {
			echo "\n<script>hidelogin();  hideloading();</script>";
		}
	} else {
		//SHOW
		if ($modalWarn != "") {
			echo "\n<script>showlogin(); hideloading(); $('#modalWarning').modal();</script>";
		} else {
			echo "\n<script>showlogin(); hideloading();</script>";
		}
	}

	//LOAD GRAPH
	echo "
	<script type='text/javascript'>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['" . _MONTH . "', '" . _INCOME . "', '" . _DELIVERY_COST . "'],
          ['1',  $reportjan, $deliveryjan],
          ['2',  $reportfeb, $deliveryfeb],
		  ['3',  $reportmar, $deliverymar],
		  ['4',  $reportapr, $deliveryapr],
		  ['5',  $reportmay, $deliverymay],
		  ['6',  $reportjun, $deliveryjun],
		  ['7',  $reportjul, $deliveryjul],
		  ['8',  $reportaug, $deliveryaug],
		  ['9',  $reportsep, $deliverysep],
		  ['10', $reportoct, $deliveryoct],
		  ['11', $reportnov, $deliverynov],
          ['12', $reportdec, $deliverydec]
        ]);

        var options = {
			title: '" . _ALL_MONTH_REPORT_OF_YEAR . " $reportyear',
			//curveType: 'function',
			legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('allmonthgraph'));

        chart.draw(data, options);
      }
    </script>";
?>