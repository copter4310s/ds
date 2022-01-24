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
	
	usleep(_LOAD_SELL_LIST_DELAY);
	
	settype($id, "integer");
	
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
										
		$sqlcom = "SELECT id, buyDate, category, buyPrice FROM $tb_name WHERE id='$id'";
		
		$result = mysqli_query($conn, $sqlcom);
		$returnid = 0;
		$returnbuyDate = "";
		$returncategory = "";
		$returnbuyPrice = 0;
		
		while($row = mysqli_fetch_array($result)) {
			$returnid = $row["id"];
			$returnbuyDate = $row["buyDate"];
			$returncategory = $row["category"];
			$returnbuyPrice = $row["buyPrice"];
		}

		$conn->close();

		$returnbuyDate = date("d-m-Y", strtotime($returnbuyDate));
		
		//ENCODE AS UTF8 AND RETURN DATA
		echo utf8_encode( $returnid );
		echo "\n" . utf8_encode( $returnbuyDate );
		echo "\n" . utf8_encode( $returncategory );
		echo "\n" . utf8_encode( $returnbuyPrice );
	} else {
		//NOT AUTH
		echo utf8_encode( "-1" );
		echo "\n" . utf8_encode( "1970-01-01" );
		echo "\n" . utf8_encode( _PLEASE_LOGIN );
		echo "\n" . utf8_encode( "-1" );
		echo "\n" . utf8_encode( _PLEASE_LOGIN );
	}
?>