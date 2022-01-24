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

	//GET CLIENT IP
    $clientIP = "";
	if (getenv('HTTP_CLIENT_IP')) {
		$clientIP = getenv('HTTP_CLIENT_IP');
	} else if(getenv('HTTP_X_FORWARDED_FOR')) {
		$clientIP = getenv('HTTP_X_FORWARDED_FOR');
	} else if(getenv('HTTP_X_FORWARDED')) {
		$clientIP = getenv('HTTP_X_FORWARDED');
	} else if(getenv('HTTP_FORWARDED_FOR')) {
		$clientIP = getenv('HTTP_FORWARDED_FOR');
	} else if(getenv('HTTP_FORWARDED')) {
		$clientIP = getenv('HTTP_FORWARDED');
	} else if(getenv('REMOTE_ADDR')) {
		$clientIP = getenv('REMOTE_ADDR');
	} else {
		$clientIP = 'UNKNOWN';
	}
?>

<html>
	<head>
		<title><?= _TEST ?></title>
		<link rel="stylesheet" href="./main.css" type="text/css" />
		<link rel="shortcut icon" type="img/icon" href="favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=0.8">
		<script src="js/pzDS_add.js"></script>
		<script src="js/pzDS_admin.js"></script>
		<script src="js/main.js"></script>
		<script>
			function showMsgBox() {
				var msgbox = document.getElementById("msgbox");
				var blockanother1 = document.getElementById("blockanother1");
				var tbinfo1 = document.getElementById("tbinfo1");

				msgbox.style.display = "block";
				blockanother1.style.display = "block";
				tbinfo1.style.display = "inline-table";
			}
		</script>
	</head>
	<body>
		<center>
			<table border="1" id="tbinfo">
				<tr>
					<th class="curvetop" bgcolor="whitesmoke" width="304">
						<div class="paddinglesswithtop">
							<a class="redButton" onclick="window.location.reload(); hidetbinfo(1)" style="margin-left: 5px; float: right;">X</a>
							<center>
							<div style="transform: translateX(0px); width:50%;">
							<font size="5">
							<?= _TEST ?>
							</font>
							</div>
							</center>
							</div>
							</center>
							</div>
							</th>
							</tr>
							<tr>
							<td class="curvebottom">
							<div style="padding:28px 28px 14px 28px;"><font size="4">
							<center>IP : <?= $clientIP ?><br></center><img src="/blank.png" height="8" /><br>
							<center>OS : <?= $_SERVER['HTTP_USER_AGENT'] ?><br></center><img src="/blank.png" height="8" /><br>
							</div>
							<hr>
							<div style="padding:14px 28px 28px 28px;"><font size="4">
							<center>Message box: <button onclick="showMsgBox()" class="myButton"><?= _SHOW ?></button><br></center><img src="/blank.png" height="32" /><br>
							<center><button class="myButton" onclick="window.location.reload(); hidetbinfo(1)"><?= _REFRESH ?></button></center>
							</div>
						</font>
					</td>
				</tr>
			</table>
			<div id="msgbox" style="display: none;">
				<img id='blockanother1' class='blockanother' width='100%' height='100%'>
				<table border='1' id='tbinfo1' class='tbinfo'>
				<tr>
				<td class='curve'>
				<div style='padding:28px 28px 28px 28px;'><font size='4'><strong>
				<center><?= _DATA_SYSTEMS ?><br></center><img src='/blank.png' height='32' /><br>
				<center><button class='myButton' onclick='hidetbinfo(1)'><?= _CLOSE_MESSAGE ?></button></center>
				</strong></font></div>
				</td>
				</tr>
				</table>
			</div>
		</center>
		<noscript><img id='blockanother' width='100%' height='100%'><table border='1' id='tbjavascript' class='tbinfo'><tr><td class='curve'><div style='padding:28px 28px 28px 28px;'><font size='4'><strong><center><?=_ERROR_ENABLE_JS ?><br></center><img src='/blank.png' height='32' /><br></strong><center><a class='myButton' target='_blank' href='<?= _ENABLE_JS_SITE ?>'><?= _CONTINUE ?></a></center></font></div></td></tr></table></noscript>
	</body>
</html>