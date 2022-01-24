<?php
    $match = array();
    preg_match('#\[(.*?)\]#', "วิทยุ Loewe-Opta [269]", $match);
    var_dump($match);
    $id = (int) $match[1];
    echo $id;
?>