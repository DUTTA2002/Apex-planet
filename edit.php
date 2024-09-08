<?php
// edit.php

require_once 'crud.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$user = getUserById($id);

if (!$user) {
    echo "Post not found.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (!empty($title) && !empty($content)) {
        $query = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssi', $title, $content, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header('Location: index.php');
        } else {
            echo "Error updating post.";
        }
    } else {
        echo "Both fields are required!";
    }
}

?>

<html>
<head>
    <title>Edit Post</title>
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
    </style>
</head>
<body>
    <form method="post">
        <h2>Edit Post</h2>
        <label for="title">Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($user['title']) ?>" required>
        <label for="content">Content</label>
        <textarea name="content" rows="5" required><?= htmlspecialchars($user['content']) ?></textarea>
        <input type="submit" value="Update Post">
    </form>
</body>
</html>