<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Livraison</title>
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
  <div style="text-align: center; margin-bottom: 20px;">
    <h1>Data from Database</h1>
  </div>


  <?php

  $servername = 'db5014599449.hosting-data.io';
  $dbname = 'dbs12132142';
  $username = 'dbu5396336';
  $password = 'Sundus@Pel$67000.';

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
              <?php if (!empty($row["signature"])) { ?>
                <td>
                  <?php echo $row["signature"]; ?>
                </td>
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
    </div>
    <?php
  } else {
    echo "No data found in the database.";
  }

  $conn->close();
  ?>
  <div style="text-align: center; margin-top: 20px; margin-bottom: 20px;">
    <button id="exportPdf">Export All in pdf</button>
  </div>

  <script src="./js/pdfGenerateV2.js" async></script>
  <script src="./js/pdfexportAllV2.js" async></script>

</body>

</html>