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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $record_id = $_POST['record_id'];
  var_dump($record_id);
  $filename = $_POST['filename'];
  $imageBase64 = $_POST['imageBase64'];
  $path = "uploads/"; // Specify the folder where you want to save the image

  // Check if the folder exists, if not, create it
  if (!file_exists($path)) {
    mkdir($path, 0777, true);
  }

  // Decode the base64 image data
  $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageBase64));

  // Save the image to the folder
  file_put_contents($path . $filename, $imageData);

  $sql = "UPDATE basic_infos SET signature = '$filename' WHERE id = $record_id";
  // You can optionally save the file path or other information in your database
  // ...

    if ($conn->query($sql) === TRUE) {
        echo "Signature Uploaded Successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
  echo "File uploaded successfully.";
} else {
  echo "Invalid request.";
}


?>
