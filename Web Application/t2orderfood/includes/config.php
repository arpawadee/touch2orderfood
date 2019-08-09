<?php

    //database configuration
    $host       = "localhost";
    $user       = "id9808710_arpawadee";
    $pass       = "m044088039";
    $database   = "id9808710_t2orderfood";

    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8');
    }

?>