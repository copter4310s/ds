<?php
    $sql_decodeconnectiondetails = base64_decode( file_get_contents("./module/dbpassword.txt") );

    $sql_currentLine = 1;
    $sql_details = explode("\n", $sql_decodeconnectiondetails);

    foreach($sql_details as $sql_detailperline) {    
        if ($sql_currentLine == 1) {
            $servername = base64_decode( base64_decode( $sql_detailperline ) );
        } else if ($sql_currentLine == 2) {
            $username = base64_decode( base64_decode( $sql_detailperline ) );
        } else if ($sql_currentLine == 3) {
            $password = base64_decode(  base64_decode( base64_decode( $sql_detailperline ) ) );
        } else if ($sql_currentLine == 4) {
            $dbname = base64_decode( base64_decode( $sql_detailperline ) );
        }
        
        $sql_currentLine += 1;
    }
?>