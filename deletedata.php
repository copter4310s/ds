<?php
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

	//GET DELETE ID
	$delID = $_POST["id"];
	$delContinue = $_POST["delContinue"];
	$reassignid = $_POST["reassignid"];
?>

<html>
	<head>
		<title><?= _DELETE_DATA ?></title>
		<!-- BOOTSTRAP -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

		<link rel="stylesheet" href="./main.css" type="text/css" />
		<link rel="shortcut icon" type="img/icon" href="favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<script src='js/pzDS_add.js'></script>
		<script src='js/pzDS_admin.js'></script>
		<script src='js/main.js'></script>
		<script>
			function showmodalInfo() {
				$('#modalInfo').modal();
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
                <div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;" id="deleteask">
                    <div>
                        <center><font size="5"><b><?= _DELETE_DATA ?></b></font></center>
                    </div>
					<div class="pt-3"></div>
                    <div class="container-lg">
                        <form style="margin-block-end: 0;" method="POST">
                            <div class="text-center">
								<font size="3"><?= _DELETE_DATA_ASK ?> (ID: <?= number_format($delID) ?>)</font>
                            </div>
							<div class="pt-3">
								<center>
								<input type="checkbox" class="form-check-input" name="reassignid" id="reassignid" value="1"> <label class="form-check-label" for="reassignid"><?= _REASSIGN_ID ?></label>&nbsp;
								<button type="button" class="btn btn-sm btn-primary" onclick="showmodalInfo()">?</button>
							</div>
							<div class="pt-3"></div>
                            <div>
                                <center>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="showloading()" name="delContinue" value="f0fe66f11addf48021039146e00f344e"><?=_DELETE ?></button>
									<button type="button" class="btn btn-sm btn-success" onclick="javascript: close_window();"><?= _CANCEL ?></button>
                                </center>
                            </div>
							<input type="hidden" name="id" value="<?= $delID ?>">
							<input type="hidden" name="token" value="<?= $_POST["token"] ?>">
                        </form>
                    </div>
                </div>
				<div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;" id="deleteprogress">
                    <div>
                        <center><font size="5"><b><?= _DELETE_DATA ?></b></font></center>
                    </div>
					<div class="pt-3"></div>
                    <div class="container-lg">
                        <form style="margin-block-end: 0;" method="POST">
                            <div class="text-center">
								<font size="3">
									<span id="deleteprogressContent"></span>
								</font>
                            </div>
							<div class="pt-3"></div>
                            <div>
                                <center>
									<button type="button" class="btn btn-sm btn-success" onclick="javascript: close_window();"><?= _CLOSE_PAGE ?></button>
                                </center>
                            </div>
							<input type="hidden" name="id" value="<?= $delID ?>">
							<input type="hidden" name="token" value="<?= $_POST["token"] ?>">
                        </form>
                    </div>
                </div>
            </div>
		</div>
		<div class="modal fade" id="modalInfo">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<font size="5" class="modal-title"><b><?= _NOTIFY ?></b></font>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<font size="3"><span id="modalInfoContent"><?= _REASSIGN_ID_EXPLAIN_IN_DELETE_DATA_PAGE ?></span></font>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-success" data-dismiss="modal"><?= _CLOSE_MESSAGE ?></button>
					</div>
				</div>
			</div>
		</div>
		<div class="blockall" id="loading" style="display:none;">
			<div class="center">
			<img src="wheel.svg" width="48" height="48" />
			</div>
		</div>
	</body>
</html>

<?php 
	date_default_timezone_set('Asia/Bangkok');
	error_reporting(0);

	if ($auth and $authuser != "guest") {
		//CHECK IF DELID IS NULL
		if ($delID != NULL) {
			$oldinfo = 0;
			$olderror = FALSE;
			$nooldinfo = FALSE;
		
			//CONTINUE INSERT, SET CONNECTION DETAIL
            //START SECTION: SQL LOGIN
			include "./module/sqllogin.php";
			//END SECTION: SQL LOGIN
			
			$connectionerror = FALSE;
			$delerror = FALSE;

			//CREATE AND CHECK CONNECTION
			$conn = new mysqli($servername, $username, $password, $dbname);

			if ($conn->connect_error) {
				$connectionerror = TRUE;	
			}
		
			//CHECK OLD INFORMATION
			$sqloldinfo = "SELECT id FROM $tb_name WHERE id=$delID";
			if ($res = mysqli_query($conn, $sqloldinfo)) { 
				$oldinfo = mysqli_num_rows($res);
			} else { 
				//ERROR
				$olderror = TRUE;
			}
			
			//CHECK IF HAVE OLD INFORMATION
			if ($oldinfo != 0 and ! $olderror) {
				//ASK FOR DELETION
				if ($delContinue != "f0fe66f11addf48021039146e00f344e") {
					//ASK
					echo "<script>document.getElementById('deleteask').style.display = 'inline-table'; document.getElementById('deleteprogress').style.display = 'none';</script>";
				} else {
					//CONTINUE DELETING, SET CONNECTION DETAIL
					//START SECTION: SQL LOGIN
					include "./module/sqllogin.php";
					//END SECTION: SQL LOGIN
			
					$connectionerror = FALSE;
					$delerror = FALSE;
					$residerror = FALSE;
					$errormessage = "";
					$residmessage = "";

					//CREATE AND CHECK CONNECTION
					$conn = new mysqli($servername, $username, $password, $dbname);

					if ($conn->connect_error) {
						$connectionerror = TRUE;
					}
					
					//DELETE DATA
					$sqldel = "DELETE FROM $tb_name WHERE id=$delID";
					if ($res = mysqli_query($conn, $sqldel)) { 
						//DELETED
					} else { 
						//ERROR
						$delerror = TRUE;
						$errormessage = $conn->error;
					}
					
					//CHECK IF NEED TO RE-ASSIGN ID
					if ($reassignid == 1) {
						//RE-ASSIGN ID
						$sqldropid = "ALTER TABLE $tb_name DROP id";
						$sqlresid = "ALTER TABLE $tb_name ADD id INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (id), AUTO_INCREMENT=1";
						if ($sqlresdropid = mysqli_query($conn, $sqldropid)) { 
							//DROPED
						} else { 
							//ERROR
							$residerror = TRUE;
							$residmessage = $conn->error;
						}
						
						if ($sqlresresid = mysqli_query($conn, $sqlresid)) { 
							//RE-ASSIGNED
						} else { 
							//ERROR
							$residerror = TRUE;
							$residmessage = $conn->error;
						}
					}

					//CLOSE CONNECTION
					$conn->close();

					//CHECK ERROR STATE
					if ($connectionerror == TRUE) {
						//CONNECTION ERROR
						echo "<script>
							document.getElementById('deleteprogressContent').innerHTML = '" . _ERROR_DB_CONNECT . "<br>" . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8') ."';
							document.getElementById('deleteask').style.display = 'none';
							document.getElementById('deleteprogress').style.display = 'inline-table';
						</script>";
					} else if ($delerror == TRUE) {
						//DELETE ERROR
						echo "<script>
							document.getElementById('deleteprogressContent').innerHTML = '" . _ERROR_WHILE_DELETE_DATA . "<br>" . htmlspecialchars($errormessage, ENT_QUOTES, 'UTF-8') ."';
							document.getElementById('deleteask').style.display = 'none';
							document.getElementById('deleteprogress').style.display = 'inline-table';
						</script>";
					} else if ($reassignid == 1) {
						if ($residerror == TRUE) {
							//DELETE DATA SUCCESSFULLY BUT RE-ASSIGN ID ERROR
							echo "<script>
								document.getElementById('deleteprogressContent').innerHTML = '" . _SUCCESSFULLY_DELETE_DATA_BUT_REASSIGN_ID_ERROR . "<br>" . htmlspecialchars($residmessage, ENT_QUOTES, 'UTF-8') ."';
								document.getElementById('deleteask').style.display = 'none';
								document.getElementById('deleteprogress').style.display = 'inline-table';
							</script>";
						} else {
							//NO ERROR FOUND (SUCESSFULLY)
							echo "<script>
								document.getElementById('deleteprogressContent').innerHTML = '" . _SUCCESSFULLY_DELETE_DATA_AND_REASSIGN_ID  . "';
								document.getElementById('deleteask').style.display = 'none';
								document.getElementById('deleteprogress').style.display = 'inline-table';
							</script>";
						}
					} else {
						//NO ERROR FOUND (SUCESSFULLY)
						echo "<script>
							document.getElementById('deleteprogressContent').innerHTML = '" . _SUCCESSFULLY_DELETE_DATA  . "';
							document.getElementById('deleteask').style.display = 'none';
							document.getElementById('deleteprogress').style.display = 'inline-table';
						</script>";
					}
				}
			} else {
				//NULL
				if ($delID == NULL) {
					echo "<script>
						document.getElementById('deleteprogressContent').innerHTML = '" . _ENTER_BY_VIEW_ALL_DATA_PAGE  . "';
						document.getElementById('deleteask').style.display = 'none';
						document.getElementById('deleteprogress').style.display = 'inline-table';
					</script>";
				} else {
					//NO OLD INFORMATION
					echo "<script>
						document.getElementById('deleteprogressContent').innerHTML = '" . _ERROR_ID_NOT_FOUND . " " . number_format($delID) . "';
						document.getElementById('deleteask').style.display = 'none';
						document.getElementById('deleteprogress').style.display = 'inline-table';
					</script>";
				}
			}
		} else {
			//NO OLD INFORMATION FOUND
			echo "<script>
				document.getElementById('deleteprogressContent').innerHTML = '" . _ERROR_ID_NOT_FOUND . " " . number_format($delID) . "';
				document.getElementById('deleteask').style.display = 'none';
				document.getElementById('deleteprogress').style.display = 'inline-table';
			</script>";
		}
		
	} else {
		//NOT FAIL BUT ?
		echo "<script>
				document.getElementById('deleteprogressContent').innerHTML = '" . _ENTER_BY_VIEW_ALL_DATA_PAGE ."';
				document.getElementById('deleteask').style.display = 'none';
				document.getElementById('deleteprogress').style.display = 'inline-table';
			</script>";
		
		//SAVE AUTH FAIL
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$page = "deletedata.php?id=$delID";
		
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
	
	echo "<noscript><img id='blockanother' width='100%' height='100%'><table border='1' id='tbjavascript' class='tbinfo'><tr><td class='curve'><div style='padding:28px 28px 28px 28px;'><font size='4'><strong><center>" . _ERROR_ENABLE_JS . "<br></center><img src='/blank.png' height='32' /><br></strong><center><a class='myButton' target='_blank' href='" . _ENABLE_JS_SITE . "'>" . _CONTINUE . "</a></center></font></div></td></tr></table></noscript>";
?>