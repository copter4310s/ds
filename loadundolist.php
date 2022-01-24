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

	//START SECTION: CONFIG
	include "./module/config.php";
	//END SECTION: CONFIG
	
	$id = $_POST["id"];
	$sellYear = $_POST["sellYear"];
	
	//DELAY
	usleep(_LOAD_UNDO_LIST_DELAY);
	
	settype($id, "integer");
	settype($sellYear, "integer");
	
	if ($auth and $authuser != "guest") {
		//CONTINUE, SET CONNECTION DETAIL
		//START SECTION: SQL LOGIN
		include "./module/sqllogin.php";
		//END SECTION: SQL LOGIN

		//CREATE AND CHECK CONNECTION
		$conn = new mysqli($servername, $username, $password, $dbname);
										
		if ($conn->connect_error) {
			$connectionerror = TRUE;
		}
										
		$sqlcom = "SELECT id, list FROM $tb_name WHERE avalible=0 AND Year(sellDate) = $sellYear ORDER BY list";
		
		$result = mysqli_query($conn, $sqlcom);
		$returnlist = "";
		
		while($row = mysqli_fetch_array($result)) {
			$returnlist .= "<option value=\"" . $row["id"] . "\">" . $row["list"] . " [" . $row["id"] . "]</option>\n";
		}

		//CHECK IF NO DATA
		if (trim($returnlist) == "") {
			$returnlist = "nodata";
		}

		$conn->close();
		
		//ENCODE AS UTF8 AND RETURN DATA
		echo utf8_encode( $returnlist );
	} else {
		//NOT AUTH
		echo utf8_encode( "<option value=\"-1\">" . _PLEASE_LOGIN . " [-1]</option>\n" );
	}
?>