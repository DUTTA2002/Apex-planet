<?php
// create.php

require_once 'crud.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Basic validation
    if (!empty($title) && !empty($content)) {
        $query = "INSERT INTO posts (title, content, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $title, $content);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header('Location: index.php'); // Redirect to the main page after successful creation
            exit;
        } else {
            echo "Error adding the post.";
        }
    } else {
        echo "Both fields are required!";
    }
}
?>

<html>
<head>
    <title>Create New Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        label, input, textarea {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <form method="post">
        <h2>Create New Post</h2>
        <hr>
        <label for="title">Title</label>
        <input type="text" name="title" required>
        
        <label for="content">Content</label>
        <textarea name="content" rows="5" required></textarea>
        
        <input type="submit" value="Add Post">
        
        <a href="index.php">Back to Home</a>
    </form>
</body>
</html>
