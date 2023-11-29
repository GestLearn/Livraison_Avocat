<?php
session_start();

if (isset($_SESSION["username"])) {
    // If the session is already set, redirect the user to the home page
    header("Location: home.php");
    exit;
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
    <h2>Login</h2>
    <form method="post" action="/controllers/login_process.php">
        <input type="email" name="email" placeholder="Email" required> <!-- Email input -->
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <?php
        if (isset($_GET["error"])) {
            $error = $_GET["error"];
            if ($error == 1) {
                $errorMessage = "Incorrect password. Please try again.";
            } elseif ($error == 2) {
                $errorMessage = "User not found. Please check your email."; // Update the error message
            }
            // Display $errorMessage in your login form
        }
        ?>
        <p class="error"> <?php echo $errorMessage; ?></p>
        
    </form>
    <h2>Forgot your password? <a href="/views/forgot_password.php">Reset it here</a></h2> <!-- Add Forgot Password link -->
</body>
</html>
