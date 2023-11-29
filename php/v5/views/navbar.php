<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    

    // Check if the user session is not set, and if not, redirect to the login page
    

    $userId = $_SESSION["user_id"];
    $userRole = $_SESSION["role"];
    $cityIds = unserialize($_SESSION["city_ids"]);
    
    $sqlNotifications = "SELECT * FROM notifications WHERE read_status = 0";
    $resultNotifications = $conn->query($sqlNotifications);

    $unreadNotificationCount = $resultNotifications->num_rows
 
?>



    <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="container">
            <ul class="navbar-nav">
             
                <li class="nav-item">
                    <a class="navbar-text" href="/">Welcome, <?php echo $_SESSION["username"]; ?></a>
                </li>
                <li class="nav-item">
                </li>
                <?php if ($userRole === "admin") { ?>
                    <li class="nav-item">
                        <span class="navbar-text notification-icon" data-toggle="modal" data-target="#notificationsModal">
                        &nbsp;
                            <i class="fas fa-bell"></i>
                            <span class="badge badge-danger"><?php echo $unreadNotificationCount; ?></span>
                        </span>
                    </li>
                <?php } ?>
            </ul>
            <ul class="navbar-nav ml-auto">

                <?php if ($userRole === "admin") { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/views/notifications.php"> all notifications </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/controllers/UpdateBDD.php"> Update BDD </a>
                    </li>
                    
                    <!-- Add this code in the header section -->
                
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link" href="/controllers/logout.php">Logout</a>
                </li>

            </ul>
                

            
        </div>
    </nav>
    

    <!-- Add this modal at the end of your HTML file -->
    <div class="modal fade" id="notificationsModal" tabindex="-1" role="dialog" aria-labelledby="notificationsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationsModalLabel">Notifications</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php
                        while ($notification = $resultNotifications->fetch_assoc()) {
                            $packageId = $notification['package_id'];
                            $address = $notification['address'];
                            $timestamp = $notification['timestamp'];

                            echo "<a href='./controllers/set-notification-status.php?package_id={$packageId}&status=1'>";
                            echo "<p>Package ID: {$packageId}, Address: {$address}, Time: {$timestamp}</p>";
                            echo "</a>";
                        }
                        ?>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



        <script>
        $(document).ready(function () {
            // Add an event listener to the "Declare here" buttons
            $(".download-btn2").click(function (event) {
                event.preventDefault(); // Prevent the default form submission

                var row = $(this).closest("tr"); // Get the parent row of the button
                var packageId = row.find("td:first").text(); // Get the package ID from the first column
                var address = row.find("td:nth-child(5)").text(); // Assuming address is in the fifth column
                // var declared = false
                // Send an AJAX request to the backend to declare incorrect address
                $.ajax({
                    type: "POST", // Use POST method
                    url: "/controllers/declareIncorrectAddress.php", // Replace with your backend endpoint URL
                    data: { 
                        packageId: packageId,
                        address: address
                        // declared: declared
                    }, // Send the package ID to the backend
                    success: function (response) {
                        // Handle the response from the backend (if needed)
                        console.log(response);
                        // You can update the UI here to indicate the address has been declared as incorrect
                        location.reload();
                    },
                    error: function (error) {
                        // Handle any errors (if needed)
                        console.error("Error: " + JSON.stringify(error));
                    }
                });
            });
        });
    </script>