<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
  <!-- <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> -->

  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> -->
  <!-- <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script> -->
  <style>
    /* table.table-smaller {
      font-size: 12px;
      padding: 5px;
    } */
    /* table {
        font-size: 10px; Adjust the font size as needed
    }

    th {
        font-size: 12px; Adjust the font size for table headers
        width: 25px !important; Set the width to 'auto' or a specific value as needed
    }

    td {
        padding: 4px; Adjust the padding as needed
    } */

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
    <h1>Data from Database</h1>
    

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "new-liv-v1";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM basic_infos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    ?>
    <div class="table-container">
        <table class="table  table-smaller table-sm mt-10" id="table">
            <thead>
                <tr>
                    <th> ID <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> Project Name <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> First Name <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> Last Name <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> Address <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> City <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> Deliverer Name <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> Manager Name <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> Old Address <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> Phone <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> ID Number <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> Letter Type <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> Created At <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
                    <th> Updated At <input type="text" placeholder="Filter ID" class="form-control form-control-sm"></th>
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
                <td><?php echo $row["destVille"]; ?></td>
                <td><?php echo $row["delivererName"]; ?></td>
                <td><?php echo $row["managerName"]; ?></td>
                <td><?php echo $row["oldAddress"]; ?></td>
                <td><?php echo $row["destPhone"]; ?></td>
                <td><?php echo $row["destIdNumber"]; ?></td>
                <td><?php echo $row["letterType"]; ?></td>
                <td><?php echo $row["createdAt"]; ?></td>
                <td><?php echo $row["updatedAt"]; ?></td>
                <td>
                    <button class="download-btn">Download</button>
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

    <!-- <button id="exportPdf">Export</button> -->

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



</body>
</html>
