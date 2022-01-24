<?php
	$editID = $_POST["id"];
	$doedit = $_POST["doedit"];
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
	
	$oldbuyDate = "";
	$oldsellDate = "";
	$oldbuyPrice = 0;
	$oldlist = "";
	$oldavaliblelist = "";
	$oldcategory = "";
	$oldsellPrice = 0;
	$oldsellProfit = 0;
	$oldsellProvince = 0;
	$olddeliveryCost = 0;
	$errortb = "";
	
	$editavalible = 0;
	
	$hidetb = "";

	if ($auth and $authuser != "guest") {
		if ($editID == NULL) {
			//NULL, NOTHING TO DO
			$modalDanger = _ENTER_BY_VIEW_ALL_DATA_PAGE;
		} else {
			//GET OLD DATA INFO, SET CONNECTION DETAIL
			//START SECTION: SQL LOGIN
			include "./module/sqllogin.php";
			//END SECTION: SQL LOGIN
	
			$connectionerror = FALSE;
			$oldinfo = 0;
			$olderror = FALSE;
			$nooldinfo = FALSE;
			$errormessage = "";

			//CREATE AND CHECK CONNECTION
			$conn = new mysqli($servername, $username, $password, $dbname);

			if ($conn->connect_error) {
				$connectionerror = TRUE;
			}
			
			settype($editID, "integer");
			
			//CHECK IF HAVE OLD INFORMATION
			$sqloldinfo = "SELECT id FROM $tb_name WHERE id=$editID";
			if ($res = mysqli_query($conn, $sqloldinfo)) { 
				$oldinfo = mysqli_num_rows($res);
			} else { 
				//ERROR
				$olderror = TRUE;
			}
			
			if ($oldinfo != 0 and ! $olderror) {
				//CHECK IF LIST AVALIBLE
				$sqlavalible = "SELECT * FROM $tb_name WHERE id=$editID";
				if ($res = mysqli_query($conn, $sqlavalible)) { 
					while($asrow = mysqli_fetch_array($res)) {
						$editavalible = $asrow['avalible'];
					}
				} else { 
					//ERROR
					$olderror = TRUE;
				}

				if ($editavalible == 1) {
					//LIST AVALIBLE, GET PRODUCTS INFORMATION
					
					$hidetb = "<script>document.getElementById('tbnotavalible').style.display = 'none';</script>";
					
					$sql = "SELECT buyDate, list, category, buyPrice FROM $tb_name WHERE id=$editID";
					$retval = mysqli_query($conn, $sql);
	   
					if(! $retval) {
						$olderror = TRUE;
						$errormessage = $conn->error;
					}
			
					while($avaliblerow = mysqli_fetch_array($retval)) {
						$oldbuyDate = $avaliblerow['buyDate'];
						$oldlist = $avaliblerow['list'];
						$oldcategory = $avaliblerow['category'];
						$oldbuyPrice = $avaliblerow['buyPrice'];
					}

					$oldbuyDate = date("d-m-Y", strtotime($oldbuyDate));
				} else {
					//LIST NOT AVALIBLE, GET PRODUCTS INFORMATION
					
					$hidetb = "<script>document.getElementById('tbavalible').style.display = 'none';</script>";
					
					$sql = "SELECT * FROM $tb_name WHERE id=$editID";
					$retval = mysqli_query($conn, $sql);
	   
					if(! $retval) {
						$olderror = TRUE;
						$errormessage = $conn->error;
					}
			
					while($notavaliblerow = mysqli_fetch_array($retval)) {
						$oldbuyDate = $notavaliblerow['buyDate'];
						$oldsellDate = $notavaliblerow['sellDate'];
						$oldbuyPrice = $notavaliblerow['buyPrice'];
						$oldlist = $notavaliblerow['list'];
						$oldcategory = $notavaliblerow['category'];
						$oldsellPrice = $notavaliblerow['sellPrice'];
						$oldsellProfit = $notavaliblerow['sellProfit'];
						$oldsellProvince = $notavaliblerow['sellProvince'];
						$oldsellSource = $notavaliblerow['sellSource'];
						$olddeliveryCost = $notavaliblerow['deliveryCost'];
					}

					$oldbuyDate = date("d-m-Y", strtotime($oldbuyDate));
					$oldsellDate = date("d-m-Y", strtotime($oldsellDate));
				}

				$oldavaliblelist = $oldlist;

				//CHECK IF LIST IS LONGER THAN 21
				if (strlen($oldavaliblelist) > 21) {
					$memlist = $oldavaliblelist;
					$oldavaliblelist = "&nbsp;<button type=\"button\" onclick=\"showOlddata('" . _OLD_DATA_OF_PRODUCT_NAME . "', '$memlist')\" class=\"btn btn-sm btn-success\">...</button>\n";

					$memlist = null;
				}

				usleep($delayUTime);
				
				//CHECK ERROR STATE
				if ($connectionerror == TRUE) {
					//CONNECTION ERROR
					$modalDanger = _ERROR_DB_CONNECT . "<br>" . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8');
				} else if ($olderror == TRUE) {
					//GET ERROR
					$modalDanger = _ERROR_WHILE_LOAD_DATA . "<br>" . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8');
				} else {
					//NO ERROR FOUND (SUCESSFULLY)
					
				}
				
				//CLOSE CONNECTION
				$conn->close();
			} else {
				//NO OLD INFORMATION FOUND
				$modalDanger = _ERROR_ID_NOT_FOUND . " " . number_format($editID);
			}
		}
	} else {
		//NOT FAIL BUT ?
		$modalDanger = _ENTER_BY_VIEW_ALL_DATA_PAGE;
				
		//SAVE AUTH FAIL
		date_default_timezone_set('Asia/Bangkok');
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$page = "editdata.php?id=$editID";

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
	$inputbuyDate = date("Y-m-d", strtotime($oldbuyDate));
	$inputsellDate = date("Y-m-d", strtotime($oldsellDate));
?>
<html>
	<head>
		<title><?= _EDIT_DATA ?></title>
		<!-- BOOTSTRAP -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

		<link rel='stylesheet' href='./main.css' type='text/css' />
		<link rel='shortcut icon' type='img/icon' href='favicon.ico'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
		<script src='js/pzDS_add.js'></script>
		<script src='js/pzDS_admin.js'></script>
		<script src='js/main.js'></script>
		<script>
			var enterCompleteInfo = "<?= _ENTER_COMPLETE_INFO ?>";
			var ontopCount = 0;

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
									document.getElementById('loading').style.display = 'none';			
								}, 1000);
								
								clearTimeout(waittimeout);
							}    
						}, 300);
			
						//WAITING FOR PAGE TO LOAD COMPLETE TIMEOUT (10 SECONDS)
						var waittimeout = setTimeout(function(){
							clearInterval(intervalLoad);
							clearInterval(intervalsetTop);
							document.getElementById('loading').style.display = 'none';			
						}, <?= _HIDE_LOADING_TIMEOUT ?>);
					} else {
						document.getElementById('loading').style.display = 'none';
					}
				}, 1000);
			}
			
			var intervalsetTop = setInterval(function() {
				if (ontopCount < 7) {
					ontopCount += 1;
					var loadingElement = document.getElementById('loading');
					loadingElement.parentNode.appendChild(loadingElement);
					
					//CHECK IF PAGE LOAD COMPLETE
					if (document.readyState === 'complete') {
						clearInterval(intervalsetTop);
					}    
				} else {
					clearInterval(intervalsetTop);
				}
			}, 300);

			function showOlddata(oldDT, oldDD) {
				var olddataContainer = document.getElementById("olddata");

				document.getElementById("modalInfoTitle").innerHTML = oldDT;
				document.getElementById("modalInfoContent").innerHTML = oldDD;
				$("#modalInfo").modal();
			}

			function hideOlddata() {
				$("#modalInfo").modal("hide");
			}
		</script>
	</head>
	<body onload="hidewarnes(); hidewarne()">
		<div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-success navbar-light fixed-top">
                <img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b><?= _DATA_SYSTEMS ?></b></font></span>
            </nav>
            <br/>
			<center>
				<div class="container-md" style="margin-top:54px;" id="tbavalible">
					<div>
						<font size="5">
							<b><?= _EDIT_DATA ?></b>
						</font>
					</div>
					<div class="container-fluid pt-2">
						<div class="pb-2">
							<font size="3"><?= _COMPLETE_INFO ?></font>
						</div>
						<form name="buyInfo" id="buyInfo" method="POST" style="margin-block-end: 12px; margin-bottom: 6px;" onkeydown="return event.key != 'Enter';">
							<table class="table table-bordered">
								<thead>
									<tr class="table-active">
										<th width="35%">
											<font size="4"><center><?= _TYPE ?></center></font>
										</th>
										<th width="65%">
											<font size="4"><center><?= _DATA ?></center></font>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _BUY_DATE ?></center></font>
										</td>
										<td width="65%">
											<font size="3">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
														</div>
														<input type="date" name="buyDate" id="buyDates" class="form-control" value="<?= $inputbuyDate ?>">
													</div>
													<div class="pt-2"></div>
													<?= _OLD_DATA ?>: <?= $oldbuyDate; ?>
												</center>
											</font>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _PRODUCT_NAME ?></center></font>
										</td>
										<td width="65%">
											<font size="3">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-file-signature"></i></span>
														</div>
														<input type="text" name="list" id="lists" class="form-control" value="<?= $oldlist ?>" maxlength="200">
													</div>
													<div class="pt-2"></div>
													<?= _OLD_DATA ?>: <?= $oldavaliblelist; ?>
												</center>
											</font>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _CATEGORY ?></center></font>
										</td>
										<td width="65%">
											<center>
												<div class="input-group" style="width: 86%;">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fas fa-ellipsis-v"></i></span>
													</div>
													<select name="category" id="categorys" class="form-control" value="<?= $oldcategory ?>">
														<?php
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
																	echo "																	<option value='" . $result . "'>" . $result . "</option>\n";
																//CHECK IF FIRST LINE
																} else if ($categoryechocount == 1) {
																	echo "<option value='" . $result . "'>" . $result . "</option>\n";
																} else {
																	echo "																	<option value='" . $result . "'>" . $result . "</option>\n";
																}
															}
															fclose($fn);
														?>
													</select>
												</div>
												<div class="pt-2"></div>
												<?= _OLD_DATA ?>: <?= $oldcategory; ?>
											</center>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _BUY_PRICE ?></center></font>
										</td>
										<td width="65%">
											<font size="3">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-money-bill"></i></span>
														</div>
														<input type="number" name="buyPrice" id="buyPrices" class="form-control" value="<?= $oldbuyPrice ?>">
													</div>
													<div class="pt-2"></div>
													<?= _OLD_DATA ?>: <?= $oldbuyPrice; ?> <?= _CURRENCY ?>
												</center>
											</font>
										</td>
									</tr>
								</tbody>
							</table>
							<input type="hidden" name="token" value="<?= $totoken ?>">
							<div class="pb-3">
								<button class="btn btn-primary" onclick="infoContinuees()" id="btn11" type="button"><?= _NEXT ?></button>
								<button class="btn btn-danger" type="submit" id="btn22" onclick="showloading()"><?= _SAVE ?></button>
								<button class="btn btn-success" id="btn33" onclick="hidewarnes()" type="button"><?= _BACK_TO_EDIT ?></button>
							</div>
							<input type="hidden" name="token" value="<?= $_POST["token"]; ?>" />
							<input type="hidden" name="id" value="<?= $_POST["id"]; ?>">
							<input type="hidden" name="listavalible" value="1">
							<input type="hidden" name="doedit" value="1">
						</form>
					</div>
				</div>
			</center>
			<!-- -->
			<center>
				<div class="container-md" id="tbnotavalible" style="margin-top:54px;">
					<div>
						<font size="5">
							<b><?= _ADD_SELL ?></b>
						</font>
					</div>
					<div class="container-fluid pt-2">
						<div class="pb-2">
							<font size="3"><?= _COMPLETE_INFO ?></font>
						</div>
						<form name="sellInfo" id="sellInfo" method="POST" style="margin-block-end: 12px; margin-bottom: 6px;" onkeydown="return event.key != 'Enter';">
							<table class="table table-bordered">
								<thead>
									<tr class="table-active">
										<th width="35%">
											<font size="4"><center><?= _TYPE ?></center></font>
										</th>
										<th width="65%">
											<font size="4"><center><?= _DATA ?></center></font>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _BUY_DATE ?></center></font>
										</td>
										<td width="65%">
											<font size="3" class="align-middle">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
														</div>
														<input type="date" name="buyDate" id="buyDate" class="form-control" value="<?= $inputbuyDate; ?>">
													</div>
													<div class="pt-2"></div>
													<?= _OLD_DATA ?>: <?= $oldbuyDate; ?>
												</center>
											</font>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _SELL_DATE ?></center></font>
										</td>
										<td width="65%">
											<font size="3" class="align-middle">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-calendar-minus"></i></span>
														</div>
														<input type="date" name="sellDate" id="sellDate" class="form-control" value="<?= $inputsellDate; ?>">
													</div>
													<div class="pt-2"></div>
													<?= _OLD_DATA ?> : <?= $oldsellDate; ?>
												</center>
											</font>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _PRODUCT_NAME ?></center></font>
										</td>
										<td width="65%">
											<font size="3" class="align-middle">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-file-signature"></i></span>
														</div>
														<input type="text" name="list" id="list" class="form-control" value="<?= $oldlist; ?>" maxlength="200">
													</div>
													<div class="pt-2"></div>
													<?= _OLD_DATA ?> : <?= $oldavaliblelist; ?>
												</center>
											</font>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _CATEGORY ?></center></font>
										</td>
										<td width="65%" class="align-middle">
											<center>
												<div class="input-group" style="width: 86%;">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fas fa-ellipsis-v"></i></span>
													</div>
													<select name="category" id="category" class="form-control" value="<?= $oldcategory ?>">
														<?php
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
																	echo "																	<option value='" . $result . "'>" . $result . "</option>\n";
																//CHECK IF FIRST LINE
																} else if ($categoryechocount == 1) {
																	echo "<option value='" . $result . "'>" . $result . "</option>\n";
																} else {
																	echo "																	<option value='" . $result . "'>" . $result . "</option>\n";
																}
															}
															fclose($fn);
														?>
													</select>
												</div>
												<div class="pt-2"></div>
												<?= _OLD_DATA ?>: <?= $oldcategory; ?>
											</center>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _BUY_PRICE ?></center></font>
										</td>
										<td width="65%">
											<font size="3" class="align-middle">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-money-bill"></i></span>
														</div>
														<input type="number" name="buyPrice" id="buyPrice" class="form-control" value="<?= $oldbuyPrice; ?>">
													</div>
													<div class="pt-2"></div>
													<?= _OLD_DATA ?>: <?= $oldbuyPrice; ?> <?= _CURRENCY ?>
												</center>
											</font>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _SELL_PRICE ?></center></font>
										</td>
										<td width="65%">
											<font size="3" class="align-middle">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-hand-holding-usd"></i></span>
														</div>
														<input type="number" name="sellPrice" id="sellPrice" class="form-control" value="<?= $oldsellPrice; ?>">
													</div>
													<div class="pt-2"></div>
													<?= _OLD_DATA ?>: <?= $oldsellPrice; ?> <?= _CURRENCY ?>
												</center>
											</font>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _SELL_PROFIT ?></center></font>
										</td>
										<td width="65%">
											<font size="3" class="align-middle">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-funnel-dollar"></i></span>
														</div>
														<input type="number" name="sellProfit" id="sellProfit" class="readonly form-control" style="<?php if ($oldsellProfit < 0) { echo " color: red;"; } ?>" value="<?= $oldsellProfit; ?>" readonly="true">
													</div>
												</center>
											</font>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _CUSTOMER_PROVINCE ?></center></font>
										</td>
										<td width="65%" class="align-middle">
											<font size="3">
												<center>
													<select name="sellProvince" id="sellProvince" class="selectpicker" data-size="10" data-width="86%" data-live-search="true" value="<?= $oldsellProvince; ?>">
														<option value='กรุงเทพมหานคร'>กรุงเทพมหานคร</option>
														<option value='กระบี่'>กระบี่ </option>
														<option value='กาญจนบุรี'>กาญจนบุรี </option>
														<option value='กาฬสินธุ์'>กาฬสินธุ์ </option>
														<option value='กำแพงเพชร'>กำแพงเพชร </option>
														<option value='ขอนแก่น'>ขอนแก่น</option>
														<option value='จันทบุรี'>จันทบุรี</option>
														<option value='ฉะเชิงเทรา'>ฉะเชิงเทรา </option>
														<option value='ชัยนาท'>ชัยนาท </option>
														<option value='ชัยภูมิ'>ชัยภูมิ </option>
														<option value='ชุมพร'>ชุมพร </option>
														<option value='ชลบุรี'>ชลบุรี </option>
														<option value='เชียงใหม่'>เชียงใหม่ </option>
														<option value='เชียงราย'>เชียงราย </option>
														<option value='ตรัง'>ตรัง </option>
														<option value='ตราด'>ตราด </option>
														<option value='ตาก'>ตาก </option>
														<option value='นครนายก'>นครนายก </option>
														<option value='นครปฐม'>นครปฐม </option>
														<option value='นครพนม'>นครพนม </option>
														<option value='นครราชสีมา'>นครราชสีมา </option>
														<option value='นครศรีธรรมราช'>นครศรีธรรมราช </option>
														<option value='นครสวรรค์'>นครสวรรค์ </option>
														<option value='นราธิวาส'>นราธิวาส </option>
														<option value='น่าน'>น่าน </option>
														<option value='นนทบุรี'>นนทบุรี </option>
														<option value='บึงกาฬ'>บึงกาฬ</option>
														<option value='บุรีรัมย์'>บุรีรัมย์</option>
														<option value='ประจวบคีรีขันธ์'>ประจวบคีรีขันธ์ </option>
														<option value='ปทุมธานี'>ปทุมธานี </option>
														<option value='ปราจีนบุรี'>ปราจีนบุรี </option>
														<option value='ปัตตานี'>ปัตตานี </option>
														<option value='พะเยา'>พะเยา </option>
														<option value='พระนครศรีอยุธยา'>พระนครศรีอยุธยา </option>
														<option value='พังงา'>พังงา </option>
														<option value='พิจิตร'>พิจิตร </option>
														<option value='พิษณุโลก'>พิษณุโลก </option>
														<option value='เพชรบุรี'>เพชรบุรี </option>
														<option value='เพชรบูรณ์'>เพชรบูรณ์ </option>
														<option value='แพร่'>แพร่ </option>
														<option value='พัทลุง'>พัทลุง </option>
														<option value='ภูเก็ต'>ภูเก็ต </option>
														<option value='มหาสารคาม'>มหาสารคาม </option>
														<option value='มุกดาหาร'>มุกดาหาร </option>
														<option value='แม่ฮ่องสอน'>แม่ฮ่องสอน </option>
														<option value='ยโสธร'>ยโสธร </option>
														<option value='ยะลา'>ยะลา </option>
														<option value='ร้อยเอ็ด'>ร้อยเอ็ด </option>
														<option value='ระนอง'>ระนอง </option>
														<option value='ระยอง'>ระยอง </option>
														<option value='ราชบุรี'>ราชบุรี</option>
														<option value='ลพบุรี'>ลพบุรี </option>
														<option value='ลำปาง'>ลำปาง </option>
														<option value='ลำพูน'>ลำพูน </option>
														<option value='เลย'>เลย </option>
														<option value='ศรีสะเกษ'>ศรีสะเกษ</option>
														<option value='สกลนคร'>สกลนคร</option>
														<option value='สงขลา'>สงขลา </option>
														<option value='สมุทรสาคร'>สมุทรสาคร </option>
														<option value='สมุทรปราการ'>สมุทรปราการ </option>
														<option value='สมุทรสงคราม'>สมุทรสงคราม </option>
														<option value='สระแก้ว'>สระแก้ว </option>
														<option value='สระบุรี'>สระบุรี </option>
														<option value='สิงห์บุรี'>สิงห์บุรี </option>
														<option value='สุโขทัย'>สุโขทัย </option>
														<option value='สุพรรณบุรี'>สุพรรณบุรี </option>
														<option value='สุราษฎร์ธานี'>สุราษฎร์ธานี </option>
														<option value='สุรินทร์'>สุรินทร์ </option>
														<option value='สตูล'>สตูล </option>
														<option value='หนองคาย'>หนองคาย </option>
														<option value='หนองบัวลำภู'>หนองบัวลำภู </option>
														<option value='อำนาจเจริญ'>อำนาจเจริญ </option>
														<option value='อุดรธานี'>อุดรธานี </option>
														<option value='อุตรดิตถ์'>อุตรดิตถ์ </option>
														<option value='อุทัยธานี'>อุทัยธานี </option>
														<option value='อุบลราชธานี'>อุบลราชธานี</option>
														<option value='อ่างทอง'>อ่างทอง </option>
														<option value='อื่นๆ'>อื่นๆ</option>
													</select>
													<div class="pt-2"></div>
													<?= _OLD_DATA ?>: <?= $oldsellProvince; ?>
												</center>
											</font>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _CONTACT_FROM ?></center></font>
										</td>
										<td width="65%">
											<font size="3" class="align-middle">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-bullhorn"></i></span>
														</div>
														<select name="sellSource" id="sellSource" class="form-control" value="<?= $oldsellSource; ?>">
															<?php
																$sourcelistpath = "././module/sourcelist.txt";
																$sourcelistcount = $lines = count(file($sourcelistpath));
																$sourceechocount = 0;
																
																$fns = fopen($sourcelistpath,"r");
																while(! feof($fns))  {
																	$sourceechocount += 1;
																	$results = fgets($fns);
																	$results = str_replace("\n", "", $results);
																	
																	//CHECK IF LAST LINE
																	if ($sourceechocount != $sourcelistcount) {
																		echo "																	<option value='" . $results . "'>" . $results . "</option>\n";
																	//CHECK IF FIRST LINE
																	} else if ($sourceechocount == 1) {
																		echo "<option value='" . $results . "'>" . $results . "</option>\n";
																	} else {
																		echo "																	<option value='" . $results . "'>" . $results . "</option>\n";
																	}
																}
																fclose($fns);
															?>
														</select>
													</div>
													<div class="pt-2"></div>
													<?= _OLD_DATA ?>: <?= $oldsellSource; ?>
												</center>
											</font>
										</td>
									</tr>
									<tr>
										<td width="35%" class="align-middle">
											<font size="3"><center><?= _DELIVERY_COST ?></center></font>
										</td>
										<td width="65%">
											<font size="3" class="align-middle">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-truck"></i></span>
														</div>
														<input type="number" name="deliveryCost" id="deliveryCost" class="form-control" value="<?= $olddeliveryCost; ?>">
													</div>
													<div class="pt-2"></div>
													<?= _OLD_DATA ?>: <?= $olddeliveryCost; ?>
												</center>
											</font>
										</td>
									</tr>
								</tbody>
							</table>
							<div class="pb-3">
								<button class="btn btn-primary" onclick="infoContinuee()" id="btn1" type="button"><?= _NEXT ?></button>
								<button class="btn btn-danger" type="submit" id="btn2" onclick="showloading()"><?= _SAVE ?></button>
								<button class="btn btn-success" id="btn3" onclick="hidewarne()" type="button"><?= _BACK_TO_EDIT ?></button>
							</div>
							<input type="hidden" name="token" value="<?= $_POST["token"]; ?>" />
							<input type="hidden" name="id" value="<?= $_POST["id"]; ?>">
							<input type="hidden" name="listavalible" value="0">
							<input type="hidden" name="doedit" value="1">
						</form>
					</div>
				</div>
				<div class="p-3"></div>
			</center>
		</div>
		<?php 
			echo "<script>
			SelectElement('category', '$oldcategory');
			SelectElement('categorys', '$oldcategory');
			SelectElement('sellProvince', '$oldsellProvince');
			SelectElement('sellSource', '$oldsellSource');
			function SelectElement(id, valueToSelect) {    
				if (valueToSelect != '') {
					var element = document.getElementById(id);
					element.value = valueToSelect;
				}
			}
			</script>";
		?>
		<div class="blockall" id="loading" style="display:block;">
			<div class="center">
			<img src="wheel.svg" width="48" height="48" />
			</div>
		</div>
		<?= $hidetb; ?>
		<div id="errortb">
			<?php
				echo $errortb;
			?>
		</div>
		<div class="modal fade" id="modalInfo">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<font size="5" class="modal-title"><b><span id="modalInfoTitle"><?= _NOTIFY ?></span></b></font>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<font size="3"><span id="modalInfoContent"><?= $modalInfo ?></span></font>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-success" data-dismiss="modal"><?= _CLOSE_MESSAGE ?></button>
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
						<button type="button" class="btn btn-sm btn-danger" onclick="javascript: close_window();" data-dismiss="modal"><?= _CLOSE_PAGE ?></button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="modalSuccess" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<font size="5" class="modal-title"><b><?= _NOTIFY ?></b></font>
					</div>
					<div class="modal-body">
						<font size="3"><?= _SUCCESSFULLY_EDIT_DATA ?></font>
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-success" onclick="javascript: close_window();" data-dismiss="modal"><?= _CLOSE_PAGE ?></button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<?php
	if ($auth and $authuser != "guest") {
		if ($modalWarn != "") {
			echo "\n<script>$('#modalWarning').modal();</script>";
		} 
		if ($modalDanger != "") {
			echo "\n<script>$('#modalDanger').modal();</script>";
		}

		if ($doedit == 1) {
			//GET PRODUCTS INFORMATION
			$buyDat = $_POST['buyDate'];
			$sellDat = $_POST['sellDate'];
			$list = $_POST['list'];
			$category = $_POST['category'];
			$sellPrice = $_POST['sellPrice'];
			$sellProfit = $_POST['sellProfit'];
			$sellProvince = $_POST['sellProvince'];
			$sellSource = $_POST['sellSource'];
			$listavalible = $_POST['listavalible'];
			$buyPrice = $_POST['buyPrice'];
			$deliveryCost = $_POST['deliveryCost'];
		
			$isblank = FALSE;
			$issymbolerror = FALSE;
			$islengthmorethan = FALSE;
			$isvariableillegal = FALSE;
			
			$buyDate = date("d-m-Y", strtotime($buyDat));
			$sellDate = date("d-m-Y", strtotime($sellDat));
			$buyDate = date("Y-m-d", strtotime($buyDate));
			$sellDate = date("Y-m-d", strtotime($sellDate));

			//CHECK IF ONE BLANK AND VARIABLES TYPE
			if ($listavalible == 0) {
				if (trim($buyDat) == "" or trim($buyPrice) == "" or trim($sellDat) == "" or trim($list) == "" or trim($category) == "" or trim($sellPrice) == "" or trim($sellProfit) == "" or trim($sellProvince) == "" or trim($sellSource) == "" or trim($deliveryCost) == "") {
					//BLANK
					$isblank = TRUE;
				} else {
					$isblank = FALSE;
				}
				
				settype($buyPrice, "integer");
				settype($sellPrice, "integer");
				settype($sellProfit, "integer");
				settype($deliveryCost, "integer");
				if (gettype($buyPrice) != "integer" or gettype($sellPrice) != "integer" or gettype($sellProfit) != "integer") {
					//ILLEGAL VARIABLE(S) TYPE
					$isvariableillegal = TRUE;
					
					echo '<script>
						document.getElementById("modalWarningContent").innerHTML = "' . _ERROR_ILLEGAL_ANY_PRICE . '";
						$("#modalWarning").modal();
					</script>';
				} else {
					$isvariableillegal = FALSE;
				}
			} else if ($listavalible == 1) {
				if (trim($buyDat) == "" or trim($buyPrice) == "" or trim($list) == "" or trim($category) == "" or trim($buyDate) == "") {
					//BLANK
					$isblank = TRUE;
				} else {
					$isblank = FALSE;
				}
				
				settype($buyPrice, "integer");
				if (gettype($buyPrice) != "integer") {
					//ILLEGAL VARIABLE(S) TYPE
					$isvariableillegal = TRUE;

					echo '<script>
						document.getElementById("modalWarningContent").innerHTML = "' . _ERROR_ILLEGAL_ANY_PRICE . '";
						$("#modalWarning").modal();
					</script>';
				} else {
					$isvariableillegal = FALSE;
				}
			}

			//INCLUDE SYMBOL CHECK MODULE
			include "./module/symbolcheck.php";
			
			//CHECK IF POST IS NOT BLANK AND NOT ILLEGAL VARIABLE(S) BEFORE DO CHECK ANOTHER CONDITION
			if (! $isblank and ! $isvariableillegal) {
				//CHECK LIST
				if (symbolcheck($list) != "ok") {
					//SYMBOL ERROR
					$issymbolerror = TRUE;
					echo '<script>
						document.getElementById("modalWarningContent").innerHTML = "' . _CHANGE_SYMBOL . symbolcheck($list) .  " " . _IN_PRODUCT_NAME . '";
						$("#modalWarning").modal();
					</script>';
				}
				
				//CHECK IF LIST LENGTH MORE THAN 200
				if (strlen($list) > 200) {
					//LIST LENGTH MORE THAN 200
					$islengthmorethan = TRUE;
					echo '<script>
						document.getElementById("modalWarningContent").innerHTML = "' . _PRODUCT_NAME . " " . _CANNOT_LONGER_THAN . " 200 " . _CHARACTER . '";
						$("#modalWarning").modal();
					</script>';
				} else {
					//NOTHING, PASS
				}
			} else {
				//DO NOTHING
			}
			
			//CHECK STATEMENT
			if (! $isblank and ! $issymbolerror and ! $islengthmorethan and ! $isvariableillegal) {
				//CONTINUE, CHECK IF PROFIT MORE THAN SELLPRICE
				$profitMoreThan = FALSE;
				if ($sellProfit > $sellPrice) {
					//MORE THAN, SHOW ERROR
					echo '<script>
						document.getElementById("modalWarningContent").innerHTML = "' . _ERROR_PROFIT_MORE_THAN . '";
						$("#modalWarning").modal();
					</script>';
				} else {
					//CONTINUE, SET CONNECTION DETAIL
					//START SECTION: SQL LOGIN
					include "./module/sqllogin.php";
					//END SECTION: SQL LOGIN
	
					$connectionerror = FALSE;
					$updateerror = FALSE;
					$errormessage = "";

					//CREATE AND CHECK CONNECTION
					$conn = new mysqli($servername, $username, $password, $dbname);

					if ($conn->connect_error) {
						$connectionerror = TRUE;
					}
		
					//CHECK IF LIST AVALIBLE
					if ($listavalible == 0) {
						//LIST NOT AVALIBLE, CALCULATE SELLPROFIT AND UPDATE PRODUCTS INFORMATION
						$sellProfit = ($sellPrice - $buyPrice);
						$sql = "UPDATE $tb_name SET id=$editID, buyDate='" . mysqli_real_escape_string( $conn, $buyDate ) . "', sellDate='" . mysqli_real_escape_string( $conn, $sellDate ) . "', buyPrice=$buyPrice, list='" . mysqli_real_escape_string( $conn, $list ) . "', category='" . mysqli_real_escape_string( $conn, $category ) . "', sellPrice=$sellPrice, sellProfit=$sellProfit, sellProvince='" . mysqli_real_escape_string( $conn, $sellProvince ) . "', sellSource='" . mysqli_real_escape_string( $conn, $sellSource ) . "',deliveryCost=$deliveryCost WHERE id=$editID";
						$retval = mysqli_query($conn, $sql);
	  
						if(! $retval) {
							$updateerror = TRUE;
							$errormessage = $conn->error;
						}
						
					} else if ($listavalible == 1) {
						//LIST AVALIBLE, CALCULATE SELLPROFIT AND UPDATE PRODUCTS INFORMATION
						$sellProfit = ($sellPrice - $buyPrice);
						$sql = "UPDATE $tb_name SET buyDate='" . mysqli_real_escape_string( $conn, $buyDate ) . "', list='" . mysqli_real_escape_string( $conn, $list ) . "', category='" . mysqli_real_escape_string( $conn, $category ) . "', buyPrice=$buyPrice WHERE id=$editID";
						$retval = mysqli_query($conn, $sql);
	   
						if(! $retval) {
							$updateerror = TRUE;
							$errormessage = $conn->error;
						}
					}

					//CHECK ERROR STATE
					if ($connectionerror == TRUE) {
						//CONNECTION ERROR
						echo "<script>
							document.getElementById('modalWarningContent').innerHTML = '" . _ERROR_DB_CONNECT . "<br>" . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8') . "';
							$('#modalWarning').modal();
						</script>";
					} else if ($updateerror == TRUE) {
						//UPDATE ERROR
						echo "<script>
							document.getElementById('modalWarningContent').innerHTML = '" . _ERROR_WHILE_UPDATE_DATA . "<br>" . htmlspecialchars($errormessage, ENT_QUOTES, 'UTF-8') . "';
							$('#modalWarning').modal();
						</script>";
					} else {
						//NO ERROR FOUND (SUCCESSFULLY)
						echo "<script>
							$('#modalSuccess').modal();
						</script>";
					}
					
					//CLOSE CONNECTION
					$conn->close();
				}
			} else {
				//BLANK, SYMBOL ERROR, LENGTH ERROR OR ILLEGAL VARIABLE(S)
			}
		}
		
		//CHECK IF PAGE WAS REFRESHED, TELL USER TO DISABLE LITE MODE OR DATA SAVING MODE
		$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
		if ($pageWasRefreshed) {
			//CHECK IF NO DATA IS POST
			if ($isblank) {
				echo "<script>
					document.getElementById('modalWarningContent').innerHTML = '" . _WARN_LITE_MODE_ON . "';
					$('#modalWarning').modal();
				</script>";
			} else {
				//DO NOTHING
			}
		} else {
			//DO NOTHING
		}
	} else {
		if ($modalWarn != "") {
			echo "\n<script>$('#modalWarning').modal();</script>";
		} 
		if ($modalDanger != "") {
			echo "\n<script>$('#modalDanger').modal();</script>";
		}

		//NOT FAIL BUT ?
		if (trim($sellDat) == "" or trim($list) == "" or trim($category) == "" or trim($sellPrice) == "" or trim($sellProfit) == "" or trim($sellProvince) == "" or trim($sellSource) == "") {
		
		} else {
			echo "<script>
				hideloading();
				document.getElementById('modalDangerContent').innerHTML = '" . _ENTER_BY_VIEW_ALL_DATA_PAGE . "';
				$('#modalWarning').modal();
			</script>";
			
			//SAVE AUTH FAIL
			date_default_timezone_set('Asia/Bangkok');
			$date = date("Y-m-d");
			$time = date("H:i:s");
			$page = "editdata.php?id=$editID";
		
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
			$sql = "INSERT INTO $tb_nameLoginFail(date, time, ip, page) VALUES ('$date', '$time', '$clientIP', '$page')";
			$retval = mysqli_query($conn, $sql);
   
			if(! $retval) {
		
			}
	
			//CLOSE CONNECTION
			$conn->close();
		}
	}
	echo "\n<script>ahideloading();</script>";
	echo "\n<noscript><img id='blockanother' width='100%' height='100%'><table border='1' id='tbjavascript' class='tbinfo'><tr><td class='curve'><div style='padding:28px 28px 28px 28px;'><font size='4'><strong><center>" . _ERROR_ENABLE_JS . "<br></center><img src='/blank.png' height='32' /><br></strong><center><a class='myButton' target='_blank' href='" . _ENABLE_JS_SITE . "'>" . _CONTINUE . "</a></center></font></div></td></tr></table></noscript>";?>