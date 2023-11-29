<?php
    require './controllers/config.php';

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

    require './views/navbar.php';
    // Function to get the status for a specific ID
    function getStatusOption($id) {
        global $conn;
        $sql = "SELECT * FROM status WHERE pkg_info_id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
        } else {
        return false; // Status doesn't exist
        }
    }


    // Fetch all package data from all the tables

    $sql = "SELECT bi.id, bi.project_name, d.firstname AS destFirstName, d.lastname AS destLastName, d.address AS destAddress, d.old_address AS oldAddress, 
            c.name AS destCity, u1.name AS delivererName, u2.name AS managerName, d.phone_number AS destPhone, d.id_number AS destIdNumber, bi.signature, bi.id AS letterType
            FROM pkg_info AS bi
            LEFT JOIN city AS c ON bi.id_city = c.id
            LEFT JOIN user AS u1 ON bi.id_deliverer = u1.id
            LEFT JOIN user AS u2 ON bi.id_manager = u2.id
            LEFT JOIN dest AS d ON bi.id_dest = d.id";
            
    if ($userRole === "admin") {
    } elseif ($userRole === "manager") {
        $sql .= " WHERE bi.id_city IN (" . implode(",", $cityIds) . ")";
        $sql .= " AND bi.id_manager = " . $userId;
    } elseif ($userRole === "deliverer") {

        $sql .= " WHERE bi.id_deliverer = $userId";
    }

    try {
        $result = $conn->query($sql);
    } catch (Exception $e) {
        echo "An error occurred while executing the SQL query: " . $e->getMessage();
    }
    
    if ($result !== false) {
        if ($result->num_rows > 0) {
            ?>
        <div class="table-container">
        <table class="table table-striped table-smaller table-sm mt-4" id="table">
            <thead>
            <tr>
                <th> ID <input type="text" placeholder="Filter by ID" class="form-control form-control-sm"></th>
                <th> Project Name <input type="text" placeholder="Filter by Project Name"
                    class="form-control form-control-sm"></th>
                <th> First Name <input type="text" placeholder="Filter by First Name" class="form-control form-control-sm"></th>
                <th> Last Name <input type="text" placeholder="Filter by Last Name" class="form-control form-control-sm"></th>
                <th> Address <input type="text" placeholder="Filter by Address" class="form-control form-control-sm"></th>
                <th> Old Address <input type="text" placeholder="Filter by Old Address" class="form-control form-control-sm"></th>
                <th> City <input type="text" placeholder="Filter by City" class="form-control form-control-sm"></th>
                <th> Deliverer Name <input type="text" placeholder="Filter by Deliverer Name"
                    class="form-control form-control-sm"></th>
                <th> Manager Name <input type="text" placeholder="Filter by Manager Name"
                    class="form-control form-control-sm"></th>
                <th> Phone <input type="text" placeholder="Filter by Phone" class="form-control form-control-sm"></th>
                <th> ID Number <input type="text" placeholder="Filter by ID Number" class="form-control form-control-sm"></th>
                <th> Letter Type <input type="text" placeholder="Filter by Letter Type" class="form-control form-control-sm"></th>
                <th> Status</th>
                <th>Sign</th>
                <th>Download</th>
                <th>Declare a wrong address </th>
            </tr>
            </thead>
            <tbody>
            <?php
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["project_name"]; ?></td>
                        <td><?php echo $row["destFirstName"]; ?></td>
                        <td><?php echo $row["destLastName"]; ?></td>
                        <td><?php echo $row["destAddress"]; ?></td>
                        <?php if (!empty($row["oldAddress"])) { ?>
                        <td><?php echo $row["oldAddress"]; ?></td>
                        <?php } else { ?>
                        <td>No old address</td>
                        <?php } ?>
                        <td><?php echo $row["destCity"]; ?></td>
                        <td><?php echo $row["delivererName"]; ?></td>
                        <td><?php echo $row["managerName"]; ?></td>
                        <td><?php echo $row["destPhone"]; ?></td>
                        <td><?php echo $row["destIdNumber"]; ?></td>
                        <td><?php echo $row["letterType"]; ?></td>
                        <td><?php
                                // Check if the status exists for this ID
                                $statusData = getStatusOption($row["id"]);
                                if ($statusData) {
                                    $statusOption = $statusData["statusOption"];
                                    $whoGotIt = $statusData["whoGotIt"];
                                    $relationship = $statusData["relationship"];

                                    if ($statusOption === "Absence") {
                                        $passages = [];
                                        $psg1 = $statusData["passage1"];
                                        $psg2 = $statusData["passage2"];
                                        $psg3 = $statusData["passage3"];
                                        
                                        if ($psg1 && !$psg3) {
                                            if ($psg2 && !$psg3) {
                                                echo "<a href='/views/setStatus.php?id=" . $row["id"] . "'>" . "Set Status for the third passage</a>  ";
                                            } else {
                                                echo "<a href='/views/setStatus.php?id=" . $row["id"] . "'>" . "Set Status for the second passage</a>  ";
                                            }
                                        } else {
                                            if ($whoGotIt)
                                                echo "delivered to a " . $statusOption . " named " . $whoGotIt . " as a " . $relationship;
                                            else
                                                echo $statusOption;
                                        }
                                    } else {
                                        if ($whoGotIt)
                                            echo "delivered to a " . $statusOption . " named " . $whoGotIt . " as a " . $relationship;
                                        else
                                            echo $statusOption;
                                    }
                                } else {
                                    echo "<a href='/views/setStatus.php?id=" . $row["id"] . "'>Set Status here</a>";
                                }
                            ?>
                        </td>
                        <?php if (!empty($row["signature"])) { ?>
                            <td><?php echo $row["signature"]; ?></td>
                        <?php } else { ?>
                            <td><a href='./views/signNew.php?id=<?php echo $row["id"]; ?>'>sign</a></td>
                        <?php } ?>
                        <td><button class="download-btn">Download PDF</button></td>
                        <?php 
                            $sql_fetch_notifications = "SELECT * FROM notifications";
                            $result_notifications = $conn->query($sql_fetch_notifications);
                            // var_dump($result_notifications->num_rows);

                            $packageIdExists = false;
                            $pkgID = $row["id"];
                            if ($result_notifications->num_rows > 0) {
                                // Loop through each row to check if the package_id exists
                                while ($notification_row = $result_notifications->fetch_assoc()) {
                                    if ($notification_row['package_id'] === $pkgID) {
                                    
                                        $packageIdExists = true;
                                        break;
                                    }
                                }
                            }
                            $declared = $packageIdExists ? true : false;

                            if (!$declared) {
                                ?>
                                <td><a href="" class="download-btn2">Declare here</a></td>
                                <?php
                            } else {
                                ?>
                                <td>Done</td>
                                <?php
                            }
                        ?>
                        <!-- <td><a href="" class="download-btn2">Declare here</a></td> -->
                    </tr>
                    <?php
                }
            ?>
            </tbody>
        </table>
        </div>
        <?php
        } else {
            echo " No data found in the database.";
        }
    } else {
        // SQL query execution failed, display an error message
        echo " An error occurred while executing the SQL query. Please check your query for correctness.";
    }

    //
    ?>


        

<?php

$conn->close();
?>



    <div style="text-align: center; margin-top: 20px;  margin-bottom: 20px;">
        <button id="exportPdf">Export All in pdf</button>
    </div>
    
    <!-- Add this script to your HTML file -->
    

    <script src="./public/js/pdfGenerateV2.js" async></script>
    <script src="./public/js/pdfexportAllV2.js" async></script>

</body>

</html>
