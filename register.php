<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password']; 

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* Reset some basic styles */
    body, html {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-color: #1cf1ae;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    /* Container for the register form */
    .register-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 400px;
        padding: 30px;
        box-sizing: border-box;
    }

    h1 {
        font-family: Georgia, 'Times New Roman', Times, serif;
        text-align: center;
        color: #0ea3e8;
        margin-bottom: 2px;
    }

    h5 {
        margin-top: 2px;
        text-align: end;
        margin-right: 5px;
    }

    /* Register form styles */
    .register-form h2 {
        font-family: Georgia, 'Times New Roman', Times, serif;
        text-align: center;
        color: #0cb1ed;
        margin-bottom: 30px;
    }

    .input-group {
        margin-bottom: 15px;
    }

    .input-group label {
        display: block;
        margin-bottom: 5px;
        color: #666;
    }

    .input-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 14px;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
    }

    button:hover {
        background-color: #45a049;
    }

    /* Sign in link styles */
    .signin-link {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
    }

    .signin-link a {
        color: #007BFF;
        text-decoration: none;
    }

    .signin-link a:hover {
        text-decoration: underline;
    }

</style>
<body>
    <div class="register-container">
        <form class="register-form" action="register.php" method="POST">
            <h1>Apex Planet</h1>
            <h5>Software Pvt Ltd</h5>
            <h2>Register</h2>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Register</button>
            <p class="signin-link">Already have an account? <a href="login.html">Sign in</a></p>
        </form>
    </div>
</body>
</html>
