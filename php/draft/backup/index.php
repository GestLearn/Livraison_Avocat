<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
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
                <th>Action</th>
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
  	<div style="text-align: center; margin-top: 20px;">
        <button id="exportPdf">Export All in pdf</button>
    </div>

  <script src="./html2pdf.bundle.min.js"></script>

<script src="./html2pdf.bundle.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function () {
        var table = $('#table').DataTable({
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    $('input', this.header()).on('keyup change', function () {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
                });
            }
        });

        // Add a click event listener to the "Download" button in each row
        $('#table tbody').on('click', '.download-btn', function () {
            var rowData = table.row($(this).parents('tr')).data(); // Get the data of the clicked row
            generatePdfForRow(rowData); // Generate a PDF for the clicked row data
        });

        function generatePdfForRow(rowData) {
            var tableHeaders = table.columns().header().toArray(); // Get an array of table headers

            // Create a new plain text element for the PDF
            var textElement = document.createElement('pre');

            // Combine table headers and table data before creating the PDF content
            var rowContent = "";
            for (var i = 0; i < rowData.length - 1; i++) {
                rowContent += tableHeaders[i].textContent + ': ' + rowData[i] + '\n';
            }

            textElement.textContent = rowContent;

            var opt = {
                margin: 1,
                filename: 'row_data.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // Generate PDF from the plain text element
            html2pdf().from(textElement).set(opt).save();
        }
    });
</script>
<script type="text/javascript">
    document.getElementById('exportPdf').onclick = function () {
        var table = document.querySelector('table'); // Select your table
        var tableContent = '';

        // Loop through table rows and cells to extract content
        table.querySelectorAll('tr').forEach(function (row) {
            var cells = row.querySelectorAll('td, th');
            for (var i = 0; i < cells.length - 1; i++) { // Skip the last cell (td or th)
                tableContent += cells[i].textContent + '\n'; // Separate cells with newlines
            }
            tableContent += '\n'; // Separate rows with a blank line
        });

        // Create a new plain text element for the PDF
        var textElement = document.createElement('pre');
        textElement.textContent = tableContent;

        var timestamp = new Date().toISOString().replace(/[-:.]/g, '_'); // Format timestamp
        var opt = {
            margin: 1,
            filename: 'myfile_' + timestamp + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        // Generate PDF from the plain text element
        html2pdf().from(textElement).set(opt).save();
    }
</script>


</body>
</html>
