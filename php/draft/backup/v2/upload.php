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

$recordId = $_POST['record_id'];
$folderPath = "upload/";
$image_parts = explode(";base64,", $_POST['signed']);
$image_type_aux = explode("image/", $image_parts[0]);
$image_type = $image_type_aux[1];
$image_base64 = base64_decode($image_parts[1]);
$file =  uniqid() . '.'.$image_type;
$fileLoc = $folderPath . $file ;
file_put_contents($fileLoc, $image_base64);

// Update the database record with the file name
$sql = "UPDATE basic_infos SET signature = '$file' WHERE id = $recordId";

if ($conn->query($sql) === TRUE) {
    echo "Signature Uploaded Successfully.";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
