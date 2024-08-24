<?php
include "config.php";
// login.php
session_start();

// Assuming you have connected to your database
$username = $_POST['username'];
$password = $_POST['password'];

// Fetch user data from the database
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    // User authenticated, set session variables
    $_SESSION['username'] = $username;
    header('Location: index.php'); // Redirect to dashboard or restricted page
} else {
    echo "Invalid username or password";
}
?>