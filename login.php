<?php
// Start the session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form is submitted
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Simple validation (can be replaced with database check)
    if ($username == "Admin" && $password == "Admin123") {
        // Set session variables for the logged-in user
        $_SESSION["username"] = $username;
        $_SESSION["loggedin"] = true;

        // Redirect to a protected page (e.g., dashboard)
        header("Location: index.php");
        exit;
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #1f1f1f; /* Dark background */
            
            background-size: cover;
            background-position: center;
        }

        #form {
            background-color: rgba(0, 0, 0, 0.7); /* Dark overlay for contrast */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.8);
            text-align: center;
            width: 320px;
            color: #fff;
            border: 2px solid #ffcc00; /* Gold border */
        }

        h2 {
            font-size: 28px;
            color: #ffcc00; /* Gold color for heading */
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }

        label {
            font-size: 18px;
            color: #ffcc00;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background-color: #333;
            color: #fff;
            border: 2px solid #444;
            font-size: 16px;
            letter-spacing: 1px;
            text-align: center;
            text-transform: uppercase;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #ffcc00; /* Gold border on focus */
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            background-color: #ffcc00;
            color: #333;
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #e6b800; /* Hover effect with a darker gold */
        }

        p {
            color: #ff6666;
            font-size: 16px;
            margin-top: 15px;
        }

        /* Add a glow effect to input fields */
        input[type="text"], input[type="password"], input[type="submit"] {
            box-shadow: 0 0 5px rgba(255, 204, 0, 0.7);
        }
    </style>
</head>
<body>
    <div id="form">
        <h2>Sumo's Inventory System</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label><br>
            <input type="text" name="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" required><br><br>
            <input type="submit" name="login" id="btn" value="Log in">
        </form>

        <?php
        if (isset($error_message)) {
            echo "<p>" . $error_message . "</p>";
        }
        ?>
    </div>
</body>
</html>
