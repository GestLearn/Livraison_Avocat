<?php
    require '../controllers/config.php'; 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    if (!isset($_SESSION["user_id"])) {
        header("Location: /views/login.php");
        exit; // Terminate the script to prevent further execution
    }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livraison</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    
    <!-- costumised css -->
    <link rel="stylesheet" href="../public/css/style.css">


    <script src="https://unpkg.com/pdf-lib@1.4.0/dist/pdf-lib.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>


<?php
//  error_reporting(E_ALL);
//  ini_set('display_errors', 1);
//  require '../controllers/config.php'; 
  require './navbar.php';

// Query unread notifications for the current admin user
$sqlNotifications = "SELECT * FROM notifications";
$resultNotifications = $conn->query($sqlNotifications);

// Display the notifications
if ($resultNotifications !== false && $resultNotifications->num_rows > 0) {
    ?>



<div class="notification-container">
    <h3>All Notifications</h3>
    <ul class="notification-list">
        <?php
        while ($notification = $resultNotifications->fetch_assoc()) {
            $statusClass = ($notification['read_status'] == 0) ? 'notification-status-0' : 'notification-status-1';
            $packageId = $notification['package_id'];
            
            // Check if the status is 0, then add a link
            $notificationItem = "<li class='notification-item $statusClass'>
                                    Package ID: {$notification['package_id']}, 
                                    Address: {$notification['address']}, 
                                    Time: {$notification['timestamp']}";
            
            if ($notification['read_status'] == 0) {
                $notificationItem .= "<br> <br><a class='mark_as_read' href='/controllers/set-notification-status.php?package_id={$packageId}&status=1'>
                                        Mark as Read
                                      </a>";
            }
            
            $notificationItem .= "</li>";
            
            echo $notificationItem;
        }
        ?>
    </ul>
</div>



    <?php
} else {
    echo "<p>No unread notifications.</p>";
}
$conn->close();
// ... (continue with the rest of your existing code)
?>
</body>

</html>