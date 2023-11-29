<?php
require '../controllers/config.php'; 
// Handle form submission to change the password
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_email = $_POST["email"];
    $new_password = password_hash($_POST["new_password"], PASSWORD_BCRYPT);

    // Validate and update the user's password in your database
    $updatePasswordQuery = "UPDATE user SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($updatePasswordQuery);
    $stmt->bind_param("ss", $new_password, $user_email);

    if ($stmt->execute()) {
        // Password updated successfully
        header("Location: /views/login.php"); // Redirect to the login page
        exit;
    } else {
        // Password update failed
        echo "Password update failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        h2 {
            text-align: center;
            margin-top: 20px;
        }
        form {
            max-width: 300px;
            margin: 0 auto;
        }
        input[type="email"], /* Change the input type to email */
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: #ff0000; /* Red text color for errors */
            font-weight: bold; /* Make the text bold */
            margin-top: 10px; /* Add some space above the error message */
        }
    </style>
</head>
<body>
    <h2>Change Password</h2>
    <form method="post" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Change Password</button>
    </form>
</body>
</html>
