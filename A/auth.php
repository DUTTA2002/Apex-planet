<?php
// auth.php

session_start();

function authenticate($username, $password) {
    global $conn;
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password_hash'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        return true;
    } else {
        return false;
    }
}

function logout() {
    session_destroy();
}

function isLoggedIn() {
    return isset($_SESSION['username']);
}