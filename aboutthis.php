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
	include "./module/loginnopassword.php";
	//END SECTION: LOGIN
	
	$aboutthis = "<font size='4'>" . _PLEASE_LOGIN . "</font>";

	if ($auth AND $authuser != "guest") {
		$myfile = fopen("./module/aboutthis.txt", "r") or die(_CANNOT_READ_FILE);
		$aboutthis = fread($myfile,filesize("./module/aboutthis.txt"));
		fclose($myfile);
		
		$aboutthis = str_replace("เข้าชมเว็บไซต์", _VISIT_SITE, $aboutthis);
		$aboutthis = trim($aboutthis);
	} else {
		//AUTH FAIL
		$aboutthis = "<font size='4'>" . _PLEASE_LOGIN . "</font>";
		
		//SAVE AUTH FAIL
		date_default_timezone_set('Asia/Bangkok');
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$page = "aboutthis.php";
		
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
		<title><?= _ABOUT_THIS ?></title>
		<!-- BOOTSTRAP -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

		<link rel="stylesheet" href="./main.css" type="text/css" />
		<link rel="shortcut icon" type="img/icon" href="favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="js/main.js"></script>
	</head>
	<body>
		<div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-success navbar-light fixed-top">
                <img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b><?= _DATA_SYSTEMS ?></b></font></span>
            </nav>
            <br/>
			<div class="container-fluid" style="margin-top:54px;">
				<div class="pt-2">
					<font size="5">
						<b><?= _ABOUT_THIS ?></b>
					</font>
				</div>
				<hr>
				<div class="container-bg">
					<font size="3">
						<?= $aboutthis; ?>
					</font>
				</div>
			</div>
		</div>
		<noscript><img id='blockanother' width='100%' height='100%'><table border='1' id='tbjavascript' class='tbinfo'><tr><td class='curve'><div style='padding:28px 28px 28px 28px;'><font size='4'><strong><center><?=_ERROR_ENABLE_JS ?><br></center><img src='/blank.png' height='32' /><br></strong><center><a class='myButton' target='_blank' href='<?= _ENABLE_JS_SITE ?>'><?= _CONTINUE ?></a></center></font></div></td></tr></table></noscript>
	</body>
</html>