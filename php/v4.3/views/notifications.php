
<?php
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 require '../controllers/config.php'; 

// ... (your existing code)

// Query unread notifications for the current admin user
$sqlNotifications = "SELECT * FROM notifications";
$resultNotifications = $conn->query($sqlNotifications);

// Display the notifications
if ($resultNotifications !== false && $resultNotifications->num_rows > 0) {
    ?>
   <style>
    .notification-container {
        border: 1px solid #ddd;
        padding: 10px;
        margin-top: 20px;
        background-color: #f9f9f9;
    }

    .notification-container h3 {
        color: #333;
    }

    .notification-list {
        list-style-type: none;
        padding: 0;
    }

    .notification-item {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ddd;
        cursor: pointer;
    }

    .notification-status-0 {
        background-color: #ffe6e6; /* Light red background for status 0 */
    }

    .notification-status-1 {
        background-color: #e6f7e6; /* Light green background for status 1 */
    }

    .notification-item a {
        text-decoration: none;
        color: inherit; /* Inherit the color from the parent */
        display: block; /* Make the link fill the entire notification item */
    }

    .mark_as_read {
        color: #4CAF50; /* Green color for the "mark as read" link */
        font-weight: bold; /* Make the text bold */
        margin-top: 5px; /* Add some space between the notification text and the link */
        display: inline-block; /* Display the link inline with the text */
    }
</style>

<!-- Your existing HTML code -->


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
