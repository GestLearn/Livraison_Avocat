<?php
require 'config.php'; // Adjust the path as needed
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: /views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['package_id'], $_POST['new_address'])) {
        $packageId = $_POST['package_id'];
        $newAddress = $_POST['new_address'];

        // Retrieve the id_dest from pkg_info
        $sqlRetrieveIdDest = "SELECT id_dest FROM pkg_info WHERE id = $packageId";
        $resultRetrieveIdDest = $conn->query($sqlRetrieveIdDest);

        if ($resultRetrieveIdDest !== false && $resultRetrieveIdDest->num_rows > 0) {
            $row = $resultRetrieveIdDest->fetch_assoc();
            $idDest = $row['id_dest'];

            // Update the address in dest table
            $sqlUpdateAddress = "UPDATE dest SET old_address = address, address = '$newAddress' WHERE id = $idDest";
            $conn->query($sqlUpdateAddress);

            // Delete the notification after updating the address
            $sqlDeleteNotification = "DELETE FROM notifications WHERE package_id = $packageId";
            $conn->query($sqlDeleteNotification);

            // Add additional logic if needed
            echo "Address updated successfully, and notification deleted.";
        } else {
            // Handle the case when the package_id is not found or other errors
            echo "Error: Package not found or other issues.";
        }
    } else {
        // Handle the case when package_id or new_address is not provided
        echo "Error: Package ID or New Address not provided.";
    }
} else {
    // Handle the case when the script is accessed without form submission
    echo "Error: Invalid request method.";
}

$conn->close();
?>
