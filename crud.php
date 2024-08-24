<?php
// crud.php

require_once 'config.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    header('Location: login.html');
    exit;
}

function getAllUsers() {
    global $conn;
    $query = "SELECT * FROM users";
    $result = $conn->query($query);
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

function getUserById($id) {
    global $conn;
    $query = "SELECT * FROM users WHERE id = '$id'";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}

function createUser($username, $password) {
    global $conn;
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password_hash')";
    $conn->query($query);
}

function updateUser($id, $username) {
    global $conn;
    $query = "UPDATE users SET username = '$username' WHERE id = '$id'";
    $conn->query($query);
}

function deleteUser($id) {
    global $conn;
    $query = "DELETE FROM users WHERE id = '$id'";
    $conn->query($query);
}
?>