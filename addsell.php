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

	$asellDate = date("d-m-Y", strtotime($_POST["sellDate"]));
	$inputsellDate = date("Y-m-d", strtotime($asellDate));
	
	if (strpos($inputsellDate, "1970") !== FALSE) {
		$inputsellDate = date('Y-m-d', time());
	}
	
	//DO ADD SELL
	if ($auth AND $authuser != "guest") {
		//GET PRODUCTS INFORMATION
		$buyDate = $_POST['buyDate'];
		$buyPrice = $_POST['buyPrice'];
		$sellDat = $_POST['sellDate'];
		$category = $_POST['category'];
		$sellPrice = $_POST['sellPrice'];
		$sellProfit = ($sellPrice - $buyPrice);
		$sellProvince = $_POST['sellProvince'];
		$sellSource = $_POST['sellSource'];
		$deliveryCost = $_POST['deliveryCost'];
		$updateid = $_POST['updateid'];

		settype($deliveryCost, "integer");
		settype($updateid, "integer");
	
		$sellDate = date("d-m-Y", strtotime($sellDat));
		$sellDate = date("Y-m-d", strtotime($sellDate));
		settype($sellPrice, "integer");
		
		//SET VARIABLES
		$updateerror = FALSE;

		//INCLUDE SYMBOL CHECK MODULE
		include "./module/symbolcheck.php";

		//CHECK IF ONE BLANK
		if (trim($sellDat) == "" or trim($category) == "" or trim($sellPrice) == "" or trim($sellProfit) == "" or trim($sellProvince) == "" or trim($sellSource) == "" or trim($deliveryCost) == "") {
			//BLANK, NOTHING
		//CHECK IF SELL PRICE IS INTEGER
		} else if (gettype($sellPrice) != "integer") {
			$modalWarn = _ERROR_ILLEGAL_SELL_PRICE;
		} else {
			//CONTINUE, CHECK IF PROFIT MORE THAN OR EQUAL TO SELLPRICE
			$profitMoreThan = FALSE;
			if ($sellProfit > $sellPrice) {
				//MORE THAN, SHOW ERROR
				$modalWarn = _ERROR_PROFIT_MORE_THAN;
			} else {
				//CONTINUE, SET CONNECTION DETAIL
				//START SECTION: SQL LOGIN
				include "./module/sqllogin.php";
				//END SECTION: SQL LOGIN

				//CREATE AND CHECK CONNECTION
				$conn = new mysqli($servername, $username, $password, $dbname);

				if ($conn->connect_error) {
					$connectionerror = TRUE;
				}
	
				//INSERT PRODUCTS INFORMATION
				$sql = "UPDATE $tb_name SET sellDate='" . mysqli_real_escape_string( $conn, $sellDate ) . "',sellPrice=$sellPrice,sellProfit=$sellProfit,sellProvince='" . mysqli_real_escape_string( $conn, $sellProvince ) . "',sellSource='" . mysqli_real_escape_string( $conn, $sellSource ) . "',deliveryCost=$deliveryCost ,avalible=0 WHERE id=$updateid";
				$retval = mysqli_query($conn, $sql);
   
				if(! $retval) {
					$updateerror = TRUE;
					$errormessage = $conn->error;
				}

				//CHECK ERROR STATE
				if ($connectionerror == TRUE) {
					//CONNECTION ERROR
					$modalWarn = _ERROR_DB_CONNECT . "<br>" . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8');
				} else if ($updateerror == TRUE) {
					//UPDATE ERROR
					$modalWarn = _ERROR_WHILE_UPDATE_DATA . "<br>" . htmlspecialchars($errormessage, ENT_QUOTES, 'UTF-8');
				} else {
					//NO ERROR FOUND (SUCCESSFULLY)
					$footerecho = "<script>$('#modalSuccess').modal();</script>";
				}
				
				//CLOSE CONNECTION
				$conn->close();
			}
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
			$page = "addsell.php";
		
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
		<title><?= _ADD_SELL ?></title>
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
		<script src='js/main.js'></script>
		<script>
			var enterCompleteInfo = "<?= _ENTER_COMPLETE_INFO ?>";

			//WAITING FOR PAGE TO LOAD COMPLETE
			var intervalLoad = setInterval(function() {
				if (document.readyState === 'complete') {
					clearInterval(intervalLoad);
					
					setTimeout(function(){
						/*document.getElementById('loading').style.display = 'none';*/		
					}, <?= _HIDE_LOADING_DELAY ?>);
					
					clearTimeout(waittimeout);
				}    
			}, 300);
			
			//WAITING FOR PAGE TO LOAD COMPLETE TIMEOUT (10 SECONDS)
			var waittimeout = setTimeout(function(){
				//CHECK IF LOAD SELL LIST COMPLETE
				var buyPrice = document.getElementById("buyPrice").value;
				if (buyPrice != 0 || buyPrice != null) {
					clearInterval(intervalLoad);
					document.getElementById('loading').style.display = 'none';
				}
			}, <?= _HIDE_LOADING_TIMEOUT ?>);
		</script>
	</head>
	<body onload="hidewarn(); reloadPrice()">
		<div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-success navbar-light fixed-top">
                <img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b><?= _DATA_SYSTEMS ?></b></font></span>
            </nav>
            <br/>
            <div class="container-fluid">
                <div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;" id="adminlogin">
                    <div>
                        <center><font size="5"><b><?= _LOGIN_ADD_SELL ?></b></font></center>
                    </div>
					<div style="padding: 8px;"></div>
					<div class="text-center">
						<font size="2">
							<?= _LOGIN_ADD_SELL . ", " . _ENTER_PASSWORD_CORRECTLY ?>
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
				<div class="container-md pr-1 pl-1" id="maintb" style="display:none; margin-top:54px;">
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
														<input type="text" name="buyDate" id="buyDate" class="readonly form-control" value="" readonly="true">
													</div>
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
													<select name="list" id="list" class="selectpicker" data-size="10" data-width="86%" data-live-search="true" onchange="reloadPrice()">
														<?php
															//REVERTIVE DATA
															if ($auth and $authuser != "guest") {
																//AUTH, SET CONNECTION DETAIL
																//START SECTION: SQL LOGIN
																include "./module/sqllogin.php";
																//END SECTION: SQL LOGIN
																
																$conn = new mysqli($servername, $username, $password, $dbname);
							
																if ($conn->connect_error) {
																	$connectionerror = TRUE;
																}
							
																$sqlcom = "SELECT id, list FROM $tb_name WHERE avalible=1 ORDER BY list";
																
																$result = mysqli_query($conn, $sqlcom);
																
																while($row = mysqli_fetch_array($result)) {
																	echo "<option value='" . $row["id"] . "'>" . $row["list"] . " [" . $row["id"] . "]</option>";
																}
																
																$conn->close();
															}
														?>
													</select>
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
													<input type="text" name="category" id="category" class="readonly form-control" value="<?= $category; ?>" readonly="true">
												</div>
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
														<input type="number" name="buyPrice" id="buyPrice" class="readonly form-control" value="<?= $buyPrice; ?>" readonly="true">
													</div>
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
														<input type="number" name="sellPrice" id="sellPrice" class="form-control" value="<?php if(isset($_POST['sellPrice'])) { echo $_POST['sellPrice']; } ?>">
													</div>
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
														<input type="number" name="sellProfit" id="sellProfit" class="readonly form-control" readonly="true">
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
													<select name="sellProvince" id="sellProvince" class="selectpicker" data-size="10" data-width="86%" data-live-search="true" value="<?php if(! isset($_POST['sellProvince'])) { echo 'กรุงเทพมหานคร'; } else { echo $_POST['sellProvince']; } ?>">
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
														<select name="sellSource" id="sellSource" class="form-control" value="<?php if(! isset($_POST['sellSource'])) { echo 'Facebook'; } else { echo $_POST['sellSource']; } ?>">
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
														<input type="number" name="deliveryCost" id="deliveryCost" class="form-control" value="<?= $_POST["deliveryCost"]; ?>">
													</div>
												</center>
											</font>
										</td>
									</tr>
								</tbody>
							</table>
							<input type="hidden" name="token" value="<?= $totoken ?>">
							<input type="hidden" name="updateid" id="updateid" value="-1">
							<div class="pb-3">
								<button class="btn btn-primary" onclick="infoContinue()" id="btn1" type="button"><?= _NEXT ?></button>
								<button class="btn btn-danger" type="submit" id="btn2" form="sellInfo" onclick="showloading()"><?= _SAVE ?></button>
								<button class="btn btn-success" id="btn3" onclick="hidewarn()" type="button"><?= _BACK_TO_EDIT ?></button>
							</div>
						</form>
					</div>
				</div>
				<div class="p-3"></div>
			</center>
		</div>
		<div class="blockall" id="loading" style="display:block;">
			<div class="center">
			<img src="wheel.svg" width="48" height="48" />
			</div>
		</div>
		<?php 
			echo "
			<script>
				SelectElement('category', '" . $_POST["category"] ."');
				SelectElement('sellProvince', '" . $_POST["sellProvince"] ."');
				SelectElement('sellSource', '" . $_POST["sellSource"] ."');
				function SelectElement(id, valueToSelect) { 
					if (valueToSelect != '') {
						var element = document.getElementById(id);
						element.value = valueToSelect;
					}
				}
			</script>";
		?>
		<script>
			var selectValue = document.getElementById('list');
			
			<?php
				if ($auth and $authuser != "guest") {
					echo "function stuck() {
						setTimeout(function(){ 
							if (selectValue.value == '') {
								hideloading();
								document.getElementById('modalWarningContent').innerHTML = '" . _NO_AVALIBLE_PRODUCT_FOUND . "';
								$('#modalWarning').modal();
							}
						}, 2500);
					}";
				}
			?>
			
			function reloadPrice() {
				var auth = "<?php echo str_replace("==", "", base64_encode(sha1($authString))); ?>";
				var authuser = "<?php echo str_replace("==", "", base64_encode(sha1($authuser))); ?>";
				
				if (auth != "OTdjZGJkYzdmZWZmODI3ZWZiMDgyYTZiNmRkMjcyNzIzN2NkNDlmZA" && authuser != "MzU2NzVlNjhmNGI1YWY3Yjk5NWQ5MjA1YWQwZmM0Mzg0MmYxNjQ1MA") {
					showloading();
					console.log(1);
					var id;
					var isStuck = false;
					try {
						id = selectValue.options[selectValue.selectedIndex].value;
					} catch {
						stuck();
						isStuck = true;
						return;
					}
					
					clearTimeout(waittimeout);
					selectValue.blur();
					
					//CHECK IF LIST IS BLANK
					if (id != "" && ! isStuck) {
						var xhr;
						if (window.XMLHttpRequest) { // Mozilla, Safari, ...
								xhr = new XMLHttpRequest();
						} else if (window.ActiveXObject) { // IE 8 and older
							xhr = new ActiveXObject('Microsoft.XMLHTTP');
						}
						var data = "id=" + id + "&token=<?= $totoken ?>";
						xhr.open('POST', 'loadselllist.php', true); 
						xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');                  
						xhr.send(data);
						xhr.onreadystatechange = display_data;
						function display_data() {
							showloading();
							if (xhr.readyState == 4) {
								if (xhr.status == 200) {
									showloading();
									text = xhr.responseText;
									lines = text.split('\n'); 
									for (i = 0; i < lines.length; i++) { 
										if (i == 0) {
											//ID
											document.getElementById('updateid').value = decodeURIComponent(escape(lines[i]));
										} else if (i == 1) {
											//BUY DATE
											document.getElementById('buyDate').value = decodeURIComponent(escape(lines[i]));
											showloading();
										} else if (i == 2) {
											//CATEGORY
											document.getElementById('category').value = decodeURIComponent(escape(lines[i]));
										} else if (i == 3) {
											//BUY PRICE
											document.getElementById('buyPrice').value = decodeURIComponent(escape(lines[i]));
										}
									}
								} else {
									//ERROR
									document.getElementById("modalWarningContent").innerHTML = "<?= _ERROR_WHILE_LOAD_DATA ?><br><br>" + xhr.respontText;
									setTimeout(function(){
										$("#modalWarning").modal();
										hideloading();
									}, 250);
								}
							}
							setTimeout(function(){
								hideloading();			
							}, 250);
						}
					} else {
						hideloading();	
					}
				} else {
					hideloading();	
				}
			}
		</script>
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
		<div class="modal fade" id="modalSuccess" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<font size="5" class="modal-title"><b><?= _NOTIFY ?></b></font>
					</div>
					<div class="modal-body">
						<font size="3"><?= _SUCCESSFULLY_ADD_SELL ?></font>
					</div>
					<div class="modal-footer">
						<button class="btn btn-sm btn-success" onclick="clearInfo()" data-dismiss="modal"><?= _CLOSE_MESSAGE ?></button>
						<button class="btn btn-sm btn-success" onclick="goBackthree()" data-dismiss="modal"><?= _BACK_TO_PREV_PAGE ?></button>
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
			echo "\n<script>hidelogin();</script>";
		}
		echo $footerecho;
	} else {
		//SHOW

		if ($modalWarn != "") {
			echo "\n<script>showlogin(); hideloading(); $('#modalWarning').modal();</script>";
		} else {
			echo "\n<script>showlogin(); hideloading();</script>";
		}
	}
	
	echo "\n<noscript><img id='blockanother' width='100%' height='100%'><table border='1' id='tbjavascript' class='tbinfo'><tr><td class='curve'><div style='padding:28px 28px 28px 28px;'><font size='4'><strong><center>" . _ERROR_ENABLE_JS . "<br></center><img src='/blank.png' height='32' /><br></strong><center><a class='myButton' target='_blank' href='" . _ENABLE_JS_SITE . "'>" . _CONTINUE . "</a></center></font></div></td></tr></table></noscript>";?>