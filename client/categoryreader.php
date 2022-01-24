<?php
	date_default_timezone_set('Asia/Bangkok');
	error_reporting(0);

	//START SECTION: LOGIN
	$donotechologin = TRUE;
	include "./login.php";
	//END SECTION: LOGIN

	if ($auth and $authuser != "guest") {
		$categorylistpath = "../module/categorylist.txt";
		$categorylist = "";
		
		$fn = fopen($categorylistpath,"r") or die("Error: Can't read category file!");
		while(! feof($fn))  {
			$result = fgets($fn);
			$result = str_replace("\n", "", $result);
			if ($categorylist == "") {
				$categorylist = $result;
			} else {
				$categorylist .= "(/)" . $result;
			}
		}
		fclose($fn);

		echo $categorylist;
	} else {

	}
?>