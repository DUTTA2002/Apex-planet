<?php
// index.php

require_once 'crud.php';

$users = getAllUsers();

?>

<html>
<head>
    <title>CRUD App</title>
</head>
<body>
    <h1>CRUD App</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user) { ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['username'] ?></td>
           
            <td>
                <a href="edit.php?id=<?= $user['id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $user['id'] ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <p><a href="create.php">Create New User</a></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>