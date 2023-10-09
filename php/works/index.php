<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    .table-container {
        overflow-x: auto; /* Enable horizontal scrolling */
        max-width: 100%; /* Limit the maximum width of the container */
    }

    table {
        width: 100%; /* Make the table occupy the full width of the container */
    }

    th {
        position: sticky;
        top: 0;
        background-color: white;
    }
  </style>
</head>
<body>
	<div style="text-align: center; margin-bottom: 20px;">
      	<h1>Data from Database</h1>
    </div>
    
    
    <?php
  
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "new-liv-v1";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
          echo '<p>Connexion au serveur MySQL établie avec succès.</p>';
        }

        $sql = "SELECT * FROM basic_infos";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
    ?>
  <div class="table-container">
    <table class="table table-striped table-smaller table-sm mt-4" id="table">
        <thead>
            <tr>
                <th> ID <input type="text" placeholder="Filter by ID" class="form-control form-control-sm"></th>
                <th> Project Name <input type="text" placeholder="Filter by Project Name" class="form-control form-control-sm"></th>
                <th> First Name <input type="text" placeholder="Filter by First Name" class="form-control form-control-sm"></th>
                <th> Last Name <input type="text" placeholder="Filter by Last Name" class="form-control form-control-sm"></th>
                <th> Address <input type="text" placeholder="Filter by Address" class="form-control form-control-sm"></th>
              	<th> Old Address <input type="text" placeholder="Filter by Old Address" class="form-control form-control-sm"></th>
                <th> City <input type="text" placeholder="Filter by City" class="form-control form-control-sm"></th>
                <th> Deliverer Name <input type="text" placeholder="Filter by Deliverer Name" class="form-control form-control-sm"></th>
                <th> Manager Name <input type="text" placeholder="Filter by Manager Name" class="form-control form-control-sm"></th>
                <th> Phone <input type="text" placeholder="Filter by Phone" class="form-control form-control-sm"></th>
                <th> ID Number <input type="text" placeholder="Filter by ID Number" class="form-control form-control-sm"></th>
                <th> Letter Type <input type="text" placeholder="Filter by Letter Type" class="form-control form-control-sm"></th>
                <th>Delivered</th>
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
                <td><?php echo $row["projectName"]; ?></td>
                <td><?php echo $row["destFirstName"]; ?></td>
                <td><?php echo $row["destLastName"]; ?></td>
                <td><?php echo $row["destAdresse"]; ?></td>
                <?php if (!empty($row["oldAddress"])) { ?>
                    <td><?php echo $row["oldAddress"]; ?></td>
                <?php } else { ?>
                    <td>No old adress</td>
                <?php } ?>
                <td><?php echo $row["destVille"]; ?></td>
                <td><?php echo $row["delivererName"]; ?></td>
                <td><?php echo $row["managerName"]; ?></td>            
                <td><?php echo $row["destPhone"]; ?></td>
                <td><?php echo $row["destIdNumber"]; ?></td>
                <td><?php echo $row["letterType"]; ?></td>
                <td>
                <button class="status-btn" data-toggle="modal" data-target="#statusModal<?php echo $rowId; ?>">Set Status</button>
                </td>
                <?php if (!empty($row["signature"])) { ?>
                    <td><?php echo $row["signature"]; ?></td>
                <?php } else { ?>
                    <td>
                        <a href='./signNew.php?id=<?php echo $row["id"]; ?>'>sign</a>
                    </td>
                <?php } ?>
                <td>
                    <button class="download-btn">Download PDF</button>
                </td>
            </tr>
            <?php
                }
            ?>
        </tbody>
    </table>

    <!-- Add this modal at the end of your HTML body -->
    <div class="modal fade" id="statusModal<?php echo $rowId; ?>" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Set Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="statusOption<?php echo $row["id"]; ?>" value="Hand-delivered">
                    <label class="form-check-label">Hand-delivered</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="statusOption<?php echo $row["id"]; ?>" value="Passed to a trusted person">
                    <label class="form-check-label">Passed on to a trusted person in the company:</label>
                    <input type="text" class="form-control" id="relationship<?php echo $row["id"]; ?>" placeholder="Enter relationship">
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="statusOption<?php echo $row["id"]; ?>" value="Passed to a Family over 18">
                    <label class="form-check-label">Passed on to a Family over 18:</label>
                    <input type="text" class="form-control" id="familyRelationship<?php echo $row["id"]; ?>" placeholder="Enter relationship">
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="statusOption<?php echo $row["id"]; ?>" value="Absence">
                    <label class="form-check-label">Absence:</label>
                    <input type="text" class="form-control" id="passage1<?php echo $row["id"]; ?>" placeholder="Passage 1: Date + time">
                    <input type="text" class="form-control" id="passage2<?php echo $row["id"]; ?>" placeholder="Passage 2: Date + time">
                    <input type="text" class="form-control" id="passage3<?php echo $row["id"]; ?>" placeholder="Passage 3: Date + time">
                    <input type="file" class="form-control-file" id="photos<?php echo $row["id"]; ?>" accept="image/*">
                    <textarea class="form-control" id="letterAddress<?php echo $row["id"]; ?>" placeholder="Letter + door address"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateStatus(<?php echo $row["id"]; ?>)">Save</button>
            </div>
        </div>
    </div>
<!-- </div> -->
  </div>
    <?php
    } else {
        echo "No data found in the database.";
    }

    $conn->close();
    ?>
  	<div style="text-align: center; margin-top: 20px;">
        <button id="exportPdf">Export All in pdf</button>
    </div>

  <script src="./html2pdf.bundle.min.js"></script>

<script src="./html2pdf.bundle.min.js"></script>
    <script type="text/javascript">
    
</script>
<script src="./js/pdfGenerate.js"></script>
<script src="./js/pdfexportAll.js"></script>
<script type="text/javascript">
// function updateStatus(rowId) {
//     var selectedOption = $("input[name='statusOption" + rowId + "']:checked").val();
//     var relationship = $("#relationship" + rowId).val();
//     var familyRelationship = $("#familyRelationship" + rowId).val();
//     var passage1 = $("#passage1" + rowId).val();
//     var passage2 = $("#passage2" + rowId).val();
//     var passage3 = $("#passage3" + rowId).val();
//     var photos = $("#photos" + rowId)[0].files;
//     var letterAddress = $("#letterAddress" + rowId).val();

//     // Send this data to the server using an AJAX request and update the database

//     // Close the modal
//     $("#statusModal" + rowId).modal('hide');
// }

</script>


</body>
</html>
