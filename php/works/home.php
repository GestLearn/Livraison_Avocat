<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require './controllers/config.php'; 

    session_start();

    // Check if the user session is not set, and if not, redirect to the login page
    if (!isset($_SESSION["user_id"])) {
        header("Location: /views/login.php");
        exit; // Terminate the script to prevent further execution
    }

    $userId = $_SESSION["user_id"];
    $userRole = $_SESSION["role"];
    $cityIds = unserialize($_SESSION["city_ids"]);
    
 
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <script src="https://unpkg.com/pdf-lib@1.4.0/dist/pdf-lib.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    .table-container {
      overflow-x: auto;
      /* Enable horizontal scrolling */
      max-width: 100%;
      /* Limit the maximum width of the container */
    }

    table {
      width: 100%;
      /* Make the table occupy the full width of the container */
    }

    th {
      position: sticky;
      top: 0;
      background-color: white;
    }
  </style>
</head>

<body>

    <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="navbar-text">Welcome, <?php echo $_SESSION["username"]; ?></span>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">

                <?php if ($userRole === "admin") { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/controllers/UpdateBDD.php"> Update BDD </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link" href="/controllers/logout.php">Logout</a>
                </li>

            </ul>
            
        </div>
    </nav>
    

        
    
    <!-- <div style="text-align: center; margin-bottom: 20px;">
        <h1>Data from Database</h1>
    </div> -->

    <?php
    

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

    // Assuming you have retrieved the user's role and associated city IDs in the session
    
    //   var_dump($_SESSION["user_id"]);
    //   die();
    $sql = "SELECT bi.id, bi.project_name, d.firstname AS destFirstName, d.lastname AS destLastName, d.address AS destAddress, d.old_address AS oldAddress, 
            c.name AS destCity, u1.name AS delivererName, u2.name AS managerName, d.phone_number AS destPhone, d.id_number AS destIdNumber, bi.signature, bi.id AS letterType
            FROM Pkg_info AS bi
            LEFT JOIN City AS c ON bi.id_city = c.id
            LEFT JOIN User AS u1 ON bi.id_deliverer = u1.id
            LEFT JOIN User AS u2 ON bi.id_manager = u2.id
            LEFT JOIN Dest AS d ON bi.id_dest = d.id";
            // var_dump($userRole);
            // die();
    if ($userRole === "admin") {
        // Admin can see all packages, no need for additional filtering
    } elseif ($userRole === "manager") {
        
        // var_dump($cityIds);
        // die();
        // Manager should only see packages associated with their cities
        $sql .= " WHERE bi.id_city IN (" . implode(",", $cityIds) . ")";
        $sql .= " AND bi.id_manager = " . $userId;
    } elseif ($userRole === "deliverer") {
        // var_dump($userRole);
        // die();
        // Deliverer should only see packages they are assigned to
        // $userId = $_SESSION["user_id"];
        $sql .= " WHERE bi.id_deliverer = $userId";
    }
    // var_dump($userRole);
    // var_dump($userId);
    // die();
    try {
        // Attempt to execute the SQL query
        $result = $conn->query($sql);
    } catch (Exception $e) {
        // Handle the exception (error)
        echo "An error occurred while executing the SQL query: " . $e->getMessage();
    }
    
    if ($result !== false) {
        // SQL query executed successfully
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

    $conn->close();
    ?>
    <div style="text-align: center; margin-top: 20px;  margin-bottom: 20px;">
        <button id="exportPdf">Export All in pdf</button>
    </div>

    <script src="./public/js/pdfGenerateV2.js" async></script>
    <script src="./public/js/pdfexportAllV2.js" async></script>

</body>

</html>
