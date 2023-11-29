<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config.php'; 
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['packageId'])) {
        // Get the package ID from the AJAX request
        $packageId = $_POST['packageId'];
        $address = $_POST['address'];
        //$declared = true;
        
        // Insert a notification into the database
        $sql = "INSERT INTO notifications (package_id, address, timestamp, read_status) VALUES ($packageId, '$address', NOW(), 0)";
        if ($conn->query($sql) === TRUE) {
            echo "Notification inserted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        $conn->close();
        // Send a response back to the client (if needed)
        echo "Address: " . $address . " declared as incorrect for Package ID: " . $packageId;
    } else {
        // Invalid request, packageId parameter is missing
        http_response_code(400); // Bad Request
        echo "Missing packageId parameter";
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method";
}
?>