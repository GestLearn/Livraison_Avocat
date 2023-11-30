<?php

 	$servername = 'db5014599449.hosting-data.io';
    $dbname = 'dbs12132142';
    $dbusername = 'dbu5396336';
    $dbpassword = 'Sundus@Pel$67000.';
    // Create a database connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>