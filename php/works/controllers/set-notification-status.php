<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config.php'; 
?>
<?php

if (isset($_GET['package_id']) && isset($_GET['status'])) {
    $packageId = $_GET['package_id'];
    $status = $_GET['status'];

    // Update the notification status in the database
    $updateSql = "UPDATE notifications SET read_status = $status WHERE package_id = $packageId";
    $conn->query($updateSql);

    // Redirect back to the previous page or handle as needed
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit();
}
?>
