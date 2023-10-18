<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $role = $_POST["role"];
    $selectedCities = $_POST["cities"];

    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "new-liv-v1";

    // Create a database connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert user data into the "User" table
    $insertUserQuery = "INSERT INTO User (name, password, role) VALUES (?, ?, ?)";

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare($insertUserQuery);
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        // The user record has been inserted successfully
        // Retrieve the user's ID
        $userId = $stmt->insert_id;

        // Loop through the selected cities and insert them into the "UserCity" table
        foreach ($selectedCities as $cityId) {
            $insertUserCityQuery = "INSERT INTO UserCity (user_id, city_id) VALUES (?, ?)";
            $userCityStmt = $conn->prepare($insertUserCityQuery);
            $userCityStmt->bind_param("ii", $userId, $cityId);
            $userCityStmt->execute();
        }

        // Close prepared statements
        $stmt->close();
        $userCityStmt->close();

        // Close the database connection
        $conn->close();

        // Redirect to the login page after successful registration
        header("Location: login.php");
        exit;
    } else {
        // Handle registration failure
        echo "Registration failed. Please try again.";
    }
}
?>
