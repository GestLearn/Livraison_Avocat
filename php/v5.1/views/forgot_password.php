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
    <link rel="stylesheet" href="../public/css/style.css">

    
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
