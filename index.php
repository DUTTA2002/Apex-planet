<?php
include "config.php";
session_start();

// Check if the user is logged in and has a role
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Admins can create and delete posts
    if ($role == 'admin') {
        if (isset($_POST['create'])) {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
            $stmt->bind_param("ss", $title, $content);
            $stmt->execute();
            $stmt->close();
        } elseif (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Both admins and editors can update posts
    if ($role == 'admin' || $role == 'editor') {
        if (isset($_POST['update'])) {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
            $stmt->bind_param("ssi", $title, $content, $id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Search and Pagination logic
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

// Fetch posts with search and pagination
$search_query = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? LIMIT ?, ?";
$stmt = $conn->prepare($search_query);
$search_term = '%' . $search . '%';
$stmt->bind_param("ssii", $search_term, $search_term, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$total_query = "SELECT COUNT(*) AS total FROM posts WHERE title LIKE ? OR content LIKE ?";
$stmt_total = $conn->prepare($total_query);
$stmt_total->bind_param("ss", $search_term, $search_term);
$stmt_total->execute();
$total_result = $stmt_total->get_result();
$total_posts = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $limit);

$stmt->close();
$stmt_total->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Application</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #7CF5FF;
            font-family: 'Arial', sans-serif;
        }
        .btn-custom {
            background-color: #1e90ff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-custom-danger {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            transition: background-color 0.3s;
        }
        .btn-custom-danger:hover {
            background-color: #c0392b;
        }
        .header-container {
            text-align: center;
            margin-bottom: 20px;
        }
        /* Styling the search box with an icon */
        .input-group .form-control {
            border-right: 0;
        }
        .input-group .input-group-append .btn {
            border-left: 0;
        }
    </style>
</head>
<body>
    
    <div class="container" style="margin-top: 2.5cm;">
        <div class="header-container">
            <h2 class="text-dark">CRUD Application</h2>
        </div>
        
        <div class="d-flex justify-content-center align-items-center mb-3">
            <div class="d-flex align-items-center">
                <form action="" method="get" class="mr-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <?php if ($role == 'admin'): ?>
                    <a href="add_post.php" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square"></i> Create Post
                    </a>
                <?php endif; ?>
            </div>
            
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span>Posts</span>
                    <button class="btn btn-custom" onclick="window.location.href='logout.php'" title="Logout">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($row['content']); ?></p>
                                    <small class="text-muted">Created at: <?php echo htmlspecialchars($row['created_at']); ?></small>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <button class="btn btn-custom btn-sm" 
                                        onclick="editDetails('<?php echo htmlspecialchars($row['id']); ?>', '<?php echo htmlspecialchars($row['title']); ?>', '<?php echo htmlspecialchars($row['content']); ?>')" 
                                        data-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <?php if ($role == 'admin'): ?>
                                    <form action="" method="post" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <button type="submit" name="delete" class="btn btn-custom-danger btn-sm" data-toggle="tooltip" title="Delete">
                                            <i class="bi bi-trash3"></i> Delete
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Pagination -->
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">
                        <div class="form-group">
                            <label for="editTitle">Title</label>
                            <input type="text" class="form-control" name="title" id="editTitle" required>
                        </div>
                        <div class="form-group">
                            <label for="editContent">Content</label>
                            <textarea class="form-control" name="content" id="editContent" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="update" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editDetails(id, title, content) {
            $('#editId').val(id);
            $('#editTitle').val(title);
            $('#editContent').val(content);
            $('#editModal').modal('show');
        }
    </script>
</body>
</html>
