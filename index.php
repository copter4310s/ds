<?php
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
	include "./module/loginnopassword.php";
	//END SECTION: LOGIN
	
	if (! $auth and $authuser == "guest") {
		//SAVE AUTH FAIL
		date_default_timezone_set('Asia/Bangkok');
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$page = "index.php";
		
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
?>
<html>
	<head>
		<title>
			<?= _DATA_SYSTEMS ?>
		</title>
	 	<!-- BOOTSTRAP -->
	 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

		<link rel="stylesheet" href="./main.css" type="text/css" />
		<link rel="shortcut icon" type="img/icon" href="favicon.ico">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-success navbar-light fixed-top">
                <img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b><?= _DATA_SYSTEMS ?></b></font></span>
            </nav>
            <br/>
			<div class="center p-4" style="margin-top: 30px;  border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA;  display: inline-table;">
				<div>
					<img src="favicon.ico" width="40" />
					<font size="3">
						<b><?= _DATA_SYSTEMS ?></b>
					</font>
				</div>
				<div style="padding: 8px;"></div>
				<div class="container-lg">
					<div class="py-2">
						<font size="3">
							<?= _ADD_BUY ?>&nbsp;&nbsp;
						</font>
						<a href="addbuy.php" class="btn btn-sm btn-success float-right"><?= _CONTINUE ?></a>
					</div>
					<div class="py-2">
						<font size="3">
							<?= _ADD_SELL ?>&nbsp;&nbsp;
						</font>
						<a href="addsell.php" class="btn btn-sm btn-success float-right"><?= _CONTINUE ?></a>
					</div>
					<div class="py-2">
						<font size="3">
							<?= _UNDO_SELL ?>&nbsp;&nbsp;
						</font>
						<a href="undosell.php" class="btn btn-sm btn-success float-right"><?= _CONTINUE ?></a>
					</div>
					<div class="py-2"></div>
					<div class="py-2">
						<font size="3">
							<?= _ADMIN_PAGE ?>&nbsp;&nbsp;
						</font>
						<a href="admin.php" class="btn btn-sm btn-success float-right"><?= _CONTINUE ?></a>
					</div>
					<div class="py-2">
						<font size="3">
							<?= _VIEW_ALL_DATA ?>&nbsp;&nbsp;
						</font>
						<a href="view.php" class="btn btn-sm btn-success float-right"><?= _CONTINUE ?></a>
					</div>
					<div class="py-2"></div>
					<div class="py-2">
						<font size="3">
							<?= _CHANGELOG ?>&nbsp;&nbsp;
						</font>
						<a href="changelog.php" class="btn btn-sm btn-success float-right"><?= _CONTINUE ?></a>
					</div>
					<div class="py-2">
						<font size="3">
							<?= _ABOUT_THIS ?>&nbsp;&nbsp;
						</font>
						<a href="aboutthis.php" class="btn btn-sm btn-success float-right"><?= _CONTINUE ?></a>
					</div>
					<div class="py-2"></div>
					<div class="pt-2 text-center">
						<font size="2">
							<a href="old" class="text-primary">ใช้รูปแบบเก่า</a>
						</font>
					</div>
				</div>
			</div>
        </div>
		<?php
			//CHECK IF NOT AUTH
			if (! $auth and $authuser == "guest") {
				echo "<div id='loading'>
						<img id='blockanother' width='100%' height='100%'>
						<div class='center'>
							<img src='wheel.svg' width='72' height='72' />
						</div>
					</div>";
			}
		?>
		<noscript><img id='blockanother' width='100%' height='100%'><table border='1' id='tbjavascript' class='tbinfo'><tr><td class='curve'><div style='padding:28px 28px 28px 28px;'><font size='4'><strong><center><?= _ERROR_ENABLE_JS ?><br></center><img src='/blank.png' height='32' /><br></strong><center><a class='myButton' target='_blank' href='<?= _ENABLE_JS_SITE ?>'><?= _CONTINUE ?></a></center></font></div></td></tr></table></noscript>
	</body>
</html>