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
	$custommonth = FALSE;
	
	$findList = $_POST["findList"];
	$userfL = $_POST["findList"];
	$dofindList = FALSE;
	
	$customcom = $_POST["customcom"];
	$customcom = str_replace("(u201D", "\"", $customcom);
	$customcomm = FALSE;
	
	$usermode = "ordermode";
	$userstringmode = "ordermode=highid";
	$userstring = "highid";

	$twocommand = FALSE;
	
	//TWO COMMANDS CHECK IN CUSTOMCOM
	if (strpos($_POST["customcom"], ";") !== FALSE or strpos($_POST["customcom"], "U+0003B") !== FALSE or strpos($_POST["customcom"], "&#x3b;") !== FALSE or strpos($_POST["customcom"], "&#59;") !== FALSE or strpos($_POST["customcom"], "&semi;") !== FALSE or strpos($_POST["customcom"], "\003B") !== FALSE or strpos($_POST["customcom"], "LEFT JOIN") !== FALSE or strpos($_POST["customcom"], "RIGHT JOIN") !== FALSE or strpos($_POST["customcom"], "UPDATE") !== FALSE or strpos($_POST["customcom"], "DELETE") !== FALSE or strpos($_POST["customcom"], "TRUNCATE") !== FALSE or strpos($_POST["customcom"], "CHANGE") !== FALSE or strpos($_POST["customcom"], "DROP") !== FALSE or strpos($_POST["customcom"], "EMPTY") !== FALSE or strpos($_POST["customcom"], "CREATE") !== FALSE or strpos($_POST["customcom"], "UNION") !== FALSE or strpos($_POST["customcom"], "ALTER") !== FALSE or strlen($_POST["customcom"]) >= 400) {
		$twocommand = TRUE;
	}
	
	//CHECK IF CUSTOM MONTH IS NULL
	if ($auth AND $authuser != "guest") {
		if ($getmonth == NULL or $getmonthyear == NULL) {
			$month = date("m");
			$monthyear = date("Y");
		} else {
			$custommonth = TRUE;
			$month = $getmonth;
			$monthyear = $getmonthyear;

			settype($month, "integer");
			settype($monthyear, "integer");

			//ADD 0 TO MONTH
			if (strlen($month) == 1) {
				$month = "0$month";
			}

			//ADD 0 TO YEAR (SPECIFIC DAY)
			if (strlen($monthyear) == 1) {
				$monthyear = "0$monthyear";
			}
			
			$usermode = "custommonth";
			$userstringmode = "month=$month&monthyear=$monthyear";
		}
		
		//CHECK IF FIND LIST IS NULL
		if ($findList == NULL) {
		
		} else {
			//REPLACE NOT ALLOW STRING
			$dofindList = TRUE;
			
			$findList = str_replace("+", "", $findList);
			$findList = str_replace("/", "", $findList);
			$findList = str_replace("\\", "", $findList);
			$findList = str_replace("*", "", $findList);
			$findList = str_replace("\"", "'", $findList);
			$findList = str_replace("`", "", $findList);
			
			$findList = str_replace(";", "", $findList);
			$userfL = $findList;
			
			$findList = strtolower($findList);
			
			$usermode = "findList";
			$userstringmode = "findList=$findList";
			$userstring = $findList;
		}
		
		//CHECK IF CUSTOMCOM IS NULL
		if ($customcom == NULL) {
		
		} else {
			$customcomm = TRUE;
			
			$customcommode = str_replace("\"", "(ฟ", $customcom);
			
			$usermode = "customcom";
			$userstringmode = "customcom=$customcommode";
			$userstring = $customcommode;
		}
		
		//DELAY
		usleep($delayUTime);
	}
	
?>

<html>
	<head>
		<title><?= _VIEW_ALL_DATA ?></title>
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
				echo '<meta name="viewport" content="width=device-width, initial-scale=0.325">';
			} else {
				echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
			}
		?>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<script src='js/pzDS_add.js'></script>
		<script src='js/pzDS_view.js'></script>
		<script src='js/main.js'></script>
		<script>
			function ahideloading() {
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

			var firstClickCP = 0;
			function toggleControlPanel() {
				var controlPanel = document.getElementById("controlPanel");
				var toggleButton = document.getElementById("toggleButton");
				
				//CHECK IF FIRST CLICK
				if (firstClickCP == 0) {
					firstClickCP = 1;
					controlPanel.style.display = "none";
					toggleButton.innerHTML = "<font color=\"white\"><strong><?= _SHOW_CONTROL_PANEL ?> &#x25BC</strong></font>";
					toggleButton.style.borderRadius = "12px 12px 12px 12px";
				} else {
					//CHECK IF VISIBLE OR NOT
					if (controlPanel.style.display === "block") {
						controlPanel.style.display = "none";
						toggleButton.innerHTML = "<font color=\"white\"><strong><?= _SHOW_CONTROL_PANEL ?> &#x25BC</strong></font>";
						toggleButton.style.borderRadius = "12px 12px 12px 12px";
					} else {
						controlPanel.style.display = "block";
						toggleButton.innerHTML = "<font color=\"white\"><strong><?= _HIDE_CONTROL_PANEL ?> &#x25B2</strong></font>";
						toggleButton.style.borderRadius = "12px 12px 0px 0px";
					}
				}
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
                        <center><font size="5"><b><?= _LOGIN_VIEW_ALL_DATA ?></b></font></center>
                    </div>
					<div style="padding: 8px;"></div>
					<div class="text-center">
						<font size="2">
							<?= _LOGIN_VIEW_ALL_DATA . ", " . _ENTER_PASSWORD_CORRECTLY ?>
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
							<input type="hidden" name="ordermode" value="<?= _ORDER_MODE_WHEN_LOGGED_IN ?>" />
                        </form>
                    </div>
                </div>
            </div>
			<center>
				<div class="container-md pr-1 pl-1" id="maintb" style="display:none; margin-top:54px;">
					<div>
						<font size="5">
							<b><?= _VIEW_ALL_DATA ?></b>
						</font>
					</div>
					<div class="container-fluid pt-2">
						<div class="container-fluid">
							<button style="margin-bottom: 0px; width: 100%; border: 0px; padding: 16px; background-color: #ff8000; border-radius: 12px 12px 0px 0px;" onclick="toggleControlPanel()" id="toggleButton"><font color="white"><strong><?=_HIDE_CONTROL_PANEL?> &#x25B2</strong></font></button>
							<div id="controlPanel" style="background-color: whitesmoke; border-radius: 0px 0px 12px 12px;">
								<div class="container-md pt-3 pl-4 pr-4">
									<form method="POST">
										<center>
											<div class="input-group container-sm">
												<font size="3" class="mt-1"><?= _SELECT_ORDER_MODE ?>:&nbsp;</font>
												<select id="ordermode" class="form-control form-control-sm" value="เรียงจากครั้งที่เพิ่มครั้งแรก -> ครั้งล่าสุด (ค่าเริ่มต้น)" name="ordermode" onsubmit="showloading()">
													<option value="olddate">เรียงจากวันที่ขายเก่าที่สุด -> ล่าสุด</option>
													<option value="newdate">เรียงจากวันที่ขายล่าสุด -> เก่าที่สุด</option>
													<option value="oldbuydate">เรียงจากวันที่ซื้อเก่าที่สุด -> ล่าสุด</option>
													<option value="newbuydate">เรียงจากวันที่ซื้อล่าสุด -> เก่าที่สุด</option>
													<option value="lowsellprice">เรียงจากราคาขายน้อยที่สุด -> มากที่สุด</option>
													<option value="highsellprice">เรียงจากราคาขายมากที่สุด -> น้อยที่สุด</option>
													<option value="lowsellprofit">เรียงจากกำไรน้อยที่สุด -> มากที่สุด</option>
													<option value="highsellprofit">เรียงจากกำไรมากที่สุด -> น้อยที่สุด</option>
													<option value="lowdeliverycost">เรียงจากค่าขนส่งน้อยที่สุด -> มากที่สุด</option>
													<option value="highdeliverycost">เรียงจากค่าขนส่งมากที่สุด -> น้อยที่สุด</option>
													<option value="lowid">เรียงจากครั้งที่เพิ่มครั้งแรก -> ครั้งล่าสุด</option>
													<option value="highid">เรียงจากครั้งที่เพิ่มครั้งล่าสุด -> ครั้งแรก (ค่าเริ่มต้น)</option>
												</select>
												<div class="input-group-append">
													<button type="submit" class="btn btn-sm btn-success" onclick="showloading(); hidePrintbtn()"><?= _SET ?></button>
												</div>
											</div>
											<input type="hidden" name="token" value="<?= $totoken; ?>" />
										</center>
									</form>
								</div>
								<div class="container-md pl-4 pr-4">
									<form method="POST">
										<center>
											<div class="input-group container-sm">
												<font size="3" class="mt-1"><?= _SELECT_LIST ?>:&nbsp;</font>
												<select id="ordermode2" class="form-control form-control-sm" value="แสดงเฉพาะรายการที่ยังมีอยู่ในคลัง" name="ordermode">
													<option value="avaliblelist">แสดงเฉพาะรายการที่ยังมีอยู่ในคลัง</option>
													<option value="selllist">แสดงเฉพาะรายการที่ไม่มีอยู่ในคลัง</option>
												</select>
												<div class="input-group-append">
													<button type="submit" class="btn btn-sm btn-success" onclick="showloading(); hidePrintbtn()"><?= _SET ?></button>
												</div>
											</div>
											<input type="hidden" name="token" value="<?= $totoken; ?>" />
										</center>
									</form>
								</div>
								<div class="container-md pl-4 pr-4">
									<form method="POST" onsubmit="addReadonlys('monthbuy', 'monthyearbuy')">
										<center>
											<div class="input-group container-sm">
												<font size="3" class="mt-1"><?= _SET_VIEW_BUY_DAY_MONTH ?>:&nbsp;</font>
												<input type="number" class="form-control form-control-sm" placeholder="<?= _DAY . " " . _OR . " " .  _MONTH ?>" name="month" id="monthbuy" min="1" max="31" value="<?= $month; ?>">
												<input type="number" class="form-control form-control-sm" placeholder="<?= _MONTH . " " . _OR . " " .  _YEAR ?>" name="monthyear" id="monthyearbuy" min="1" value="<?= $monthyear; ?>">
												<div class="input-group-append">
													<button type="submit" class="btn btn-sm btn-success" onclick="showloading(); hidePrintbtn()"><?= _SET ?></button>
												</div>
											</div>
											<input type="hidden" name="monthmode" value="buy">
											<input type="hidden" name="token" value="<?= $totoken; ?>" />
										</center>
									</form>
								</div>
								<div class="container-md pl-4 pr-4">
									<form method="POST" onsubmit="addReadonlys('monthsell', 'monthyearsell')">
										<center>
											<div class="input-group container-sm">
												<font size="3" class="mt-1"><?= _SET_VIEW_SELL_DAY_MONTH ?>:&nbsp;</font>
												<input type="number" class="form-control form-control-sm" placeholder="<?= _DAY . " " . _OR . " " .  _MONTH ?>" name="month" id="monthsell" min="1" max="31" value="<?= $month; ?>">
												<input type="number" class="form-control form-control-sm" placeholder="<?= _MONTH . " " . _OR . " " .  _YEAR ?>" name="monthyear" id="monthyearsell" min="1" value="<?= $monthyear; ?>">
												<div class="input-group-append">
													<button type="submit" class="btn btn-sm btn-success" onclick="showloading(); hidePrintbtn()"><?= _SET ?></button>
												</div>
											</div>
											<input type="hidden" name="monthmode" value="sell">
											<input type="hidden" name="token" value="<?= $totoken; ?>" />
										</center>
									</form>
								</div>
								<div class="container-md pl-4 pr-4">
									<form method="POST" onsubmit="addReadonly('findList');">
										<center>
											<div class="input-group container-sm">
												<font size="3" class="mt-1"><?= _SEARCH_PRODUCT_NAME ?>:&nbsp;</font>
												<input type="text" class="form-control form-control-sm" id="findList" name="findList" value="<?= $userfL; ?>">
												<div class="input-group-append">
													<button type="submit" class="btn btn-sm btn-success" onclick="showloading(); hidePrintbtn()"><?= _SET ?></button>
												</div>
											</div>
											<input type="hidden" name="token" value="<?= $totoken; ?>" />
										</center>
									</form>
								</div>
								<div class="container-md pl-4 pr-4">
									<form method="POST" onsubmit="addReadonly('customcoms');">
										<center>
											<div class="input-group container-sm">
												<font size="3" class="mt-1"><?= _CUSTOM_COMMAND ?>: SELECT * FORM <?= _ALL_DATA ?>&nbsp;</font>
												<input type="text" class="form-control form-control-sm" id="customcoms" name="customcom" value="<?= htmlspecialchars($customcom); ?>">
												<div class="input-group-append">
													<button type="submit" class="btn btn-sm btn-success" onclick="showloading(); hidePrintbtn()"><?= _SET ?></button>
												</div>
											</div>
											<input type="hidden" name="token" value="<?= $totoken; ?>" />
										</center>
									</form>
								</div>
								<div style="padding-bottom: 12px;">
									<center>
										<table border="0" style="background-color: whitesmoke;">
											<tr>
												<td>
													<form action='reassignid.php' method='POST' target='_blank' style='margin-block-end: 0px;'>
														<input type='hidden' name='token' value='<?= $totoken; ?>' />
														<button class='btn btn-sm btn-primary' type='submit'><?= _REASSIGN_ID ?></button>
													</form></td>
												<td>
													<div style="padding: 0px 4px 0px 4px;"></div>
												</td>
												<td>
													<form action="view.php" method="POST" style="margin-bottom: 0px;">
														<input type="hidden" name="token" value="<?= $totoken; ?>" />
														<button type="submit" class="btn btn-sm btn-warning" onclick="showloading(); hidePrintbtn()"><?= _REMOVE_SETTING ?></button>
													</form>
												</td>
											</tr>
										</table>
									</center>
								</div>
							</div>
						</div>
						<div style="padding: 12px 12px 0px 12px;" class="container-fluid">
							<font size="3"><?= _CURRENT_ORDER_MODE ?> : <?php if ($_POST["customcom"] == NULL or $twocommand == TRUE) { if ($_POST["ordermode"] == NULL or $_POST["ordermode"] == "highid" or $_POST["ordermode"] == "0") { echo "เรียงจากครั้งที่เพิ่มครั้งล่าสุด -> ครั้งแรก (ค่าเริ่มต้น)"; } else if ($_POST["ordermode"] == "lowid") { echo "เรียงจากครั้งที่เพิ่มครั้งแรก -> ครั้งล่าสุด"; } else if ($_POST["ordermode"] == "olddate") { echo "เรียงจากวันที่ขายเก่าที่สุด -> ล่าสุด"; } else if ($_POST["ordermode"] == "newdate") { echo "เรียงจากวันที่ขายล่าสุด -> เก่าที่สุด"; } else if ($_POST["ordermode"] == "highsellprice") { echo "เรียงจากราคาขายมากที่สุด -> น้อยที่สุด"; } else if ($_POST["ordermode"] == "lowsellprice") { echo "เรียงจากราคาขายน้อยที่สุด -> มากที่สุด"; } else if ($_POST["ordermode"] == "lowsellprofit") { echo "เรียงจากกำไรน้อยที่สุด -> มากที่สุด"; } else if ($_POST["ordermode"] == "highsellprofit") { echo "เรียงจากกำไรมากที่สุด -> น้อยที่สุด"; } else if ($_POST["ordermode"] == "oldbuydate") { echo "เรียงจากวันที่ซื้อเก่าที่สุด -> ล่าสุด"; } else if ($_POST["ordermode"] == "lowdeliverycost") { echo "เรียงจากค่าขนส่งน้อยที่สุด -> มากที่สุด"; } else if ($_POST["ordermode"] == "highdeliverycost") { echo "เรียงจากค่าขนส่งมากที่สุด -> น้อยที่สุด"; } else if ($_POST["ordermode"] == "newbuydate") { echo "เรียงจากวันที่ซื้อล่าสุด -> เก่าที่สุด"; } else if ($_POST["ordermode"] == "avaliblelist") { echo "แสดงเฉพาะรายการที่ยังมีอยู่ในคลัง"; } else if ($_POST["ordermode"] == "selllist") { echo "แสดงเฉพาะรายการที่ไม่มีอยู่ในคลัง"; } else { echo $_POST["ordermode"]; } } else { if ($twocommand) { echo "เรียงจากครั้งที่เพิ่มครั้งล่าสุด -> ครั้งแรก (ค่าเริ่มต้น)"; } else { echo "ตั้งค่าคำสั่งเอง"; } } ?></span></font>
						</div>
						<div id="pageselecttop" style="margin-top: 12px; margin-right: 15px; float: left;">
							
						</div>
						<div style="padding: 0px 12px 0px 12px;" class="container-fluid">
							<?php
								$connectionerror = FALSE;
								$revertiveError = FALSE;
								$uselimit = 0;
								
								$ordermode = $_POST["ordermode"];

								$monthdata = "$monthyear-$month";
								$monthmode = $_POST["monthmode"];
								
								$showerrortb = FALSE;
								
								$nodata = FALSE;
								$totalpage = 1;
								$selectpage = $_POST["page"];
								//CHECK IF SELECTPAGE IS NULL
								if ($selectpage == NULL) {
									$selectpage = 1;
								}
								
								//REVERTIVE DATA
								if ($auth and $authuser != "guest") {
									//AUTH, SET CONNECTION DETAIL
									//START SECTION: SQL LOGIN
									include "./module/sqllogin.php";
									//END SECTION: SQL LOGIN

									$conn = new mysqli($servername, $username, $password, $dbname);
									
									//GET PAGE
									$perpage = _VIEW_DATA_LIMIT;
									$startpage = (($perpage * $selectpage) - $perpage);
									
									if ($conn->connect_error) {
										$connectionerror = TRUE;
									}
									
									$sqlcom = "SELECT * FROM $tb_name";
									
									//CHECK IF USER USE CUSTOMCOM
									if ($customcomm == TRUE) {
										//CUSTOM
										$sqlcom = "SELECT * FROM $tb_name $customcom";
										$sqlcomcheck = strtoupper($sqlcom);
								
										if (strpos($sqlcomcheck, ";") !== FALSE or strpos($sqlcomcheck, "U+0003B") !== FALSE or strpos($sqlcomcheck, "&#x3b;") !== FALSE or strpos($sqlcomcheck, "&#59;") !== FALSE or strpos($sqlcomcheck, "&SEMI;") !== FALSE or strpos($sqlcomcheck, "\003B") !== FALSE or strpos($sqlcomcheck, "LEFT JOIN") !== FALSE or strpos($sqlcomcheck, "RIGHT JOIN") !== FALSE or strpos($sqlcomcheck, "UPDATE") !== FALSE or strpos($sqlcomcheck, "DELETE") !== FALSE or strpos($sqlcomcheck, "TRUNCATE") !== FALSE or strpos($sqlcomcheck, "CHANGE") or strpos($sqlcomcheck, "DROP") !== FALSE or strpos($sqlcomcheck, "EMPTY") !== FALSE or strpos($sqlcomcheck, "AAAAA") !== FALSE or strpos($sqlcomcheck, "UNION") !== FALSE or strpos($sqlcomcheck, "ALTER") !== FALSE  or strpos($sqlcomcheck, "CREATE") !== FALSE or strlen($sqlcomcheck) >= 400) {
											//TWO COMMAND FOUND
											$sqlcom = "SELECT * FROM $tb_name ORDER BY id DESC";
											$usermode = "ordermode";
											$userstringmode = "ordermode=highid";
											$userstring = "highid";
											
											$modalWarn = _ERROR_ILLEGAL_COMMAND;
										}
									} else {
										//CHECK IF FIND LIST
										if (! $dofindList) {
											//CHECK IF ONLY MONTH DATA
											if (! $custommonth) {
												if ($ordermode == "") {
													//DEFAULT MODE
													$userstringmode = "ordermode=highid";
													$sqlcom = "SELECT * FROM $tb_name ORDER BY id DESC";
													$userstring = "highid";
												} else if ($ordermode == "newdate") {
													//LASTED DATE TO OLDEST DATE
													$sqlcom = "SELECT * FROM $tb_name ORDER BY sellDate DESC";
													$userstringmode = "ordermode=newdate";
													$userstring = "newdate";
												} else if ($ordermode == "olddate") {
													//OLDEST DATE TO LASTED DATE
													$sqlcom = "SELECT * FROM $tb_name ORDER BY sellDate ASC";
													$userstringmode = "ordermode=olddate";
													$userstring = "olddate";
												} else if ($ordermode == "lowsellprice") {
													//LOWEST SELLPRICE TO HIGHEST SELLPRICE
													$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY sellPrice ASC";
													$userstringmode = "ordermode=lowsellprice";
													$userstring = "lowsellprice";
												} else if ($ordermode == "highsellprice") {
													//HIGHEST SELLPRICE TO LOWEST SELLPRICE
													$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY sellPrice DESC";
													$userstringmode = "ordermode=highsellprice";
													$userstring = "highsellprice";
												} else if ($ordermode == "lowsellprofit") {
													//LOWEST SELLPROFIT TO HIGHEST SELLPROFIT
													$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY sellProfit ASC";
													$userstringmode = "ordermode=lowsellprofit";
													$userstring = "lowsellprofit";
												} else if ($ordermode == "highsellprofit") {
													//HIGHEST SELLPROFIT TO LOWEST SELLPROFIT
													$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY sellProfit DESC";
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
													$sqlcom = "SELECT * FROM $tb_name ORDER BY id ASC";
													$userstringmode = "ordermode=lowid";
													$userstring = "lowid";
												} else if ($ordermode == "highid") {
													//HIGHEST ID TO LOWEST ID
													$sqlcom = "SELECT * FROM $tb_name ORDER BY id DESC";
													$userstringmode = "ordermode=highid";
													$userstring = "highid";
												} else if ($ordermode == "newbuydate") {
													//LASTED DATE TO OLDEST DATE
													$sqlcom = "SELECT * FROM $tb_name ORDER BY buyDate DESC";
													$userstringmode = "ordermode=newbuydate";
													$userstring = "newbuydate";
												} else if ($ordermode == "oldbuydate") {
													//OLDEST DATE TO LASTED DATE
													$sqlcom = "SELECT * FROM $tb_name ORDER BY buyDate ASC";
													$userstringmode = "ordermode=oldbuydate";
													$userstring = "oldbuydate";
												} else if ($ordermode == "avaliblelist") {
													//SHOW ONLY AVALIBLE LIST
													$sqlcom = "SELECT * FROM $tb_name WHERE avalible=1 ORDER BY id DESC";
													$userstringmode = "ordermode=avaliblelist";
													$userstring = "avaliblelist";
												} else if ($ordermode == "selllist") {
													//SHOW ONLY NOT AVALIBLE LIST
													$sqlcom = "SELECT * FROM $tb_name WHERE avalible=0 ORDER BY id DESC";
													$userstringmode = "ordermode=selllist";
													$userstring = "selllist";
												} else {
													//DEFAULT MODE
													$userstringmode = "ordermode=highid";
													$sqlcom = "SELECT * FROM $tb_name ORDER BY id DESC";
													$userstring = "highid";
												}
											} else {
												//CHECK IF NEED buyDate OR sellDate
												if ($monthmode == "sell") {
													//SELL VIEW
													$sqlcom = "SELECT * FROM $tb_name WHERE sellDate LIKE '%{$monthdata}%' ORDER BY id DESC";
												} else {
													//BUY VIEW
													$sqlcom = "SELECT * FROM $tb_name WHERE buyDate LIKE '%{$monthdata}%' ORDER BY id DESC";
												}
											}
										} else {
											//FIND LIST
											$sqlcom = "SELECT * FROM $tb_name WHERE lower(list) LIKE \"%$findList%\" ORDER BY id DESC";
										}
									}
									
									//DELAY
									usleep($delayUTime);
									
									//CHECK IF FIRST LOGIN
									if ($_POST["ordermode"] != "0") {
										//NO, CHECK IF USER USE LIMIT COMMAND
										$sqlcomcheck = strtoupper($sqlcom);
										if (strpos($sqlcomcheck, "LIMIT") !== FALSE) {
											//USE, BLOCK PAGE NAVIGATOR TO CREATE ON TOP OF TABLE
											$uselimit = 1;
										} else {
											//NOT USE, ADD LIMIT COMMAND
											$originalsqlcom = $sqlcom;
											
											//ADD PER PAGE TO QUERY COMMAND
											$sqlcom = "$sqlcom LIMIT $startpage , $perpage";

											$originalsqlcom = str_replace("SELECT * FROM", "SELECT id FROM", $originalsqlcom);
											$originalresult = mysqli_query($conn, $originalsqlcom);
											$originalnumrows = mysqli_num_rows($originalresult);
											$totalpage = ceil($originalnumrows / $perpage);
										}
										
										$result = mysqli_query($conn, $sqlcom);
		
										if(! $result) {
											$revertiveError = TRUE;
										}
										
										//DELAY
										usleep($delayUTime);
										
										$totalbuyPrice = 0;
										$totalsellPrice = 0;
										$totalsellProfit = 0;
		
										echo "<div class='table-responsive'>
										<table class='table table-bordered w-auto' id='tabledata'>
										<thead>
											<tr class='table-active'>
												<th class='text-center align-middle'><div class='abc'>ID</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _BUY_DATE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _SELL_DATE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _PRODUCT_NAME . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _CATEGORY . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _BUY_PRICE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _SELL_PRICE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _SELL_PROFIT . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _CUSTOMER_PROVINCE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _CONTACT_FROM . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _DELIVERY_COST . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _IS_AVALIBLE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _EDIT_DATA . "</div></th>
												<th class='text-center align-middle'><div class='abc'>". _DELETE_DATA . "</div></th>
											</tr>
										</thead>
										<tbody>\n";

										while ($row = mysqli_fetch_array($result))
										{
											//CHECK IF sellProfit IS NEGATIVE
											$outputsellProfit = $row['sellProfit'];
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

											echo "										<tr>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . number_format( $row['id'] ) . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . $outputbuyDate . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . $outputsellDate . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . $row['list'] . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . $row['category'] . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . number_format( $row['buyPrice'] ) . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . number_format( $row['sellPrice'] ) . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . $outputsellProfit . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . $row['sellProvince'] . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . $row['sellSource'] . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . $row['deliveryCost'] . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center>" . $outputavalible . "</center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center><form action='editdata.php' method='POST' target='_blank' style='margin-block-end: 0px;'><input type='hidden' name='token' value='$totoken' /><input type='hidden' name='id' value='" . $row['id'] . "' /><button class='btn btn-sm btn-primary' type='submit'>" . _EDIT_THIS . "</button></form></center></div></td>\n";
											echo "											<td class='align-middle'><div class='abc'><center><form action='deletedata.php' method='POST' target='_blank' style='margin-block-end: 0px;'><input type='hidden' name='token' value='$totoken' /><input type='hidden' name='id' value='" . $row['id'] . "' /><button class='btn btn-sm btn-danger' type='submit'>" . _DELETE_THIS . "</button></form></center></div></td>\n";
											echo "										</tr>\n";
											
											$totalbuyPrice += $row["buyPrice"];
											$totalsellPrice += $row["sellPrice"];
											$totalsellProfit += $row["sellProfit"];
											$totaldeliveryCost += $row["deliveryCost"];
											
											usleep($delayUTime);
										}
										$outputsellProfit = null;
										$outputbuyDate = null;
										$outputsellDate = null;
										$listCount = mysqli_num_rows($result);
										
										//CHECK IF HAVE DATA DISPLAY
										if ($listCount > 0) {
											$nodata = FALSE;
										} else {
											$nodata = TRUE;
										}
									} else {
										//FIRST LOGIN PAGE
										echo "<div class='table-responsive'>
										<table class='table table-bordered w-auto' id='tabledata'>
										<thead>
											<tr class='table-active'>
												<th class='text-center align-middle'><div class='abc'>ID</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _BUY_DATE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _SELL_DATE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _PRODUCT_NAME . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _CATEGORY . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _BUY_PRICE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _SELL_PRICE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _SELL_PROFIT . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _CUSTOMER_PROVINCE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _CONTACT_FROM . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _DELIVERY_COST . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _IS_AVALIBLE . "</div></th>
												<th class='text-center align-middle'><div class='abc'>" . _EDIT_DATA . "</div></th>
												<th class='text-center align-middle'><div class='abc'>". _DELETE_DATA . "</div></th>
											</tr>
										</thead>
										<tbody>\n";
									}
									
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
									
									echo "										<tr class='table-active'>\n";
									echo "											<td class='text-center align-middle'></td>\n";
									echo "											<td class='text-center align-middle'></td>\n";
									echo "											<td class='text-center align-middle'></td>\n";
									echo "											<td class='text-center align-middle'></td>\n";
									echo "											<td class='text-center align-middle'></td>\n";
									echo "											<td class='text-center align-middle'><div class='abc'><center><font size='2'>" . _THIS_PAGE_TOTAL . " "  . number_format( $totalbuyPrice ) . " " . _CURRENCY . "</font></center></div></td>\n";
									echo "											<td class='text-center align-middle'><div class='abc'><center><font size='2'>" . _THIS_PAGE_TOTAL . " " . number_format( $totalsellPrice ) . " " . _CURRENCY . "</font></center></div></td>\n";
									echo "											<td class='text-center align-middle'><div class='abc'><center><font size='2'>" . _THIS_PAGE_TOTAL . " " . $outputTotalSellProfit . " " . _CURRENCY . "</font></center></div></td>\n";
									echo "											<td class='text-center align-middle'></td>\n";
									echo "											<td class='text-center align-middle'></td>\n";
									echo "											<td class='text-center align-middle'><div class='abc'><center><font size='2'>" . _THIS_PAGE_TOTAL . " " . number_format( $totaldeliveryCost ) . " " . _CURRENCY . "</font></center></div></td>\n";
									echo "											<td class='text-center align-middle'></td>\n";
									echo "											<td class='text-center align-middle'></td>\n";
									echo "											<td class='text-center align-middle'><div class='abc'><center><font size='2'>" . _THIS_PAGE_COUNT . " " . number_format( $listCount ) ." ". _LIST . "</font></center></div></td>\n";
									echo "										</tr>\n";
									echo "									</tbody>\n";
									echo "									</table>\n";
									echo "							</div>\n";
									$outputTotalSellProfit = null;

									//DELAY
									usleep($delayUTime);
	
									//CHECK ERROR STATE
									if ($connectionerror == TRUE) {
										//CONNECTION ERROR
										$modalWarn = _ERROR_DB_CONNECT . "<br>" . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8');
									} else if ($revertiveError == TRUE) {
										//REVERTIVE ERROR
										$modalWarn = _ERROR_WHILE_LOAD_DATA . "<br>" . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8');
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
											//MOVE DOWN TO SEE ERROR MESSAGE
											$showerrortb = TRUE;
			
											//SAVE AUTH FAIL
											date_default_timezone_set('Asia/Bangkok');
											$date = date("Y-m-d");
											$time = date("H:i:s");
											$page = "view.php";

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
								}
								if ($nodata AND ! $revertiveError AND ! $connectionerror AND ! $twocommand AND $_POST["ordermode"] != "0") {
									//NO DATA FOUND
									$modalWarn = _WARN_NO_DATA_FOUND;
								}
							?>
							<div id="pageselect" class="mb-3" style="margin-top: 8px; margin-right: 15px; float: left;">
								<form method="POST" action="view.php" style="margin-block-end: 12px;">
									<input type="hidden" name="token" value="<?= $totoken; ?>" />
									<?php
										if ($usermode == "custommonth") {
											echo "<input type='hidden' name='month' value='$month' />
											<input type='hidden' name='monthyear' value='$monthyear' />
											<input type='hidden' name='monthmode' value='$monthmode' />";
										} else {
											echo "<input type='hidden' name='$usermode' value='";
											echo $userstring;
											echo "' />\n";
										}
									?>
									<div class="input-group">
										<font size="3" class="mt-1"><?= _PAGE ?>: &nbsp;</font>
										<select id="page" name="page" class="form-control form-control-sm">
											<?php for($i=1;$i<=$totalpage;$i++){ ?>
												<option value="<?= $i; ?>"><?= $i; ?></option>
											<?php } ?>
										</select>
										<div class="input-group-append">
											<button class="btn btn-sm btn-success" type="submit" onclick="showloading(); hidePrintbtn()"><?= _SEARCH ?></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="p-3"></div>
			</center>
		</div>
		<div style="position: fixed; right: 25px; bottom: 55px; display: none;" id="noprint" class="fadein">
			<center>
				<form action="printview.php" method="POST" target="_blank" style="margin-block-end: 0px;">
					<input type="hidden" name="token" value="<?= $totoken; ?>" />
					<?php
						if ($usermode == "custommonth") {
							echo "<input type='hidden' name='month' value='$month' />
							<input type='hidden' name='monthyear' value='$monthyear' />
							<input type='hidden' name='monthmode' value='$monthmode' />";
						} else {
							echo "<input type='hidden' name='$usermode' value='" . htmlspecialchars($userstring) . "' />";
						}
					?>
					<?php if ($auth AND $authuser != "guest" AND ! $nodata AND ! $revertiveError AND ! $connectionerror AND $_POST["ordermode"] != "0") { echo "\n<button class='printbutton' id='printButton' style='font-size: 13px;' type='submit'>" . _PRINT_THIS . "</button>"; } ?>
				</form>
			</center>
		</div>
		<script>
			//CHECK IF USER USE LIMIT COMMAND
			var uselimit = <?= $uselimit; ?>;
			var selectpage = document.getElementById("page");
			var tabledata = document.getElementById("tabledata");
			if (uselimit == 0) {
				//NOT USE, CHECK IF PRINT BUTTON EXIST
				var printButton = document.getElementById("printButton");
				if (printButton) {
					//SELECT CURRENT PAGE
					selectpage.value = "<?= $selectpage; ?>";
					
					//DUPLICATE PAGE SELECTOR
					var bottomselectpage = document.getElementById("pageselect");
					var toppageselect = document.getElementById("pageselecttop");
					bottomselectpage = bottomselectpage.innerHTML.replace("id=\"page\"", "id=\"pagetop\"");
					bottomselectpage = bottomselectpage.replace("id=\"pageselect\"", "id=\"pageselecttop\"");
					bottomselectpage = bottomselectpage.replace("style=\"margin-top: 12px; margin-right: 15px; float: left;\"", "style=\"margin-top: 0px; margin-right: 15px; float: left;\"");
					
					toppageselect.innerHTML = bottomselectpage;
					
					//SELECT CURRENT PAGE TOP
					var selectpagetop = document.getElementById("pagetop");
					selectpagetop.value = "<?= $selectpage; ?>";
				} else {
					//NOT EXIST
					tabledata.style.marginTop = "12px";
				}
			} else {
				//USE
				selectpage.value = "0";
				tabledata.style.marginTop = "12px";
			}
			
			//----\\
			
			//SET SELECT OPTION ORDERMODE
			var selectmode = "<?= $_POST["ordermode"]; ?>";
			var optionelement = document.getElementById("ordermode");
			var option2element = document.getElementById("ordermode2");
			
			//CHECK IF FIRST PAGE
			if (selectmode != "0") {
				//CHECK IF ORDERMODE IS BLANK
				if (selectmode != "") {
					//CHECK IF IT IS ORDERMODE OR AVALIBLE LIST
					if (selectmode != "avaliblelist" && selectmode != "selllist") {
						//ORDER MODE
						optionelement.value = selectmode;
					} else {
						//AVALIBLE LIST OR SELL LIST
						optionelement.value = "highid";
						option2element.value = selectmode;
					}
				} else {
					optionelement.value = "highid";
				}
			} else {
				//FIRST PAGE
				optionelement.value = "highid";
			}
		</script>
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
		<noscript><img id='blockanother' width='100%' height='100%'><table border='1' id='tbjavascript' class='tbinfo'><tr><td class='curve'><div style='padding:28px 28px 28px 28px;'><font size='4'><strong><center><?=_ERROR_ENABLE_JS ?><br></center><img src='/blank.png' height='32' /><br></strong><center><a class='btn btn-sm btn-success' target='_blank' href='<?= _ENABLE_JS_SITE ?>'><?= _CONTINUE ?></a></center></font></div></td></tr></table></noscript>
	</body>
</html>
<?php 
	//CONTROL LOGIN FORM
	if ($auth AND $authuser != "guest") {
		//HIDE
		if ($modalWarn != "") {
			echo "\n<script>hidelogin(); document.getElementById('noprint').style.display = 'none'; $('#modalWarning').modal();</script>";
		} else {
			echo "\n<script>hidelogin(); document.getElementById('noprint').style.display = 'none';</script>";
		}
	} else {
		//SHOW
		if ($modalWarn != "") {
			echo "\n<script>showlogin(); hideloading(); $('#modalWarning').modal();</script>";
		} else {
			echo "\n<script>showlogin(); hideloading();</script>";
		}
	}
	echo "\n<script>ahideloading();</script>";
?>