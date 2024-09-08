<?php
// index.php

require_once 'crud.php';

// Pagination settings
$limit = 3; // Number of posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the query based on search
if (!empty($search)) {
    $query = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $likeSearch = '%' . $search . '%';
    $stmt->bind_param('ssii', $likeSearch, $likeSearch, $limit, $offset);
} else {
    $query = "SELECT * FROM posts LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

// Get total number of posts for pagination
if (!empty($search)) {
    $countQuery = "SELECT COUNT(*) as total FROM posts WHERE title LIKE ? OR content LIKE ?";
    $countStmt = $conn->prepare($countQuery);
    $countStmt->bind_param('ss', $likeSearch, $likeSearch);
} else {
    $countQuery = "SELECT COUNT(*) as total FROM posts";
    $countStmt = $conn->prepare($countQuery);
}

$countStmt->execute();
$countResult = $countStmt->get_result();
$totalPosts = $countResult->fetch_assoc()['total'];

// Calculate total pages
$totalPages = ceil($totalPosts / $limit);
?>

<html>
<head>
    <title>CRUD APP with Search and Pagination</title>
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
            background-color:#0cebd8;
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
        .button {
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
        .button:hover {
            background-color: #0056b3;
        }
        .delete-button {
            background-color: #dc3545;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
        p {
            text-align: center;
        }
        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #0056b3;
        }
        .pagination .active {
            background-color: #0056b3;
            pointer-events: none;
        }
        .search-form {
            text-align: center;
            margin-bottom: 5px;
            margin-top: 20px;
        }

        .search-form input[type="text"] {
            width: 80%;
            max-width: 500px;
            padding: 10px 45px 10px 20px; /* Extra padding for the search icon */
            font-size: 16px;
            border-radius: 25px;
            border: 1px solid #ddd;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
            outline: none;
            transition: all 0.3s ease;
        }

        .search-form input[type="text"]:focus {
            border-color: #007BFF;
            box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.2);
        }

        .search-form input[type="text"]::placeholder {
            color: #aaa;
        }

        .search-form input[type="text"]:hover {
            box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.1);
        }

        .search-form button {
            position: absolute;
            right: 15%;
            padding: 10px;
            border: none;
            background: none;
            color: #007BFF;
            font-size: 18px;
            cursor: pointer;
        }

        .search-form button i {
            font-size: 18px;
        }

        .search-form button:hover {
            color: #0056b3;
        }

    </style>
</head>
<body>
    <h1>CRUD Application</h1>
    <div class="d-flex justify-content-between mb-3">
    <a href="create.php" class="button btn btn-primary ">Add Post</a>
    <a href="logout.php" class="button btn btn-secondary">Logout</a>
</div>

    <!-- Search Form -->
    <div class="search-form mt-3">
    <form method="get" style="position: relative; display: inline-block;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search posts by title or content">
        <button type="submit">
            <i class="bi bi-search"></i> <!-- Using Bootstrap's search icon -->
        </button>
    </form>
</div>


    <table border="1">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Created at</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($users)) { ?>
            <?php foreach ($users as $user) { ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['title']) ?></td>
                <td><?= htmlspecialchars($user['content']) ?></td>
                <td><?= $user['created_at'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $user['id'] ?>" class="button"><i class="bi bi-pencil-square"></i></a>
                    <a href="delete.php?id=<?= $user['id'] ?>" class="button delete-button"><i class="bi bi-trash3"></i></a>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5" style="text-align:center;">No posts found</td>
            </tr>
        <?php } ?>
    </table>

    <!-- Pagination Links -->
    <div class="pagination">
        <?php if ($totalPages > 1) { ?>
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php } ?>
        <?php } ?>
    </div>

    
</body>
</html>
