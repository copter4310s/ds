<?php
    //THIS IS LOGIN SECTION OF ALL PAGE
    //YOU CAN CUSTOM THIS FILE

    //GET ADMINPASSWORD AND TOKEN
	$adminpassword = sha1($_POST["adminpassword"]);
	$token = base64_decode($_POST["token"]);
	$totoken = "";
	
	//SET TOTOKEN
	if ($_POST["adminpassword"] == NULL) {
		$totoken = $_POST["token"];
	} else {
		$totoken = base64_encode($adminpassword);
	}
	
	//SET VARIABLES
	$auth = FALSE;
	$authString = "False";
	$authuser = "guest";
	
	//READ ./module/loginpassword.txt AND SET
	$loginpassword = "";

	$passwordFile = fopen("./module/loginpassword.txt", "r") or die(_ERROR_READ_PASSWORD_FILE);
	$loginpassword = fread($passwordFile,filesize("./module/loginpassword.txt"));
	fclose($passwordFile);
	
	$loginpassword = base64_decode($loginpassword);
    
    //AUTH SYSTEMS, PASSWORD
    if ($adminpassword == $loginpassword) {
        $auth = TRUE;
		$authString = "True";
		$authuser = "Trusted Device";
    } else if ($token == $loginpassword) {
        $auth = TRUE;
		$authString = "True";
		$authuser = "Trusted Device";
    } else {
        $auth = FALSE;
		$authString = "False";
		$authuser = "Trusted Device";
    }

    //GET CLIENT IP
    /*$clientIP = "";
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
	/*if (strpos($clientIP, "184.22.") !== FALSE) {
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
	} else if (strpos($clientIP, "49.228") !== FALSE) {
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
	}*/

	//AUTH SYSTEMS, OS
	/*if ($auth == TRUE and $authuser != "guest") {
		$useros = $_SERVER["HTTP_USER_AGENT"];
	
		//AUTH SYSTEMS, CHECK OS
		if (strpos($useros, "Windows NT 6.3") !== FALSE) {
			//AUTH, WINDOWS 8.1
			$auth = TRUE;
			$authString = "True";
			$authuser = $authuser . ", Windows";
		} else if (strpos($useros, "Android 7.1.1; CPH1729") !== FALSE) {
			//AUTH, Android 7.1.1; CPH1729
			$auth = TRUE;
			$authString = "True";
			$authuser = $authuser . ", Android 7.1.1; CPH1729";
		} else if (strpos($useros, "Android 9; RMX1821") !== FALSE) {
			//AUTH, Android 9; RMX1821
			$auth = TRUE;
			$authString = "True";
			$authuser = $authuser . ", Android 9; RMX1821";
		} else {
			//NOT AUTH
			$auth = FALSE;
			$authString = "False";
			$authuser = "guest";
		}
	}*/
?>
