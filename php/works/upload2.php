<!-- <?php
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
    $folderPath = "upload/";
    $image_parts = explode(";base64,", $_POST['signed']); 
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $file = $folderPath . uniqid() . '.'.$image_type;
    file_put_contents($file, $image_base64);
    echo "Signature Uploaded Successfully.";
?> -->