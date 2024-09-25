<?php
// delete.php

require_once 'crud.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Check if the post exists
$user = getUserById($id);

if (!$user) {
    echo "Post not found.";
    exit;
}

// Proceed to delete the post
deleteUser($id);

// Redirect to index.php after deletion
header('Location: index.php');
exit;

?>