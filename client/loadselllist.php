<?php
	date_default_timezone_set('Asia/Bangkok');
	error_reporting(0);

	//START SECTION: LOGIN
	$donotechologin = TRUE;
	include "./login.php";
	//END SECTION: LOGIN

	include "../module/config.php";
	
	$productName = $_POST["productName"];
	
	usleep(_LOAD_SELL_LIST_DELAY);
	
	if ($auth and $authuser != "guest") {
		//CONTINUE, SET CONNECTION DETAIL

		$match = array();
		preg_match('#\[(.*?)\]#', $productName, $match);
		$id = (int) $match[1];

		//START SECTION: SQL LOGIN
		include "./sqllogin.php";
		//END SECTION: SQL LOGIN

		//CREATE AND CHECK CONNECTION
		$conn = new mysqli($servername, $username, $password, $dbname);
										
		if ($conn->connect_error) {
			$connectionerror = TRUE;
			echo "Error: ไม่สามารถเข้าสู่ระบบฐานข้อมูลได้!";
		}
										
		$sqlcom = "SELECT buyDate, category, buyPrice FROM $tb_name WHERE id=$id";
		
		$result = mysqli_query($conn, $sqlcom);
		$returnbuyDate = "";
		$returncategory = "";
		$returnbuyPrice = 0;
		
		if (mysqli_num_rows($result) == 0) {
			echo "Error: ไม่พบข้อมูลของ ID " . $id;
		} else {
			while($row = mysqli_fetch_array($result)) {
				$returnbuyDate = $row["buyDate"];
				$returncategory = $row["category"];
				$returnbuyPrice = $row["buyPrice"];
			}
		}

		$conn->close();

		$returnbuyDate = date("Y-m-d", strtotime($returnbuyDate));
		
		//ENCODE AS UTF8 AND RETURN DATA
		echo $returnbuyDate;
		echo "\n" . $returncategory;
		echo "\n" . $returnbuyPrice;
	}
?>