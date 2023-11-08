<?php 
 $servername = 'db5014599449.hosting-data.io';
  $dbname = 'dbs12132142';
  $username = 'dbu5396336';
  $password = 'Sundus@Pel$67000.';

$conn = new mysqli($servername, $username, $password, $dbname);
global $conn;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo '<p>Connection to the MySQL server established successfully.</p>';
}

// Retrieve data from the submitted form
$statusOption = $_POST['statusOption'];
$whoGotIt = $_POST['whoGotIt'];
$relationship = $_POST['relationship'];
$passage1 = isset($_POST['passage1']) ? 1 : 0;
$passage2 = isset($_POST['passage2']) ? 1 : 0;
$passage3 = isset($_POST['passage3']) ? 1 : 0;

$basic_info_id = intval($_POST['basic_info_id']);

$uploadedFiles = $_FILES['photos'];

// Check the number of uploaded files
$fileCount = count($uploadedFiles['name']);

// Define the upload directory
$uploadDir = 'uploads/imgs/';

// Initialize photo variables
$photoImmo = null;
$photoPkg = null;
$photoImmoName = null;
$photoPkgName = null;

if ($fileCount > 0) {
    // At least one file is uploaded
    $tmpFiles = $uploadedFiles['tmp_name'];

    if ($fileCount >= 1) {
        // Handle the first uploaded file
        $fileName1 = $uploadedFiles['name'][0];
        $photoImmoName = $fileName1;
        $photoImmo = $uploadDir . $fileName1;

        // Move the first file to the "photoImmo" directory
        move_uploaded_file($tmpFiles[0], $photoImmo);
    }

    if ($fileCount == 2) {
        // Handle the second uploaded file (if available)
        $fileName2 = $uploadedFiles['name'][1];
        $photoPkgName = $fileName2;
        $photoPkg = $uploadDir . $fileName2;

        // Move the second file to the "photoPkg" directory
        move_uploaded_file($tmpFiles[1], $photoPkg);
    }
}

// Check if a row with the same basic_info_id already exists
$sql_check = "SELECT basic_info_id FROM status WHERE basic_info_id = $basic_info_id";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    // A row with the same basic_info_id exists, so update the existing row
    $sql = "UPDATE status
            SET statusOption = '$statusOption',
                whoGotIt = '$whoGotIt',
                relationship = '$relationship',
                passage1 = $passage1,
                passage2 = $passage2,
                passage3 = $passage3,
                photoImmo = '$photoImmoName',
                photoPkg = '$photoPkgName'
                
            WHERE basic_info_id = $basic_info_id";
} else {
    // No row with the same basic_info_id found, so insert a new row
    $sql = "INSERT INTO status (basic_info_id, statusOption, whoGotIt, relationship, passage1, passage2, passage3, photoImmo, photoPkg)
            VALUES ($basic_info_id, '$statusOption', '$whoGotIt', '$relationship', $passage1, $passage2, $passage3,'$photoImmoName', '$photoPkgName')";
}

// Perform the SQL query
if (mysqli_query($conn, $sql)) {
    echo "Data updated/inserted successfully!";
    header('Location: /');
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>