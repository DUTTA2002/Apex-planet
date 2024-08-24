<?php
// index.php

require_once 'crud.php';

$users = getAllUsers();

?>

<html>
<head>
    <title>CRUD APP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        a.button {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px;
            font-size: 14px;
            color: white;
            background-color: #007BFF;
            border-radius: 4px;
            text-decoration: none;
            text-align: center;
        }
        a.button:hover {
            background-color: #0056b3;
        }
        a.delete-button {
            background-color: #dc3545;
        }
        a.delete-button:hover {
            background-color: #c82333;
        }
        p {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>CRUD APPLICATION</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Created at</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user) { ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['title'] ?></td>
            <td><?= $user['content'] ?></td>
            <td><?= $user['created_at'] ?></td>
            <td>
                <a href="edit.php?id=<?= $user['id'] ?>" class="button"><i class="bi bi-pencil-square"></i></a>
                <a href="delete.php?id=<?= $user['id'] ?>" class="button delete-button"><i class="bi bi-trash3"></i></a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <p><a href="create.php" class="button">Create New User</a></p>
    <p><a href="logout.php" class="button">Logout</a></p>
</body>
</html>