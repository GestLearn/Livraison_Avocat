<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require 'config.php'; 
?>
<?php
// login_process.php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to your database and retrieve user information (including role and associated city IDs)
    

    // Retrieve user information from the database
    $sql = "SELECT * FROM user WHERE name = '$username'";
    $result = $conn->query($sql);
	//var_dump($result);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row["password"]; // Password retrieved from the database
        $role = $row["role"]; // Role retrieved from the database
        $user_id = $row["id"]; // Assuming the user ID is in the User table

        // Assuming the UserCity table has a structure with user_id and city_id
        $cityIds = array();
        $sqlCities = "SELECT city_id FROM usercity WHERE user_id = $user_id";
        $resultCities = $conn->query($sqlCities);

        if ($resultCities->num_rows > 0) {
            while ($rowCities = $resultCities->fetch_assoc()) {
                $cityIds[] = $rowCities["city_id"];
            }
        }

        // Check if the password is correct
        if (password_verify($password, $storedPassword)) {
            // Save user information in session variables
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            $_SESSION["city_ids"] = serialize($cityIds);


            // var_dump($cityIds);
            // die();
            $_SESSION["role"] = $role; // Save the user's role
            header("Location: /home.php");
           
        } else {
            // Password is incorrect, redirect to login page with an error message
            header("Location: /views/login.php?error=1"); // You can add a query parameter to indicate the error
        }
    } else {
        // User not found in the database, redirect to login page with an error message
        header("Location: login.php?error=2"); // You can use different values for different error cases
    }

    // Close the database connection
    $conn->close();
}
?>
