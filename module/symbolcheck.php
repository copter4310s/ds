<?php
    function symbolcheck($charinput) {
        $returnchar = "";

        //START SECTION: CHECK SYMBOL IN CHARACTER
        if (strpos($charinput, "\\") !== FALSE) {
            //SYMBOL ERROR
            $returnchar = $returnchar . " \\";
        } 

        if (strpos($charinput, "*") !== FALSE) {
            //SYMBOL ERROR
            $returnchar = $returnchar . " *";
        }
        
        if (strpos($charinput, "\"") !== FALSE) {
            //SYMBOL ERROR
            $returnchar = $returnchar . " \"";
        }
        
        if (strpos($charinput, "`") !== FALSE) {
            //SYMBOL ERROR
            $returnchar = $returnchar . " `";
        }
        
        if (strpos($charinput, ";") !== FALSE) {
            //SYMBOL ERROR
            $returnchar = $returnchar . " ;";
        }
        //END SECTION: CHECK SYMBOL IN CHARACTER

        //RETURN STATUS OR ILLEGAL SYMBOL
        if ($returnchar == "") {
            return "ok";
        } else {
            return $returnchar;
        }
    }
?>