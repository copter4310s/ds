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
	
	
	$getmonth = $_POST["month"];
	$getmonthyear = $_POST["monthyear"];
	$monthmode = $_POST["monthmode"];
	$custommonth = FALSE;
	$monthdata = date("Y", time());
	
	$findList = $_POST["findList"];
	$dofindList = FALSE;
	
	$customcom = $_POST["customcom"];
	$customcom = str_replace("(ฟ", "\"", $customcom);
	$customcomm = FALSE;
	
	$allmonthreport = $_POST["allmonthreport"];
	$usemonthreport = FALSE;
	
	$adminpassword = sha1($_POST["adminpassword"]);
	$token = base64_decode($_POST["token"]);
	
	$usermode = "ordermode";
	$userstringmode = "ordermode=highid";
	$userstring = "highid";

	//LOAD GRAPH
	$loadgraph = $_POST["loadgraph"];
	settype($loadgraph, "integer");
	if ($loadgraph == 1) {
		$loadgraph = TRUE;
	} else {
		$loadgraph = FALSE;
	}

	//LOAD GRAPH MONTH
	$loadgraphmonth = $_POST["loadgraphmonth"];
	settype($loadgraphmonth, "integer");
	if ($loadgraphmonth == 1) {
		$loadgraphmonth = TRUE;
	} else {
		$loadgraphmonth = FALSE;
	}
	
	//CHECk AUTH AND CHECK LISTING COMMAND
	if ($auth AND $authuser != "guest") {
		$graphtime = date('Y', time());
		
		if ($monthmode != "sell" and $monthmode != "buy") {
			$monthmode = "sell";
		}
		
		//CHECK IF CUSTOM MONTH IS NOTHING
		if ($getmonth == "" or $getmonthyear == "") {
			$month = date("m", time());
			$monthyear = date("Y", time());
		} else {
			$custommonth = TRUE;
			$month = $getmonth;
			$monthyear = $getmonthyear;
			$monthdata = "$monthyear-$month";
			
			$usermode = "custommonth";
			$userstringmode = "month=$month&monthyear=$monthyear";
			
			$graphtime = $monthdata;

			//PREVENT SQL INJECTION (WHEN QUERY FOR GRAPH DATA)
			if ($monthmode == "sell") {
				
			} else {
				$monthmode = "buy";
			}
			
			/* REMOVED VERSION 3.0.0 //CHECK IF NEED sellDate AND SET LOAD GRAPH TIME
			if ($monthmode == "sell") {
				//SELL VIEW
				$graphtime = $monthdata;
			}*/
		}
		
		//CHECK IF FIND LIST IS NULL
		if ($findList == NULL) {
		
		} else {
			$dofindList = TRUE;
			
			$findList = str_replace("+", "", $findList);
			$findList = str_replace("/", "", $findList);
			$findList = str_replace("\\", "", $findList);
			$findList = str_replace("*", "", $findList);
			$findList = str_replace("\"", "'", $findList);
			$findList = str_replace("`", "", $findList);
			
			$findList = str_replace(";", "", $findList);
			
			$findList = strtolower($findList);
			
			$usermode = "findList";
			$userstringmode = "findList=$findList";
			$userstring = $findList;
		}
		
		//CHECK IF CUSTOMCOM IS NULL
		if ($customcom == NULL) {
		
		} else {
			//CHECK IF USE TWO COMMANDS
			if (strpos($customcom, ";") !== FALSE or strpos($customcom, "U+0003B") !== FALSE or strpos($customcom, "&#x3b;") !== FALSE or strpos($customcom, "&#59;") !== FALSE or strpos($customcom, "&SEMI;") !== FALSE or strpos($customcom, "\003B") !== FALSE or strpos($customcom, "LEFT JOIN") !== FALSE or strpos($customcom, "RIGHT JOIN") !== FALSE or strpos($customcom, "UPDATE") !== FALSE or strpos($customcom, "DELETE") !== FALSE or strpos($customcom, "TRUNCATE") !== FALSE or strpos($customcom, "CHANGE") or strpos($customcom, "DROP") !== FALSE or strpos($customcom, "EMPTY") !== FALSE or strpos($customcom, "AAAAA") !== FALSE or strpos($customcom, "UNION") !== FALSE or strpos($customcom, "ALTER") !== FALSE or strpos($customcom, "CREATE") !== FALSE or strlen($customcom) >= 400) {
				$usermode = "ordermode";
				$userstringmode = "ordermode=highid";
				$userstring = "highid";
			} else {
				$customcomm = TRUE;
			
				$usermode = "customcom";
				$userstringmode = "customcom=$customcom";
				$userstring = $customcom;
			}
		}
		
		//CHECK IF ALL MONTH REPORT IS BLANK
		if (trim($allmonthreport) == "Im51bGwi" or trim($allmonthreport) == "") {
			//BLANK
		} else {
			//USE ALL MONTH REPORT
			$usemonthreport = TRUE;
			$decodejson = json_decode(base64_decode($allmonthreport));
			
			$usermode = "allmonthreport";
			$userstringmode = "allmonthreport=$allmonthreport";
			$userstring = $allmonthreport;
			
			$graphtime = $decodejson->reportyear;
		}
		
		//DELAY
		usleep($delayUTime);
	}
	
	//LOAD GRAPH
	if ($auth AND $authuser != "guest" AND $loadgraph) {
		//SET CONNECTION DETAILS, CREATE AND CHECK CONNECTION
		//START SECTION: SQL LOGIN
		include "./module/sqllogin.php";
		//END SECTION: SQL LOGIN
		
		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			$connectionerror = TRUE;
		}
		
		//SET VARIABLES
		$sourceError = FALSE;
		$catError = FALSE;
		
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
			$sqlsource = "SELECT id FROM $tb_name WHERE sellSource='$resultsource' AND " . $monthmode . "Date LIKE '%{$graphtime}%'";
			if ($res = mysqli_query($conn, $sqlsource)) { 
				$sourceResult[$sourcequerycount] = mysqli_num_rows($res);
			} else { 
				//ERROR
				$sourceError = TRUE;
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
		while(! feof($fn)) {
			$categoryquerycount += 1;
			$result1 = fgets($fn);
			$result1 = str_replace("\n", "", $result1);
			
			//GET CATEGORY
			$sqlcat = "SELECT id FROM $tb_name WHERE category='$result1' AND " . $monthmode . "Date LIKE '%{$graphtime}%' AND avalible=0";
			if ($res = mysqli_query($conn, $sqlcat)) { 
				$catResult[$categoryquerycount] = mysqli_num_rows($res);
			} else { 
				//ERROR
				$catError = TRUE;
			}
		}
		fclose($fn);

		//CHECK IF ERROR
		if ($catError OR $sourceError) {
			$modalWarn = _ERROR_WHILE_LOAD_DATA_FOR_GRAPH . "<br>" . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8');
		}

		//CLOSE CONNECTION
		$conn->close();

		//DELAY
		usleep($delayUTime);
	}
?>

<html>
	<head>
		<title><?= _PRINT_DATA ?></title>
		<!-- BOOTSTRAP -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

		<link rel="stylesheet" href="./main.css" type="text/css" />
		<link rel="shortcut icon" type="img/icon" href="favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=0.32">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<script src='js/pzDS_add.js'></script>
		<script src='js/main.js'></script>
		<script src='js/pzDS_printview.js'></script>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script>
			function ahideloading() {
				setTimeout(function(){
					var auth = "<?= str_replace("==", "", base64_encode(sha1($authString))); ?>";
					var authuser = "<?= str_replace("==", "", base64_encode(sha1($authuser))); ?>";
					//document.getElementById('loading').style.display = 'none';
					
					if (auth != "OTdjZGJkYzdmZWZmODI3ZWZiMDgyYTZiNmRkMjcyNzIzN2NkNDlmZA" && authuser != "MzU2NzVlNjhmNGI1YWY3Yjk5NWQ5MjA1YWQwZmM0Mzg0MmYxNjQ1MA") {
						//WAITING FOR PAGE TO LOAD COMPLETE
						var intervalLoad = setInterval(function() {
							if (document.readyState === 'complete') {
								clearInterval(intervalLoad);
					
								setTimeout(function(){
									try {
										document.getElementById('noprint').style.display = 'block';	
									} catch {

									}
									document.getElementById('loading').style.display = 'none';			
								}, <?= _HIDE_LOADING_DELAY ?>);
								
								clearTimeout(waittimeout);
							}    
						}, 300);
			
						//WAITING FOR PAGE TO LOAD COMPLETE TIMEOUT (10 SECONDS)
						var waittimeout = setTimeout(function(){
							clearInterval(intervalLoad);
							try {
								document.getElementById('noprint').style.display = 'block';	
							} catch {

							}
							document.getElementById('loading').style.display = 'none';
							document.getElementById('graphload').style.display = 'none';							
						}, <?= _HIDE_LOADING_TIMEOUT ?>);
					} else {
						try {
							document.getElementById('noprint').style.display = 'none';	
						} catch {

						}
						document.getElementById('loading').style.display = 'none';
					}
				}, <?= _HIDE_LOADING_DELAY ?>);
			}
		</script>
	</head>
	<body>
		<div class="blockall" id="loading" style="display:block;">
			<div class="center">
			<img src="wheel.svg" width="48" height="48" />
			</div>
		</div>
		<div id="printthis">
			<?php
				if ($auth and $authuser != "guest") {
					echo "<div style='padding: 4px 12px 0px 12px;'>
				<center>
					<table border='0'>
						<tr>
							<td>
								<a href='#' onclick='showPrintbtn()'><img src='logo.png' width='192' height='192' style='border-radius: 36px;' /></a>
							</td>
							<td>
								<div class='p-3'></div>
							</td>
							<td>
								<font size='6'><strong>" . _DATA_SYSTEMS . "</strong></font>
							</td>
						</tr>
					</table>
				</center>
			</div>
			<div>
				<div class='pb-2'></div>
			</div>\n";
				}
			?>
			<div class="container-fluid">
                <div class="center p-4" style="border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;" id="adminlogin">
                    <div>
                        <center><font size="5"><b><?= _LOGIN_PRINT_DATA ?></b></font></center>
                    </div>
					<div style="padding: 8px;"></div>
					<div class="text-center">
						<font size="2">
							<?= _LOGIN_PRINT_DATA . ", " . _ENTER_PASSWORD_CORRECTLY ?>
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
									<font size="2"><?= _ENTER_PRINT_VIEW_BY_PAGE ?></font>
								</center>
							</div>
							<input type="hidden" name="reportyear" value="<?= date("Y", time()); ?>" />
                        </form>
                    </div>
                </div>
            </div>
			<!--  -->
			<center>
				<div>
					<?php
						$connectionerror = FALSE;
						$revertiveError = FALSE;
						$ordermode = $_POST["ordermode"];
	
						//REVERTIVE DATA
						if ($auth and $authuser != "guest" and ! $usemonthreport) {
							//LOAD GRAPH
							if ($loadgraph) {
								echo "<div id='graphloadhere'>
								<table>
								<tr>
								<td>
								<center>
								<div id='piecat'></div>
								</center>
								</td>
								<td>
								<center>
								<div id='piesource'></div>
								</center>
								</td>
								</tr>
								</table>
								</div>";
							}
							
							//AUTH, SET CONNECTION DETAIL
							//START SECTION: SQL LOGIN
							include "./module/sqllogin.php";
							//END SECTION: SQL LOGIN

							$conn = new mysqli($servername, $username, $password, $dbname);
		
							if ($conn->connect_errno) {
								$connectionerror = TRUE;
							}
		
							$sqlcom = "SELECT * FROM $tb_name ORDER BY id DESC";
							$datalimit = _PRINT_DATA_LIMIT;

							usleep($delayUTime);
							
							//CHECK IF USER USE CUSTOMCOM
							if ($customcomm == TRUE) {
								//CUSTOM
								if (strpos($customcom, "LIMIT") !== FALSE) {
									$sqlcom = "SELECT * FROM $tb_name $customcom";
								} else {
									$sqlcom = "SELECT * FROM $tb_name $customcom LIMIT 0, $datalimit";
								}
								
								$sqlcomcheck = strtoupper($sqlcom);
								
								if (strpos($sqlcomcheck, ";") !== FALSE or strpos($sqlcomcheck, "U+0003B") !== FALSE or strpos($sqlcomcheck, "&#x3b;") !== FALSE or strpos($sqlcomcheck, "&#59;") !== FALSE or strpos($sqlcomcheck, "&SEMI;") !== FALSE or strpos($sqlcomcheck, "\003B") !== FALSE or strpos($sqlcomcheck, "LEFT JOIN") !== FALSE or strpos($sqlcomcheck, "RIGHT JOIN") !== FALSE or strpos($sqlcomcheck, "UPDATE") !== FALSE or strpos($sqlcomcheck, "DELETE") !== FALSE or strpos($sqlcomcheck, "TRUNCATE") !== FALSE or strpos($sqlcomcheck, "CHANGE") or strpos($sqlcomcheck, "DROP") !== FALSE or strpos($sqlcomcheck, "EMPTY") !== FALSE or strpos($sqlcomcheck, "AAAAA") !== FALSE or strpos($sqlcomcheck, "UNION") !== FALSE or strpos($sqlcomcheck, "ALTER") !== FALSE or strpos($sqlcomcheck, "CREATE") !== FALSE or strlen($sqlcomcheck) >= 400) {
									//TWO COMMAND FOUND
									$sqlcom = "SELECT * FROM $tb_name ORDER BY id DESC LIMIT 0, $datalimit";
									$usermode = "ordermode";
									$userstringmode = "ordermode=highid";
									$userstring = "highid";
								}
							} else {
								//CHECK IF FIND LIST
								if (! $dofindList) {
									//CHECK IF ONLY MONTH DATA
									if (! $custommonth) {
										if ($ordermode == "") {
											//DEFAULT MODE
											$userstringmode = "ordermode=highid";
											$sqlcom = "SELECT * FROM $tb_name ORDER BY id DESC LIMIT 0, $datalimit";
											$userstring = "highid";
										} else if ($ordermode == "newdate") {
											//LASTED DATE TO OLDEST DATE
											$sqlcom = "SELECT * FROM $tb_name ORDER BY sellDate DESC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=newdate";
											$userstring = "newdate";
										} else if ($ordermode == "olddate") {
											//OLDEST DATE TO LASTED DATE
											$sqlcom = "SELECT * FROM $tb_name ORDER BY sellDate ASC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=olddate";
											$userstring = "olddate";
										} else if ($ordermode == "lowsellprice") {
											//LOWEST SELLPRICE TO HIGHEST SELLPRICE
											$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY sellPrice ASC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=lowsellprice";
											$userstring = "lowsellprice";
										} else if ($ordermode == "highsellprice") {
											//HIGHEST SELLPRICE TO LOWEST SELLPRICE
											$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY sellPrice DESC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=highsellprice";
											$userstring = "highsellprice";
										} else if ($ordermode == "lowsellprofit") {
											//LOWEST SELLPROFIT TO HIGHEST SELLPROFIT
											$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY sellProfit ASC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=lowsellprofit";
											$userstring = "lowsellprofit";
										} else if ($ordermode == "highsellprofit") {
											//HIGHEST SELLPROFIT TO LOWEST SELLPROFIT
											$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY sellProfit DESC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=highsellprofit";
											$userstring = "highsellprofit";
										} else if ($ordermode == "lowdeliverycost") {
											//LOWEST DELIVERY COST TO HIGHEST DELIVRY COST
											$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY deliveryCost ASC";
											$userstringmode = "ordermode=lowdeliverycost";
											$userstring = "lowdeliverycost";
										} else if ($ordermode == "highdeliverycost") {
											//HIGHEST DELIVERY COST TO LOWEST DELIVERY COST
											$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY deliveryCost DESC";
											$userstringmode = "ordermode=highdeliverycost";
											$userstring = "highdeliverycost";
										} else if ($ordermode == "lowid") {
											//LOWEST ID TO HIGHEST ID
											$sqlcom = "SELECT * FROM $tb_name ORDER BY id ASC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=lowid";
											$userstring = "lowid";
										} else if ($ordermode == "highid") {
											//HIGHEST ID TO LOWEST ID
											$sqlcom = "SELECT * FROM $tb_name ORDER BY id DESC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=highid";
											$userstring = "highid";
										} else if ($ordermode == "newbuydate") {
											//LASTED DATE TO OLDEST DATE
											$sqlcom = "SELECT * FROM $tb_name ORDER BY buyDate DESC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=newbuydate";
											$userstring = "newbuydate";
										} else if ($ordermode == "oldbuydate") {
											//OLDEST DATE TO LASTED DATE
											$sqlcom = "SELECT * FROM $tb_name ORDER BY buyDate ASC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=oldbuydate";
											$userstring = "oldbuydate";
										} else if ($ordermode == "avaliblelist") {
											//SHOW ONLY AVALIBLE LIST
											$sqlcom = "SELECT * FROM $tb_name WHERE avalible=1 ORDER BY id DESC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=avaliblelist";
											$userstring = "avaliblelist";
										} else if ($ordermode == "selllist") {
											//SHOW ONLY NOT AVALIBLE LIST
											$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY id DESC LIMIT 0, $datalimit";
											$userstringmode = "ordermode=selllist";
											$userstring = "selllist";
										} else {
											//DEFAULT MODE
											$userstringmode = "ordermode=highid";
											$sqlcom = "SELECT * FROM $tb_name ORDER BY id DESC LIMIT 0, $datalimit";
											$userstring = "highid";
										}
									} else {
										//CHECK IF NEED buyDate OR sellDate
										if ($monthmode == "sell") {
											//SELL VIEW
											$sqlcom = "SELECT * FROM $tb_name WHERE sellDate LIKE '%{$monthdata}%' ORDER BY id DESC LIMIT 0, $datalimit";
										} else {
											//BUY VIEW
											$sqlcom = "SELECT * FROM $tb_name WHERE buyDate LIKE '%{$monthdata}%' ORDER BY id DESC LIMIT 0, $datalimit";
										}
									}
								} else {
									//FIND LIST
									$sqlcom = "SELECT * FROM $tb_name WHERE lower(list) LIKE \"%$findList%\" ORDER BY id DESC LIMIT 0, $datalimit";
								}
							}
		
							$result = mysqli_query($conn, $sqlcom);
		
							if(! $result) {
								$revertiveError = TRUE;
							}
							
							$totalbuyPrice = 0;
							$totalsellPrice = 0;
							$totalsellProfit = 0;
							$totaldeliveryCost = 0;
		
							echo "<div style='padding: 0px 12px 0px 12px;' class='container-fluid'>
						<table class='table table-bordered w-auto' id='tabledata maintb'>
							<thead>
								<tr class='table-active'>
									<th class='text-center align-middle'><font size='3'><div class='abc'>ID</div></font></th>
									<th class='text-center align-middle'><font size='3'><div class='abc'>" . _BUY_DATE . "</div></font></th>
									<th class='text-center align-middle'><font size='3'><div class='abc'>" . _SELL_DATE . "</div></font></th>
									<th class='text-center align-middle'><font size='3'><div class='abc'>" . _PRODUCT_NAME . "</div></font></th>
									<th class='text-center align-middle'><font size='3'><div class='abc'>" . _CATEGORY . "</div></font></th>
									<th class='text-center align-middle'><font size='3'><div class='abc'>" . _BUY_PRICE . "</div></font></th>
									<th class='text-center align-middle'><font size='3'><div class='abc'>" . _SELL_PRICE . "</div></font></th>
									<th class='text-center align-middle'><font size='3'><div class='abc'>" . _SELL_PROFIT . "</div></font></th>
									<th class='text-center align-middle'><font size='3'><div class='abc'>" . _CUSTOMER_PROVINCE . "</div></font></th>
									<th class='text-center align-middle'><font size='3'><div class='abc'>" . _CONTACT_FROM . "</div></font></th>
									<th class='text-center align-middle'><font size='3'><div class='abc'>" . _DELIVERY_COST . "</div></font></th>
									<th class='text-center align-middle'><font size='3'><div class='abc'>" . _IS_AVALIBLE . "</div></font></th>
								</tr>
							</thead>
							<tbody>\n";

							while($row = mysqli_fetch_array($result))
							{
								$outputsellProfit = $row['sellProfit'];
								//CHECK IF sellProfit IS NEGATIVE
								if ($outputsellProfit < 0) {
									//NEGATIVE
									$outputsellProfit = "<font color=\"red\">" . number_format( $outputsellProfit ) . "</font>";
								} else {
									//POSITIVE
									$outputsellProfit = number_format( $outputsellProfit );
								}
				
								//CHECK IF buyDate IS ZERO DATE
								$outputbuyDate = $row['buyDate'];
								if ($outputbuyDate == "0000-00-00") {
									//ZERO
									$outputbuyDate = "";
								} else {
									$outputbuyDate = date("d-m-Y", strtotime($outputbuyDate));
								}
								
								//CHECK IF sellDate IS ZERO DATE
								$outputsellDate = $row['sellDate'];
								if ($outputsellDate == "0000-00-00") {
									//ZERO
									$outputsellDate = "";
								} else {
									$outputsellDate = date("d-m-Y", strtotime($outputsellDate));
								}

								//CHANGE avalible TO TEXT
								$outputavalible = $row['avalible'];
								if ($outputavalible == 1) {
									$outputavalible = _YES;
								} else if ($outputavalible == 0) {
									$outputavalible = _NO;
								}

								echo "								<tr>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . number_format( $row['id'] ) . "</center></div></font></td>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . $outputbuyDate . "</center></div></font></td>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . $outputsellDate . "</center></div></font></td>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . $row['list'] . "</center></div></font></td>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . $row['category'] . "</center></div></font></td>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . number_format( $row['buyPrice'] ) . "</center></div></font></td>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . number_format( $row['sellPrice'] ) . "</center></div></font></td>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . $outputsellProfit . "</center></div></font></td>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . $row['sellProvince'] . "</center></div></font></td>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . $row['sellSource'] . "</center></div></font></td>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . number_format( $row['deliveryCost'] ) . "</center></div></font></td>\n";
								echo "									<td class='text-center align-middle'><font size='3'><div class='abc'><center>" . $outputavalible . "</center></div></font></td>\n";
								echo "								</tr>\n";
								
								$totalbuyPrice += $row['buyPrice'];
								$totalsellPrice += $row['sellPrice'];
								$totalsellProfit += $row['sellProfit'];
								$totaldeliveryCost += $row['deliveryCost'];
								
								usleep($delayUTime);
							}
							$outputsellProfit = null;
							$outputbuyDate = null;
							$outputsellDate = null;
							$sellCount = mysqli_num_rows($result);

							$outputTotalSellProfit = $totalsellProfit;
							$totalsellProfit = null;

							//CHECK IF totalsellProfit IS NEGATIVE
							if ($outputTotalSellProfit < 0) {
								//NEGATIVE
								$outputTotalSellProfit = "<font color=\"red\">" . number_format( $outputTotalSellProfit ) . "</font>";
							} else {
								//POSITIVE
								$outputTotalSellProfit = number_format( $outputTotalSellProfit );
							}
							
							echo "								<tr class='table-active'>\n";
							echo "									<td class='text-center align-middle'></td>\n";
							echo "									<td class='text-center align-middle'></td>\n";
							echo "									<td class='text-center align-middle'></td>\n";
							echo "									<td class='text-center align-middle'></td>\n";
							echo "									<td class='text-center align-middle'></td>\n";
							echo "									<td class='text-center align-middle'><div class='abc'><center><font size='2'>" . _TOTAL . " "  . number_format( $totalbuyPrice ) . " " . _CURRENCY . "</font></center></div></td>\n";
							echo "									<td class='text-center align-middle'><div class='abc'><center><font size='2'>" . _TOTAL . " " . number_format( $totalsellPrice ) . " " . _CURRENCY . "</font></center></div></td>\n";
							echo "									<td class='text-center align-middle'><div class='abc'><center><font size='2'>" . _TOTAL . " " . $outputTotalSellProfit . " " . _CURRENCY . "</font></center></div></td>\n";
							echo "									<td class='text-center align-middle'></td>\n";
							echo "									<td class='text-center align-middle'></td>\n";
							echo "									<td class='text-center align-middle'><div class='abc'><center><font size='2'>" . _TOTAL . " " . number_format( $totaldeliveryCost ) . " " . _CURRENCY . "</font></center></div></td>\n";
							echo "									<td class='text-center align-middle'><div class='abc'><center><font size='2'>" . _TOTAL . " " . number_format( $sellCount ) . " " . _LIST . "</font></center></div></td>\n";
							echo "								</tr>\n";
							echo "							</tbody>\n";
							echo "						</table>\n";
							echo "					</div>\n";
							$outputTotalSellProfit = null;

							//DELAY
							usleep($delayUTime);

							//CHECK ERROR STATE
							if ($connectionerror == TRUE) {
								//CONNECTION ERROR
								$modalDanger = _ERROR_DB_CONNECT . "<br>" . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8');
							} else if ($revertiveError == TRUE) {
								//REVERTIVE ERROR
								$modalDanger = _ERROR_WHILE_LOAD_DATA . "<br>" . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8');
							}
							
							//CLOSE CONNECTION
							$conn->close();
							
						} else if ($auth AND $authuser != "guest" AND $usemonthreport) {
							//ALL MONTH REPORT, LOAD GRAPH
							if ($loadgraph) {
								echo "<div id='graphloadhere'>
								<table>
								<tr>
								<td>
								<center>
								<div id='piecat'></div>
								</center>
								</td>
								<td>
								<center>
								<div id='piesource'></div>
								</center>
								</td>
								</tr>
								</table>
								</div>";
							}

							echo "<div class='container-lg pr-0 pl-0 pb-2'>
								<font size='3'>
									<div>
										<table class='table table-bordered' id='formtb maintb'>
											<tr class='table-active text-center'>
												<th width='33%'>
													<font size='4'>" . _MONTH . "</font>
												</th>
												<th width='33.5%'>
													<font size='4'>" . _INCOME . "</font>
												</th>
												<th width='33.5%'>
													<font size='4'>" . _DELIVERY_COST . "</font>
												</th>
											</tr>
											<tr>
												<td>
													<font size='3'><center>" . _JANUARY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->reportjan) . " " . _CURRENCY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->deliveryjan) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td>
													<font size='3'><center>" . _FEBUARY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->reportfeb) . " " . _CURRENCY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->deliveryfeb) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td>
													<font size='3'><center>" . _MARCH . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->reportmar) . " " . _CURRENCY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->deliverymar) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . _APRIL . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->reportapr) . " " . _CURRENCY . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->deliveryapr) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . _MAY . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->reportmay) . " " . _CURRENCY . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->deliverymay) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . _JUNE . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->reportjun) . " " . _CURRENCY . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->deliveryjun) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td>
													<font size='3'><center>" . _JULY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->reportjul) . " " . _CURRENCY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->deliveryjul) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td>
													<font size='3'><center>" . _AUGUST . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->reportaug) . " " . _CURRENCY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->deliveryaug) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td>
													<font size='3'><center>" . _SEPTEMBER . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->reportsep) . " " . _CURRENCY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->deliverysep) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . _OCTOBER . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->reportoct) . " " . _CURRENCY . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->deliveryoct) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . _NOVEMBER . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->reportnov) . " " . _CURRENCY . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->deliverynov) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . _DECEMBER . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->reportdec) . " " . _CURRENCY . "</center></font>
												</td>
												<td class='paddinglesswithtop'>
													<font size='3'><center>" . number_format($decodejson->deliverydec) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td>
													<font size='3'><center>" . _TOTAL_FIRST_QUARTER . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->reportqu1) . " " . _CURRENCY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->deliveryqu1) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td>
													<font size='3'><center>" . _TOTAL_SECOND_QUARTER . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->reportqu2) . " " . _CURRENCY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->deliveryqu2) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td>
													<font size='3'><center>" . _TOTAL_THIRD_QUARTER . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->reportqu3) . " " . _CURRENCY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->deliveryqu3) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr>
												<td>
													<font size='3'><center>" . _TOTAL_FORTH_QUARTER . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->reportqu4) . " " . _CURRENCY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->deliveryqu4) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
											<tr class='table-active'>
												<td>
													<font size='3'><center>" . _TOTAL . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->reportall) . " " . _CURRENCY . "</center></font>
												</td>
												<td>
													<font size='3'><center>" . number_format($decodejson->deliveryall) . " " . _CURRENCY . "</center></font>
												</td>
											</tr>
										</table>
										<div>
											<table border='0'>
												<tr>
													<td>
														<center>
															<font size='3'>
																" . _AVERAGE_SALARY_FIRST_QUARTER . " : 
															</font>
														</center>
													</td>
													<td>
														<div class='p-3'></div>
													</td>
													<td>
														<center>
															" . number_format( $decodejson->reportavgqu1, 2 ) . " " . _CURRENCY . "
														</center>
													</td>
												</tr>
												<tr>
													<td>
														<center>
															<font size='3'>
																" . _AVERAGE_SALARY_SECOND_QUARTER . " : 
															</font>
														</center>
													</td>
													<td>
														<div class='p-3'></div>
													</td>
													<td>
														<center>
															" . number_format( $decodejson->reportavgqu2, 2 ) . " " . _CURRENCY . "
														</center>
													</td>
												</tr>
												<tr>
													<td>
														<center>
															<font size='3'>
																" . _AVERAGE_SALARY_THIRD_QUARTER . " : 
															</font>
														</center>
													</td>
													<td>
														<div class='p-3'></div>
													</td>
													<td>
														<center>
															" . number_format( $decodejson->reportavgqu3, 2 ) . " " . _CURRENCY . "
														</center>
													</td>
												</tr>
												<tr>
													<td>
														<center>
															<font size='3'>
																" . _AVERAGE_SALARY_FORTH_QUARTER . " : 
															</font>
														</center>
													</td>
													<td>
														<div class='p-3'></div>
													</td>
													<td>
														<center>
															" . number_format( $decodejson->reportavgqu4, 2 ) . " " . _CURRENCY . "
														</center>
													</td>
												</tr>
												<tr>
													<td>
														<center>
															<font size='3'>
																" . _AVERAGE_SALARY_ALL_YEAR . " : 
															</font>
														</center>
													</td>
													<td>
														<div class='p-3'></div>
													</td>
													<td>
														<center>
															" . number_format( $decodejson->reportavgall, 2 ) . " " . _CURRENCY . "
														</center>
													</td>
												</tr>
											</table>
										</div>";
										if ($auth AND $authuser != "guest" AND $usemonthreport AND $loadgraphmonth) {
											echo "											<hr width=\"97%\">
											<div style=\"padding-top: 12px;\" class=\"paddinglesswithtop\">
												<div id=\"allmonthgraph\"  class=\"fadein\">
	
												</div>\n";
										}
										echo "										</div>
									</div>
								</font>
							</div>";

							//LOAD GRAPH ALL MONTH
							if ($auth AND $authuser != "guest" AND $usemonthreport AND $loadgraphmonth) {
								echo "
								<script type='text/javascript'>
									google.charts.load('current', {'packages':['corechart']});
									google.charts.setOnLoadCallback(drawChart);

									function drawChart() {
										var data = google.visualization.arrayToDataTable([
										['" . _MONTH . "', '" . _INCOME . "', '" . _DELIVERY_COST . "'],
										['1',  $decodejson->reportjan, $decodejson->deliveryjan],
										['2',  $decodejson->reportfeb, $decodejson->deliveryfeb],
										['3',  $decodejson->reportmar, $decodejson->deliverymar],
										['4',  $decodejson->reportapr, $decodejson->deliveryapr],
										['5',  $decodejson->reportmay, $decodejson->deliverymay],
										['6',  $decodejson->reportjun, $decodejson->deliveryjun],
										['7',  $decodejson->reportjul, $decodejson->deliveryjul],
										['8',  $decodejson->reportaug, $decodejson->deliveryaug],
										['9',  $decodejson->reportsep, $decodejson->deliverysep],
										['10', $decodejson->reportoct, $decodejson->deliveryoct],
										['11', $decodejson->reportnov, $decodejson->deliverynov],
										['12', $decodejson->reportdec, $decodejson->deliverydec]
										]);

										var options = {
										title: '" . _ALL_MONTH_REPORT_OF_YEAR . " $decodejson->reportyear',
										//curveType: 'function',
										legend: { position: 'bottom' }
										};

										var chart = new google.visualization.LineChart(document.getElementById('allmonthgraph'));

										chart.draw(data, options);
									}
								</script>\n";
							} else {

							}
						} else {
							//NOT AUTH OR BAD USERNAME, CHECK IF ADMINPASSWORD IS BLANK, CHECH IF USE ALL MONTH REPORT
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
									$page = "printview.php";
									
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
						//READ VERSION
						$nowversion = _LASTEST;
	
						if ($auth and $authuser != "guest") {
							$amyfile = fopen("./module/nowversion.txt", "r");
							$nowversion = fread($amyfile,filesize("./module/nowversion.txt"));
							fclose($amyfile);
						}
					?>
					<div class="padding">
						<font size="2"><?= _DATA_SYSTEMS . " " . _VERSION . " " . $nowversion?></font>
					</div>
				</div>
			</center>
		</div>
		<?php
			if ($auth and $authuser != "guest") {
				echo "<div style='position: fixed; right: 25px; bottom: 55px; display: none;' id='noprint'>
			<center>
				<div class='pb-2'><button class='printbutton' onclick='doPrint(); hideSettings()'>" . _PRINT_THIS . "</button></div>
				<div class='pb-2'><button class='btn btn-success' onclick='showSettings()'>" . _SETTING . "</button></div>
				<div><button class='btn btn-danger' onclick='javascript:close_window();'>" . _CLOSE_PAGE . "</button></div>
			</center>
		</div>\n";
			}
		?>
		<div class="modal fade" id="modalprintSettingDesktop">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<font size="5" class="modal-title"><b><?= _NOTIFY ?></b></font>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<font size="3"><?= _PRINT_INFO_DESKTOP ?></font>
					</div>
					<div class="modal-footer">
						<button type="button" onclick="doPrintDesktop()" class="btn btn-sm btn-success" data-dismiss="modal"><?= _CONTINUE ?></button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="modalprintSettingMobile">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<font size="5" class="modal-title"><b><?= _NOTIFY ?></b></font>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<font size="3"><?= _PRINT_INFO_MOBILE ?></font>
					</div>
					<div class="modal-footer">
						<button type="button" onclick="doPrintMobile()" class="btn btn-sm btn-success" data-dismiss="modal"><?= _CONTINUE ?></button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="showSettings">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<font size="5" class="modal-title"><b><?= _SETTING ?></b></font>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<form id="formSettings" method="POST" style="margin-bottom: 0px;">
							<center>
								<?php
									if ($usermode == "custommonth") {
										echo "<input type='hidden' name='month' value='$month' />
										<input type='hidden' name='monthyear' value='$monthyear' />
										<input type='hidden' name='monthmode' value='$monthmode' />\n";
									} else {
										echo "<input type='hidden' name='$usermode' value='" . htmlspecialchars($userstring) . "' />";
									}
									echo "\n										<input type='hidden' name='token' value='" . $_POST["token"] . "' />\n";
								?>
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="loadgraph" id="loadgraph" value="1"> <label class="form-check-label" for="loadgraph"><?= _LOAD_GRAPH ?></label>
								</div>
								<?php if ($auth AND $authuser != "guest" AND $usemonthreport) {
									echo '<div class="form-check pt-2"><input type="checkbox" class="form-check-input" name="loadgraphmonth" id="loadgraphmonth" value="1"> <label class="form-check-label" for="loadgraphmonth">' . _LOAD_GRAPH_ALL_MONTH_REPORT . '</label></div>';
								}
								?>
							</center>
						</form>
					</div>
					<div class="modal-footer">
						<button class="btn btn-sm btn-danger" type="button" onclick="hideSettings()"><?= _CANCEL ?></button>
						<button type="button" onclick="hideonlySettings()" class="btn btn-sm btn-success" data-dismiss="modal"><?= _SET ?></button>
					</div>
				</div>
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
		<div class="modal fade" id="modalDanger" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<font size="5" class="modal-title"><b><?= _NOTIFY ?></b></font>
					</div>
					<div class="modal-body">
						<font size="3"><span id="modalDangerContent"><?= $modalDanger ?></span></font>
					</div>
					<div class="modal-footer">
						<button type="button" onclick="javascript: close_window();" class="btn btn-sm btn-danger" data-dismiss="modal"><?= _CLOSE_PAGE ?></button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<?php 
	//CONTROL LOGIN FORM
	if ($auth AND $authuser != "guest") {
		//HIDE
		if ($modalWarn != "") {
			echo "\n<script>hidelogin(); $('#modalWarning').modal();</script>";
		} else {
			echo "\n<script>hidelogin(); $('#modalWarning').modal();</script>";
		}

		if ($modalDanger != "") {
			echo "\n<script>hidelogin(); $('#modalDanger').modal();</script>";
		} else {
			echo "\n<script>hidelogin();</script>";
		}
	} else {
		//SHOW
		if ($modalWarn != "") {
			echo "\n<script>showlogin(); hideloading(); $('#modalWarning').modal();</script>";
		} else {
			echo "\n<script>showlogin(); hideloading();</script>";
		}

		if ($modalDanger != "") {
			echo "\n<script>showlogin(); hideloading(); $('#modalDanger').modal();</script>";
		} else {
			echo "\n<script>showlogin(); hideloading();</script>";
		}
	}
	
	//CONTROL LOADING GRAPH
	if ($auth AND $authuser != "guest" AND $loadgraph == FALSE) {
		echo "\n<script>document.getElementById('graphload').style.display = 'none';
		document.getElementById('loadgraph').checked = false;</script>";
	} else {
		//LOAD GRAPH
		echo "\n<script>document.getElementById('graphloadhere').disabled = true;</script>";

		//DELAY
		usleep($delayUTime);

		echo "
		<script>
			//LOAD GRAPH SOURCE
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
				// Optional; add a title and set the width and height of the chart
				var options = {'title':'Sold Source', 'width':415, 'height':275};

				var chart = new google.visualization.PieChart(document.getElementById('piesource'));
				chart.draw(data, options);
			}

			//LOAD GRAPH CATEGORY
			//LOAD GOOGLE CHARTS
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChartCategory);

			//DRAW THE CHART AND SET THE CHART VALUES
			function drawChartCategory() {
				var bdata = google.visualization.arrayToDataTable([
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
				// Optional; add a title and set the width and height of the chart
				var boptions = {'title':'Sold Category', 'width':415, 'height':275};

				var bchart = new google.visualization.PieChart(document.getElementById('piecat'));
				bchart.draw(bdata, boptions);
			
				//LOAD GRAPH SUCESSFULLY
				var loadingElement = document.getElementById('loading');
				loadingElement.parentNode.appendChild(loadingElement);
				document.getElementById('graphload').style.display = 'none';
			}
		</script>
		";
	}
	
	echo "\n<script>ahideloading();</script>";
	echo "\n<noscript><img id='blockanother' width='100%' height='100%'><table border='1' id='tbjavascript' class='tbinfo'><tr><td class='curve'><div style='padding:28px 28px 28px 28px;'><font size='4'><strong><center>" . _ERROR_ENABLE_JS . "<br></center><img src='/blank.png' height='32' /><br></strong><center><a class='myButton' target='_blank' href='" . _ENABLE_JS_SITE . "'>" . _CONTINUE . "</a></center></font></div></td></tr></table></noscript>";?>