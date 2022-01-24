<?php
    //THIS IS A LOGIN SECTION OF ALL PAGE
    //YOU CAN CUSTOM THIS FILE

    //GET ADMINPASSWORD AND TOKEN
	$adminpassword = sha1($_POST["adminpassword"]);
	$token = base64_decode($_POST["token"]);
	$totoken = "";

	//SET VARIABLES
	$auth = FALSE;
	$authString = "False";
	$authuser = "guest";
	
	//READ loginpassword.txt AND SET
	$loginpassword = "";

	$passwordFile = fopen("../module/loginpassword.txt", "r") or die("Error: Can't read password file!");
	$loginpassword = fread($passwordFile,filesize("../module/loginpassword.txt"));
	fclose($passwordFile);
	
	$loginpassword = base64_decode($loginpassword);
	
	if (! isset($donotechologin)) {
		$donotechologin = FALSE;
	}

    //AUTH SYSTEMS, PASSWORD
    if ($adminpassword == $loginpassword) {
        $auth = TRUE;
        $authString = "True";
    } else if ($token == $loginpassword) {
        $auth = TRUE;
        $authString = "True";
    } else {
        $auth = FALSE;
        $authString = "False";
    }

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

	//AUTH SYSTEMS, IP
	if (strpos($clientIP, "184.22.") !== FALSE) {
		//AUTH, AIS
		$authuser = "AIS";
	} else if (strpos($clientIP, "182.232") !== FALSE) {
		//AUTH, AIS
		$authuser = "AIS";
	} else if (strpos($clientIP, "49.229") !== FALSE) {
		//AUTH, AIS
		$authuser = "AIS";
	} else if (strpos($clientIP, "49.230") !== FALSE) {
		//AUTH, AIS
		$authuser = "AIS";
	} else if (strpos($clientIP, "49.231") !== FALSE) {
		//AUTH, AIS
		$authuser = "AIS";
	} else if (strpos($clientIP, "27.55") !== FALSE) {
		//AUTH, TRUE
		$authuser = "TRUE";
	} else if (strpos($clientIP, "223.24") !== FALSE) {
		//AUTH, TRUE
		$authuser = "TRUE";
	} else if (strpos($clientIP, "2001:44c8:") !== FALSE) {
		//AUTH, AIS IPv6
		$authuser = "AIS";
	} else if (strpos($clientIP, "2405:9800:bc00:4012:") !== FALSE) {
		//AUTH, AIS F IPv6
		$authuser = "AIS";
	} else {
		//NOT AUTH
		$authuser = "guest";
	}

	if ($authString == "True" ) {
		if (! $donotechologin) {
			echo $authString;
		}
	} else {
		echo "Error: Password is incorrect or bad client!";
	}
?>