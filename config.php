<?php
// config.php

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'apexplanet');
define('DB_PASSWORD', '12345@');
define('DB_NAME', 'blog');

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>