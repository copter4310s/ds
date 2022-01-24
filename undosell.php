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

	$errortb = "";
	$reporttb = "";
	$doupdate = $_POST["doupdate"];
	$updateid = (int) $_POST["updateid"];

	//DO UNDO SELL
	if ($auth AND $authuser != "guest") {
		//GET PRODUCTS ID

		//SET VARIABLES
		$updateerror = FALSE;
		//settype($updateid, "integer");
		//CHECK IF ONE BLANK
		if (trim($updateid) == "" or gettype($updateid) != "integer" or $updateid == 0) {
			//BLANK, NOTHING
		} else if ($doupdate == 1) {
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
			$sql = "UPDATE $tb_name SET sellDate='0000-00-00',sellPrice=0,sellProfit=0,sellProvince='',sellSource='',deliveryCost=0,avalible=1 WHERE id=$updateid";
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
		} else {
			//DO NOTHING
		}
	} else {
		//LOGIN ERROR, CHECK IF PASSWORD IS BLANK
		if ($adminpassword == "da39a3ee5e6b4b0d3255bfef95601890afd80709") {
			//BLANK, NOTHING
		} else {
			//SHOW NOT AUTH
			$modalWarn = _ENTER_PASSWORD_CORRECTLY;
			
			//SAVE AUTH FAIL
			date_default_timezone_set('Asia/Bangkok');
			$date = date("Y-m-d");
			$time = date("H:i:s");
			$page = "undosell.php";
		
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
		<title><?= _UNDO_SELL ?></title>
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
			var isshowwarn = 0;
		</script>
	</head>
	<body onload="hidewarnsl(); reloadList()">
		<div class="container-fluid">
			<nav class="navbar navbar-expand-sm bg-success navbar-light fixed-top">
				<img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b><?= _DATA_SYSTEMS ?></b></font></span>
			</nav>
			<br/>
			<div class="container-fluid">
				<div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;" id="adminlogin">
					<div>
						<center><font size="5"><b><?= _LOGIN_UNDO_SELL ?></b></font></center>
					</div>
					<div style="padding: 8px;"></div>
					<div class="text-center">
						<font size="2">
							<?= _LOGIN_UNDO_SELL . ", " . _ENTER_PASSWORD_CORRECTLY ?>
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
							<b><?= _UNDO_SELL ?></b>
						</font>
					</div>
					<div class="container-fluid pt-2">
						<div class="pb-2">
							<font size="3"><?= _SELECT_PRODUCT_TO_UNDO_SELL ?></font>
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
											<font size="3"><center><?= _PRODUCT_NAME ?></center></font>
										</td>
										<td width="65%">
											<font size="3">
												<center>
													<select name="updateid" id="updateid" class="selectpicker" data-size="10" data-width="86%" data-live-search="true"></select>
													<div style="padding-top: 12px;">
														<div class="input-group">
															<span style="margin-top: 8px;"><?= _SPECIFY_PRODUCT_YEAR ?>: &nbsp;</span><input id="selectYear" type="number" class="form-control" style="width: 45%;" value="<?= date("Y") ?>" />
															<div class="input-group-append">
															<button type="button" onclick="reloadList()" class="btn btn-sm btn-primary"><?= _SEARCH ?></button>
															</div>
														</div>
													</div>
												</center>
											</font>
										</td>
									</tr>
								</tbody>
							</table>
							<input type="hidden" name="token" value="<?= $totoken ?>">
							<input type="hidden" name="doupdate" value="1">
							<div class="pb-3">
								<button class="btn btn-primary" onclick="showwarnsl()" id="btn1" type="button"><?= _NEXT ?></button>
								<button class="btn btn-danger" type="submit" id="btn2" form="buyInfo" onclick="showloading()"><?= _SAVE ?></button>
								<button class="btn btn-success" id="btn3" onclick="hidewarnsl()" type="button"><?= _BACK_TO_EDIT ?></button>
							</div>
						</form>
					</div>
				</div>
			</center>
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
							<font size="3"><?= _SUCCESSFULLY_UNDO_SELL ?></font>
						</div>
						<div class="modal-footer">
							<button class="btn btn-sm btn-success" data-dismiss="modal"><?= _CLOSE_MESSAGE ?></button>
							<button class="btn btn-sm btn-success" onclick="goBackthree()" data-dismiss="modal"><?= _BACK_TO_PREV_PAGE ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			//WAITING FOR PAGE TO LOAD COMPLETE
			document.getElementById('loading').style.display = 'block';
			var intervalLoad = setInterval(function() {
				if (document.readyState === 'complete') {
					clearInterval(intervalLoad);
					
					setTimeout(function(){
						hideloading();			
					}, <?= _HIDE_LOADING_DELAY ?>);
					
					clearTimeout(waittimeout);
				}    
			}, 300);
			
			//WAITING FOR PAGE TO LOAD COMPLETE TIMEOUT (10 SECONDS)
			var waittimeout = setTimeout (function(){
				clearInterval(intervalLoad);
				hideloading();
			}, <?= _HIDE_LOADING_TIMEOUT ?>);
		</script>
		<script>
			var selectYear = document.getElementById('selectYear');
			
			<?php
				if ($auth and $authuser != "guest") {
					echo "function stuckcheck() {
						setTimeout(function(){ 
							if (selectYear.value == '') {
								hideloading();
								document.getElementById('modalWarningContent').innerHTML = '" .  _NO_UNDOABLE_PRODUCT_FOUND . "';
							}
						}, 2500);
					}";
				}
			?>
			
			function reloadList() {
				var auth = "<?php echo str_replace("==", "", base64_encode(sha1($authString))); ?>";
				var authuser = "<?php echo str_replace("==", "", base64_encode(sha1($authuser))); ?>";
				
				if (auth != "OTdjZGJkYzdmZWZmODI3ZWZiMDgyYTZiNmRkMjcyNzIzN2NkNDlmZA" && authuser != "MzU2NzVlNjhmNGI1YWY3Yjk5NWQ5MjA1YWQwZmM0Mzg0MmYxNjQ1MA" && isshowwarn == 0) {
					showloading();
					stuckcheck();
					var sellYear = selectYear.value;
					selectYear.blur();
					addReadonly("selectYear");
					
					//CHECK IF LIST IS BLANK
					if (sellYear != "") {
						var xhr;
						if (window.XMLHttpRequest) { // Mozilla, Safari, ...
								xhr = new XMLHttpRequest();
						} else if (window.ActiveXObject) { // IE 8 and older
							xhr = new ActiveXObject('Microsoft.XMLHTTP');
						}
						var data = "sellYear=" + sellYear + "&token=<?= $totoken ?>";
						xhr.open('POST', 'loadundolist.php', true); 
						xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');                  
						xhr.send(data);
						xhr.onreadystatechange = display_data;
						function display_data() {
							showloading();
							if (xhr.readyState == 4) {
								if (xhr.status == 200) {
									showloading();
									text = decodeURIComponent(escape(xhr.responseText));

									//CHECK IF SERVER RESPONE NO DATA
									if (text != "nodata") {
										document.getElementById("updateid").innerHTML = text;
										$('#updateid').selectpicker('refresh');
										text = null;
									} else {
										hideloading();
										document.getElementById('modalWarningContent').innerHTML = "<?= _NO_UNDOABLE_PRODUCT_FOUND ?>";
										$("#modalWarning").modal();
									}
								} else {
									//ERROR
									document.getElementById("modalWarningContent").innerHTML = "<?= _ERROR_WHILE_LOAD_DATA ?><br><br>" + xhr.respontText;
									setTimeout(function(){
										$("#modalWarning").modal();
										removeReadonly("selectYear");		
									}, 250);
								}
							}
							setTimeout(function(){
								hideloading();		
								removeReadonly("selectYear");		
							}, 250);
						}
					} else {
						hideloading();
						removeReadonly("selectYear");	
					}
				} else {

				}
			}

			$(window).on('load', function(){
				$('.modal.fade').appendTo('body');
			})
		</script>
	</body>
</html>
<?php
	//CONTROL LOGIN FORM
	if ($auth AND $authuser != "guest") {
		//HIDE
		echo "\n<script>hidelogin();</script>";

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