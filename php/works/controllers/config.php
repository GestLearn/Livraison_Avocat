<?php

 	$servername = 'localhost';
    $dbname = 'new-liv-v1';
    $dbusername = 'root';
    $dbpassword = '';
    // Create a database connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>