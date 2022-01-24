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

	$reidContinue = $_POST["reidContinue"];
	$reassignid = $_POST["reassignid"];
?>

<html>
	<head>
		<title><?= _REASSIGN_ID ?></title>
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
                <div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;" id="reassignask">
                    <div>
                        <center><font size="5"><b><?= _REASSIGN_ID ?></b></font></center>
                    </div>
					<div class="pt-3"></div>
                    <div class="container-lg">
                        <form style="margin-block-end: 0;" method="POST">
                            <div class="text-center">
								<font size="3"><?= _REASSIGN_ID_ASK ?> </font>&nbsp;<button type="button" class="btn btn-sm btn-primary" onclick="showmodalInfo()">?</button>
                            </div>
							<div class="pt-3"></div>
                            <div>
                                <center>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="showloading()" name="reidContinue" value="f0fe66f11addf48021039146e00f344e"><?=_REASSIGN_ID ?></button>
									<button type="button" class="btn btn-sm btn-success" onclick="javascript: close_window();"><?= _CANCEL ?></button>
                                </center>
                            </div>
							<input type="hidden" name="id" value="<?= $delID ?>">
							<input type="hidden" name="token" value="<?= $_POST["token"] ?>">
                        </form>
                    </div>
                </div>
				<div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;" id="reassignprogress">
                    <div>
                        <center><font size="5"><b><?= _REASSIGN_ID ?></b></font></center>
                    </div>
					<div class="pt-3"></div>
                    <div class="container-lg">
                        <form style="margin-block-end: 0;" method="POST">
                            <div class="text-center">
								<font size="3">
									<span id="reassignprogressContent"></span>
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
						<font size="3"><span id="modalWarningContent"><?= _REASSIGN_ID_EXPLAIN ?></span></font>
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
		$connectionerror = FALSE;
		$reiderror = FALSE;

		//ASK FOR RE-ASSIGN
		if ($reidContinue != "f0fe66f11addf48021039146e00f344e") {
			//ASK
			echo "<script>document.getElementById('reassignask').style.display = 'inline-table'; document.getElementById('reassignprogress').style.display = 'none';</script>";
		} else {
			//CONTINUE RE-ASSIGN, SET CONNECTION DETAIL
            //START SECTION: SQL LOGIN
			include "./module/sqllogin.php";
			//END SECTION: SQL LOGIN

			$connectionerror = FALSE;
			$reiderror = FALSE;
			$residerror = FALSE;
			$residmessage = "";

			//CREATE AND CHECK CONNECTION
			$conn = new mysqli($servername, $username, $password, $dbname);

			if ($conn->connect_error) {
				$connectionerror = TRUE;
			}
			
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

			//CLOSE CONNECTION
			$conn->close();

			//CHECK ERROR STATE
			if ($connectionerror == TRUE) {
				//CONNECTION ERROR
				echo "<script>
					document.getElementById('reassignprogressContent').innerHTML = '" . _ERROR_DB_CONNECT . "<br>" . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8') ."';
					document.getElementById('reassignask').style.display = 'none';
					document.getElementById('reassignprogress').style.display = 'inline-table';
				</script>";
			} else if ($residerror == TRUE) {
				//RE-ASSIGN ID ERROR
				echo "<script>
					document.getElementById('reassignprogressContent').innerHTML = '" . _ERROR_WHILE_REASSIGN_ID . "<br>" . htmlspecialchars($residmessage, ENT_QUOTES, 'UTF-8') ."';
					document.getElementById('reassignask').style.display = 'none';
					document.getElementById('reassignprogress').style.display = 'inline-table';
				</script>";
			} else {
				//NO ERROR FOUND (SUCESSFULLY)
				echo "<script>
					document.getElementById('reassignprogressContent').innerHTML = '" . _SUCCESSFULLY_RESSIGN_ID  . "';
					document.getElementById('reassignask').style.display = 'none';
					document.getElementById('reassignprogress').style.display = 'inline-table';
				</script>";
			}
		}
	} else {
		//NOT FAIL BUT ?
		echo "<script>
			document.getElementById('reassignprogressContent').innerHTML = '" . _ENTER_BY_VIEW_ALL_DATA_PAGE  . "';
			document.getElementById('reassignask').style.display = 'none';
			document.getElementById('reassignprogress').style.display = 'inline-table';
		</script>";
		
		//SAVE AUTH FAIL
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$page = "reassignid.php";
		
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