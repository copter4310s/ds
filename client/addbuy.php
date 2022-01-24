<?php
	date_default_timezone_set('Asia/Bangkok');
	error_reporting(0);

	//START SECTION: LOGIN
	$donotechologin = TRUE;
	include "./login.php";
	//END SECTION: LOGIN

	include "../module/config.php";

	if ($auth AND $authuser != "guest") {
		//GET PRODUCTS INFORMATION
		$buyDat = $_POST["buyDate"];
		$productName = $_POST["productName"];
		$category = $_POST["category"];
		$buyPrice = $_POST["buyPrice"];
	
		$buyDate = date("Y-m-d", strtotime($buyDat));
		settype($buyPrice, "integer");

		//START SECTION: SYMBOL CHECK
		include "../module/symbolcheck.php";
		//END SECTION: SYMBOL CHECK

		//CHECK IF ONE BLANK
		if (trim($buyDat) == "" or trim($productName) == "" or trim($category) == "" or trim($buyPrice) == "") {
			//BLANK, ECHO
			echo "Error: Please complete an information before save the data!";
		//CHECK SYMBOL
		} else if (symbolcheck($productName) != "ok") {
			echo "Error: กรุณาเปลี่ยนเครื่องหมาย " . trim(symbolcheck($productName)) . " เป็นอย่างอื่นแทนในชื่อสินค้า!";
		} else {
			//CONTINUE, SET CONNECTION DETAIL
            include "./sqllogin.php";

			//CREATE AND CHECK CONNECTION
			$conn = new mysqli($servername, $username, $password, $dbname);

			if ($conn->connect_error) {
				$connectionerror = TRUE;
			}
	
			//INSERT PRODUCTS INFORMATION
			$sql = "INSERT INTO $tb_name(buyDate, list, category, buyPrice, avalible) VALUES ('" . mysqli_real_escape_string( $conn, $buyDate ) . "', '" . mysqli_real_escape_string( $conn, $productName ) . "', '" . mysqli_real_escape_string( $conn, $category ) . "', $buyPrice, 1)";
			$retval = mysqli_query($conn, $sql);
   
			if(! $retval) {
				$inserterror = TRUE;
				$errormessage = $conn->error;
			}

			//CHECK ERROR STATE
			if ($connectionerror == TRUE) {
				//CONNECTION ERROR
				echo "Error: ไม่สามารถเข้าสู่ระบบฐานข้อมูลได้!";
			} else if ($inserterror == TRUE) {
				//INSERT ERROR
				echo "Error: เกิดข้อผิดพลาดขณะทำการเพิ่มข้อมูล!";
			} else {
				//NO ERROR FOUND (SUCESSFULLY)
				echo "Successfully: ระบบได้ทำการเพิ่มข้อมูลซื้อเสร็จเรียบร้อยแล้ว!";
			}
			
			//CLOSE CONNECTION
			$conn->close();
		}
	} else {
		//LOGIN ERROR, CHECK IF ADMINPASSWORD IS BLANK
		if ($adminpassword == "da39a3ee5e6b4b0d3255bfef95601890afd80709") {
			//BLANK, NOTHING
		} else {
			//SHOW NOT AUTH
			echo "<img src='/blur.png' id='blockanother4' width='100%' height='100%'>";
			echo "<table class='curve center' border='1' id='tbinfo4'>
			<tr>
			<td class='curve'>
			<div style='padding:28px 28px 28px 28px;'><font size='4'><strong>
			<center>" . _ENTER_PASSWORD_CORRECTLY . "<br></center><img src='/blank.png' height='32' /><br>
			<center><button class='myButton' onclick='hidetbinfo(4)'>" . _CLOSE_MESSAGE . "</button></center>
			</strong></font></div>
			</td>
			</tr>
			</table>";
			
			//SAVE AUTH FAIL
			date_default_timezone_set('Asia/Bangkok');
			$date = date("Y-m-d");
			$time = date("H:i:s");
			$page = "addbuy.php";

			//CONTINUE INSERT, SET CONNECTION DETAIL
            $detailsReader = fopen("../module/dbpassword.txt", 'r');
            $decodeconnectiondetails = base64_decode( fgets( $detailsReader ) );
            fclose($detailsReader);

            $currentLine = 1;
            $details = explode("\n", $decodeconnectiondetails);
            foreach($details as $detailperline) {    
                if ($currentLine == 1) {
                    $servername = base64_decode( base64_decode( $detailperline ) );
                } else if ($currentLine == 2) {
                    $username = base64_decode( base64_decode( $detailperline ) );
                } else if ($currentLine == 3) {
                    $password = base64_decode( base64_decode( base64_decode( $detailperline ) ) );
                } else if ($currentLine == 4) {
                    $dbname = base64_decode( base64_decode( $detailperline ) );
                }

                $currentLine += 1;
            }
			
			//CREATE AND CHECK CONNECTION
			$conn = new mysqli($servername, $username, $password, $dbname);

			if ($conn->connect_error) {
				$connectionerror = TRUE;
			}
	
			//INSERT AUTH FAIL
			$useros = mysqli_real_escape_string( $conn, $_SERVER['HTTP_USER_AGENT'] );
			$sql = "INSERT INTO pzDSLoginFail(date, time, ip, os, page) VALUES ('$date', '$time', '$clientIP', '$useros', '$page')";
			$retval = mysqli_query($conn, $sql);
   
			if(! $retval) {
	
			}
	
			//CLOSE CONNECTION
			$conn->close();
		}
	}

?>