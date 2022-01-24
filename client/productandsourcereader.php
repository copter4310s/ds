<?php
	date_default_timezone_set('Asia/Bangkok');
	error_reporting(0);

	//START SECTION: LOGIN
	$donotechologin = TRUE;
	include "./login.php";
	//END SECTION: LOGIN

	include "../module/config.php";
	$outputproduct = "";
	$outputsource = "";

	if ($auth and $authuser != "guest") {
		//CONTINUE, SET CONNECTION DETAIL
		//START SECTION: SQL LOGIN
		include "./sqllogin.php";
		//END SECTION: SQL LOGIN

		//CREATE AND CHECK CONNECTION
		$conn = new mysqli($servername, $username, $password, $dbname);
										
		if ($conn->connect_error) {
			$connectionerror = TRUE;
		}

		$sqlcom = "SELECT id, list FROM $tb_name WHERE avalible=1 ORDER BY list";
		
		$result = mysqli_query($conn, $sqlcom);
		$isfirst = TRUE;
		while($row = mysqli_fetch_array($result)) {
			if ($isfirst) {
				$outputproduct = $row["list"] . " [" . $row["id"] . "]";
				$isfirst = FALSE;
			} else {
				$outputproduct .= "('/')" . $row["list"] . " [" . $row["id"] . "]";
			}
		}													

		$conn->close();

		$sourcelistpath = "../module/sourcelist.txt";
		$sourcelistcount = $lines = count(file($sourcelistpath));
		$sourceechocount = 0;
		
		$fns = fopen($sourcelistpath,"r");
		while(! feof($fns))  {
			$sourceechocount += 1;
			$results = fgets($fns);
			$results = str_replace("\n", "", $results);
			
			if ($outputsource == "") {
				$outputsource = $results;
			} else {
				$outputsource .= "('/')" . $results;
			}
			
		}
		fclose($fns);
		
		//RETURN DATA
		echo $outputproduct;
		echo "\n" . $outputsource;
	}
?>