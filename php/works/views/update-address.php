<?php
require '../controllers/config.php'; // Adjust the path as needed
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: /views/login.php");
    exit;
}

// Check if the package_id is provided in the URL
if (isset($_GET['package_id'])) {
    $packageId = $_GET['package_id'];

    // Retrieve existing information based on package_id
    $sqlRetrieveAddress = "SELECT d.address, p.id_dest
                           FROM pkg_info p
                           JOIN dest d ON p.id_dest = d.id
                           WHERE p.id = $packageId";

    $resultRetrieveAddress = $conn->query($sqlRetrieveAddress);

    if ($resultRetrieveAddress !== false && $resultRetrieveAddress->num_rows > 0) {
        $row = $resultRetrieveAddress->fetch_assoc();
        $currentAddress = $row['address'];
        $idDest = $row['id_dest'];

        // Display the form
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Update Address</title>

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
                require './navbar.php';
            ?>

            <div class="container mt-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Update Address for Package ID <?php echo $packageId; ?></h2>

                        <form action='/controllers/update-address.php' method='post'>

                            <input type='hidden' name='package_id' value='<?php echo $packageId; ?>'>
                            
                            <div class="form-group">
                                <label for='current_address'>Current Address:</label>
                                <input type='text' class="form-control" name='current_address' value='<?php echo $currentAddress; ?>' readonly>
                            </div>

                            <div class="form-group">
                                <label for='new_address'>New Address:</label>
                                <input type='text' class="form-control" name='new_address' required>
                            </div>

                            <button type='submit' class="btn btn-primary">Update Address</button>

                        </form>
                    </div>
                </div>
            </div>

        </body>

        </html>

        <?php
    } else {
        // Handle the case when the package_id is not found or other errors
        echo "Error: Package not found or other issues.";
    }
} else {
    // Handle the case when package_id is not provided in the URL
    echo "Error: Package ID not provided.";
}

$conn->close();
?>
