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

	$abuyDate = date("d-m-Y", strtotime($_POST["buyDate"]));
	$inputbuyDate = date("Y-m-d", strtotime($abuyDate));
	
	if (strpos($inputbuyDate, "1970") !== FALSE) {
		$inputbuyDate = date('Y-m-d', time());
	}
?>

<html>
	<head>
		<title><?= _ADD_BUY ?></title>
		<!-- BOOTSTRAP -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

		<link rel='stylesheet' href='./main.css' type='text/css' />
		<link rel='shortcut icon' type='img/icon' href='favicon.ico'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
		<script src='js/pzDS_add.js'></script>
		<script src='./js/main.js'></script>
		<script>
			var enterCompleteInfo = "<?= _ENTER_COMPLETE_INFO ?>";

			//WAITING FOR PAGE TO LOAD COMPLETE
			var intervalLoad = setInterval(function() {
				if (document.readyState === 'complete') {
					clearInterval(intervalLoad);
					
					setTimeout(function(){
						document.getElementById('loading').style.display = 'none';			
					}, <?= _HIDE_LOADING_DELAY ?>);
					
					clearTimeout(waittimeout);
				}    
			}, 300);
			
			//WAITING FOR PAGE TO LOAD COMPLETE TIMEOUT (10 SECONDS)
			var waittimeout = setTimeout(function(){
				clearInterval(intervalLoad);
				document.getElementById('loading').style.display = 'none';			
			}, <?= _HIDE_LOADING_TIMEOUT ?>);
		</script>
	</head>
	<body onload="hidewarns()">
		<div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-success navbar-light fixed-top">
                <img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b><?= _DATA_SYSTEMS ?></b></font></span>
            </nav>
            <br/>
            <div class="container-fluid">
                <div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;" id="adminlogin">
                    <div>
                        <center><font size="5"><b><?= _LOGIN_ADD_BUY ?></b></font></center>
                    </div>
					<div style="padding: 8px;"></div>
					<div class="text-center">
						<font size="2">
							<?= _LOGIN_ADD_BUY . ", " . _ENTER_PASSWORD_CORRECTLY ?>
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
				<div class="container-md" id="maintb" style="display:none; margin-top:54px;">
					<div>
						<font size="5">
							<b><?= _ADD_BUY ?></b>
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
														<input type="date" name="buyDate" id="buyDate" class="form-control" value="<?= $inputbuyDate ?>">
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
											<font size="3">
												<center>
													<div class="input-group" style="width: 86%;">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="fas fa-file-signature"></i></span>
														</div>
														<input type="text" name="list" id="list" class="form-control" value="<?= $_POST['list'] ?>" maxlength="200">
													</div>
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
													<select name="category" id="category" class="form-control" value="<?= $_POST['category'] ?>">
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
														<input type="number" name="buyPrice" id="buyPrice" class="form-control" value="<?= $_POST['buyPrice'] ?>">
													</div>
												</center>
											</font>
										</td>
									</tr>
								</tbody>
							</table>
							<input type="hidden" name="token" value="<?= $totoken ?>">
							<div class="pb-3">
								<button class="btn btn-primary" onclick="infoContinues()" id="btn1" type="button"><?= _NEXT ?></button>
								<button class="btn btn-danger" type="submit" id="btn2" form="buyInfo" onclick="showloading()"><?= _SAVE ?></button>
								<button class="btn btn-success" id="btn3" onclick="hidewarns()" type="button"><?= _BACK_TO_EDIT ?></button>
							</div>
						</form>
					</div>
				</div>
			</center>
		</div>
		<?php 
			echo "<script>
			SelectElement('category', '" . $_POST["category"] . "');
			function SelectElement(id, valueToSelect) {    
				if (valueToSelect != '') {
					var element = document.getElementById(id);
					element.value = valueToSelect;
				}
			}
			</script>";
		?>
		<div class="blockall" id="loading" style="display:none;">
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
						<font size="3"><span id="modalWarningContent"></span></font>
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
						<font size="3"><?= _SUCCESSFULLY_ADD_BUY ?></font>
					</div>
					<div class="modal-footer">
						<button class="btn btn-sm btn-success" onclick="clearInfos()" data-dismiss="modal"><?= _CLOSE_MESSAGE ?></button>
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
		echo "<script>hidelogin(); showloading();</script>";
	} else {
		//SHOW
		echo "<script>showlogin();</script>";
	}

	if ($auth AND $authuser != "guest") {
		//GET PRODUCTS INFORMATION
		$buyDat = $_POST['buyDate'];
		$list = $_POST['list'];
		$category = $_POST['category'];
		$buyPrice = $_POST['buyPrice'];
	
		$buyDate = date("d-m-Y", strtotime($buyDat));
		$buyDate = date("Y-m-d", strtotime($buyDate));
		settype($buyPrice, "integer");

		//INCLUDE SYMBOL CHECK MODULE
		include "./module/symbolcheck.php";

		//CHECK IF ONE BLANK
		if (trim($buyDat) == "" or trim($list) == "" or trim($category) == "" or trim($buyPrice) == "") {
			//BLANK, NOTHING
		//CHECK LIST
		} else if (strlen($list) > 200) {
			//LIST LENGTH MORE THAN 200
			echo '<script>
				document.getElementById("modalWarningContent").innerHTML = "' . _PRODUCT_NAME . ' ' . _CANNOT_LONGER_THAN . ' 200 ' . _CHARACTER . '";
				$("#modalWarning").modal();
			</script>';
		} else if (symbolcheck($list) != "ok") {
			//SYMBOL ERROR
			echo "<script>
				document.getElementById('modalWarningContent').innerHTML = '" . _CHANGE_SYMBOL . symbolcheck($list) . ' ' . _IN_PRODUCT_NAME . "';
				$('#modalWarning').modal();
			</script>";
		//CHECK IF BUY PRICE IS INTEGER
		} else if (gettype($buyPrice) != "integer") {
			echo '<script>
				document.getElementById("modalWarningContent").innerHTML = "' . _ERROR_ILLEGAL_BUY_PRICE . '";
				$("#modalWarning").modal();
			</script>';
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
			$sql = "INSERT INTO $tb_name(buyDate, list, category, buyPrice, avalible) VALUES ('" . mysqli_real_escape_string( $conn, $buyDate ) . "', '" . mysqli_real_escape_string( $conn, $list ) . "', '" . mysqli_real_escape_string( $conn, $category ) . "', $buyPrice, 1)";
			$retval = mysqli_query($conn, $sql);
   
			if(! $retval) {
				$inserterror = TRUE;
				$errormessage = $conn->error;
			}

			//CHECK ERROR STATE
			if ($connectionerror == TRUE) {
				//CONNECTION ERROR
				echo "<script>
					document.getElementById('modalWarningContent').innerHTML = '" . _ERROR_DB_CONNECT . "<br>" . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8') . "';
					$('#modalWarning').modal();
				</script>";
			} else if ($inserterror == TRUE) {
				echo "<script>
					document.getElementById('modalWarningContent').innerHTML = '" . _ERROR_WHILE_ADD_DATA . "<br>" . htmlspecialchars($errormessage, ENT_QUOTES, 'UTF-8') . "';
					$('#modalWarning').modal();
				</script>";
			} else {
				//NO ERROR FOUND (SUCESSFULLY)
				echo "<script>
					$('#modalSuccess').modal();
				</script>";
			}
			
			//CLOSE CONNECTION
			$conn->close();
		}
	} else {
		//LOGIN ERROR, CHECK IF ADMINPASSWORD IS BLANK
		if ($adminpassword == "da39a3ee5e6b4b0d3255bfef95601890afd80709") {
			//BLANK, NOTHING
		} else {
			//SHOW NOT AUTH
			echo '<script>
				document.getElementById("modalWarningContent").innerHTML = "' . _ENTER_PASSWORD_CORRECTLY . '";
				$("#modalWarning").modal();
			</script>';
			
			//SAVE AUTH FAIL
			date_default_timezone_set('Asia/Bangkok');
			$date = date("Y-m-d");
			$time = date("H:i:s");
			$page = "addbuy.php";
			
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
	
	echo "<noscript><img id='blockanother' width='100%' height='100%'><table border='1' id='tbjavascript' class='tbinfo'><tr><td class='curve'><div style='padding:28px 28px 28px 28px;'><font size='4'><strong><center>" . _ERROR_ENABLE_JS . "<br></center><img src='/blank.png' height='32' /><br></strong><center><a class='myButton' target='_blank' href='" . _ENABLE_JS_SITE . "'>" . _CONTINUE . "</a></center></font></div></td></tr></table></noscript>";
?>