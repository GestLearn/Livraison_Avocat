<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input,
        select,
        button {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        select[multiple] {
            height: auto;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form method="post" action="signup_process.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role">
                <option value="manager">Manager</option>
                <option value="deliverer">Deliverer</option>
            </select>
            <label>Select Cities:</label>
            <select name="cities[]" multiple>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "new-liv-v1";

                // Create a database connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch cities from the City table
                $sql = "SELECT id, name FROM City";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                    }
                }

                // Close the database connection
                $conn->close();
                ?>
            </select>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>

</html>
