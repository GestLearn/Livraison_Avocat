<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require 'config.php'; 
?>
 <!DOCTYPE html>
<html lang="en" dir="ltr">
	<head> 
		<meta charset="utf-8">
		<title>Import Excel To MySQL</title>
	</head>
	<body>
		<form class="" action="" method="post" enctype="multipart/form-data">
			<input type="file" name="excel" required value="">
			<button type="submit" name="import">Import</button>
		</form>
		<hr>

		<?php
		if(isset($_POST["import"])){
			echo("enter1");
			$fileName = $_FILES["excel"]["name"];
			$fileExtension = explode('.', $fileName);
            $fileExtension = strtolower(end($fileExtension));
			$newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;

			$targetDirectory = "uploads/bdd/" . $newFileName;
			move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

			error_reporting(0);
			ini_set('display_errors', 0);

			require './excelReader/excelReader-main/excel_reader2.php';
			require './excelReader/excelReader-main/SpreadsheetReader.php';

			$reader = new SpreadsheetReader($targetDirectory);
			$skipFirstRow = true; // Initialize a variable to skip the first row

            foreach($reader as $key => $row) {
                var_dump($row);
                if ($skipFirstRow) {
                    $skipFirstRow = false; // Set to false to indicate we've skipped the first row
                    continue; // Skip the first row
                }

                $proName = $row[0];
                $idPkg = $row[1];
                $destLastname = $row[2];
                $destFirstname = $row[3];
                $address = $row[4];
                $oldAddress = $row[5];
                $city =  $row[6];
                $destPhone = $row[7];
                $destIdNumber = $row[8];
                $deliverer = $row[9];
                $manager = $row[10];
                $letterType = $row[11];
                // $ = $row[11];

                // Perform a SELECT query to check if the city exists
                $selectQuery = "SELECT id FROM city WHERE name = '$city'";
                $result = mysqli_query($conn, $selectQuery);

                if (mysqli_num_rows($result) > 0) {
                    echo '<br>';
                    echo " city exist ";
                    echo '<br>';
                    // The city already exists in the database
                    // Fetch the id of the existing city
                    $row = mysqli_fetch_assoc($result);
                    $cityId = $row['id'];
                } else {
                    echo '<br>';
                    echo " city exist ";
                    echo '<br>';
                    // The city doesn't exist, so insert it
                    $insertQuery = "INSERT INTO city (name) VALUES ('$city')";
                    mysqli_query($conn, $insertQuery);
                    // Get the id of the newly inserted city
                    $cityId = mysqli_insert_id($conn);
                }
                echo '<br>';
                echo "The ID of the city is: $cityId";
                echo '<br>';

                // Perform a SELECT query to check if the city exists
                $selectQuery = "SELECT id FROM dest WHERE id_number = '$destIdNumber'";
                $result = mysqli_query($conn, $selectQuery);

                if (mysqli_num_rows($result) > 0) {
                    echo '<br>';
                    echo " dest exist ";
                    echo '<br>';
                    // The city already exists in the database
                    // Fetch the id of the existing city
                    $row = mysqli_fetch_assoc($result);
                    $destID = $row['id'];
                } else {
                    echo '<br>';
                    echo " dest no exist ";
                    echo '<br>';
                    // The city doesn't exist, so insert it
                    if($oldAddress = ""){
                        $oldAddress = null;
                    }else{}
                    $insertQuery = "INSERT INTO dest (firstname, lastname, address,old_address, phone_number, id_number ) VALUES ('$destFirstname','$destLastname','$address','$oldAddress','$destPhone','$destIdNumber')";
                    mysqli_query($conn, $insertQuery);
                    // Get the id of the newly inserted city
                    $destID = mysqli_insert_id($conn);
                }

                echo '<br>';
                echo "The ID of the dest is: $destID";
                echo '<br>';

                $selectQuery = "SELECT id FROM user WHERE name = '$deliverer' AND role = 'deliverer'";
                $result = mysqli_query($conn, $selectQuery);

                if (mysqli_num_rows($result) > 0) {
                    echo '<br>';
                    echo " deliverer exist ";
                    echo '<br>';
                    // The city already exists in the database
                    // Fetch the id of the existing city
                    $row = mysqli_fetch_assoc($result);
                    $delivererID = $row['id'];
                } else {
                    echo '<br>';
                    echo " deliverer no exist ";
                    echo '<br>';
                    // The city doesn't exist, so insert it
                    $insertQuery = "INSERT INTO user (name, role) VALUES ('$deliverer', 'deliverer');";
                    mysqli_query($conn, $insertQuery);
                    // Get the id of the newly inserted city
                    $delivererID = mysqli_insert_id($conn);
                }
                echo '<br>';
                echo "The ID of the deliverer is: $delivererID";
                echo '<br>';

                /////////////////////////// deliverer city //////////////////////////////////////
                $selectQuery = "SELECT * FROM usercity WHERE user_id = $delivererID AND city_id = $cityId";
                $result = mysqli_query($conn, $selectQuery);

                if (mysqli_num_rows($result) > 0) {
                    echo '<br>';
                    echo " link deliverer city exist ";
                    echo '<br>';
                    // The city already exists in the database
                    // Fetch the id of the existing city
                    $row = mysqli_fetch_assoc($result);
                    // $delivererID = $row['id'];
                } else {
                    echo '<br>';
                    echo " link deliverer city  no exist ";
                    echo '<br>';
                    // The city doesn't exist, so insert it
                    $insertQuery = "INSERT INTO usercity (user_id, city_id) VALUES ($delivererID, $cityId);";
                    mysqli_query($conn, $insertQuery);
                    // Get the id of the newly inserted city
                    // $managerID = mysqli_insert_id($conn);
                }

                /////////////////////////// Manager //////////////////////////////////////
                $selectQuery = "SELECT id FROM user WHERE name = '$manager' AND role = 'manager'";
                $result = mysqli_query($conn, $selectQuery);

                if (mysqli_num_rows($result) > 0) {
                    echo '<br>';
                    echo " manager exist ";
                    echo '<br>';
                    // The city already exists in the database
                    // Fetch the id of the existing city
                    $row = mysqli_fetch_assoc($result);
                    $managerID = $row['id'];
                } else {
                    echo '<br>';
                    echo " manager no exist ";
                    echo '<br>';
                    // The city doesn't exist, so insert it
                    $insertQuery = "INSERT INTO user (name, role) VALUES ('$manager', 'manager');";
                    mysqli_query($conn, $insertQuery);
                    // Get the id of the newly inserted city
                    $managerID = mysqli_insert_id($conn);
                }
                echo '<br>';
                echo "The ID of the manager is: $managerID";
                echo '<br>';

                /////////////////////////// manager city //////////////////////////////////////
                $selectQuery = "SELECT * FROM usercity WHERE user_id = $managerID AND city_id = $cityId";
                $result = mysqli_query($conn, $selectQuery);

                if (mysqli_num_rows($result) > 0) {
                    echo '<br>';
                    echo " link manager city exist ";
                    echo '<br>';
                    // The city already exists in the database
                    // Fetch the id of the existing city
                    $row = mysqli_fetch_assoc($result);
                    // $managerID = $row['id'];
                } else {
                    echo '<br>';
                    echo " link manager city  no exist ";
                    echo '<br>';
                    // The city doesn't exist, so insert it
                    $insertQuery = "INSERT INTO `usercity` (`user_id`, `city_id`) VALUES ($managerID, $cityId);";
                    mysqli_query($conn, $insertQuery);
                    // Get the id of the newly inserted city
                    // $managerID = mysqli_insert_id($conn);
                }

                /////////////////////////// pkg info //////////////////////////////////////
                $selectQuery = "SELECT * FROM pkg_info WHERE id = $idPkg";
                $result = mysqli_query($conn, $selectQuery);

                if (mysqli_num_rows($result) > 0) {
                    echo '<br>';
                    echo " package exist ";
                    echo '<br>';
                    // The city already exists in the database
                    // Fetch the id of the existing city
                    $row = mysqli_fetch_assoc($result);
                    $packageID = $row['id'];
                } else {
                    echo '<br>';
                    echo " package no exist ";
                    echo '<br>';
                    // The city doesn't exist, so insert it
                    echo  "-----idPkg:" .$idPkg . "-----:proName" . $proName. "-----cityId:" . $cityId. "-----:managerID:" . $managerID. "-----delivererID:" . $delivererID. "-----sign:" . 'null'. "-----destID" . $destID;
                    $insertQuery = "INSERT INTO pkg_info (id, project_name, id_city, id_manager, id_deliverer, signature, id_dest) VALUES ($idPkg, '$proName', $cityId, $managerID, $delivererID, null, $destID);";
                    echo '<br>';
                    echo 'done';
                    echo '<br>';
                        
                    mysqli_query($conn, $insertQuery);
                    // Get the id of the newly inserted city
                    $packageID = mysqli_insert_id($conn);
                }

                echo '<br>';
                echo "The ID of the manager is: $managerID";
                echo '<br>';
				echo
                "
                <script>
                      alert('Succesfully Imported');
                      document.location.href = '/home.php';
                </script>
                ";


                /*INSERT INTO `pkg_info` (`id`, `project_name`, `id_city`, `id_manager`, `id_deliverer`, `signature`, `id_dest`) VALUES
(201201, 'A', 1, 2, 12, 'signature_1696839056685.png', 1),*/
                
                
            }   
				// $name = $row[0]; INSERT INTO `usercity` (`user_id`, `city_id`) VALUES (9, 1)
				// $age = $row[1];
				// $country = $row[2];
				// mysqli_query($conn, "INSERT INTO tb_data VALUES('', '$name', '$age', '$country')");
        }

		 	
		// }/
        // header("Location: /home.php");
		?>
	</body>
</html>

<?php
// if (class_exists('ZipArchive')) {
//     echo "ZipArchive is enabled.";
// } else {
//     echo "ZipArchive is not enabled.";
// }

// phpinfo();