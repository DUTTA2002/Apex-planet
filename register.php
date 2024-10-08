<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username, password, and role from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $role);

    // Execute the query
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #7CF5FF;
            color: #343a40;
        }
        .card-custom {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            width: 10cm;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #4CAF50;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #45a049;
        }
        .text-custom {
            color: #0cb1ed;
        }
        .form-control-custom {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            color: #495057;
        }
        .form-control-custom:focus {
            border-color: #007bff;
            box-shadow: none;
        }
        a {
            text-decoration: none;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #0056b3;
        }
        h1{
    font-family: Georgia, 'Times New Roman', Times, serif;
    text-align: center;
    color: #0ea3e8;
    margin-bottom: 2px;

}
h6{
    margin-top: 2px;
    text-align: end;
    margin-right: 5px;
}
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card-custom rounded-4 p-4">
        <h1>Apex Planet</h1>
        <h6>Software Pvt Ltd.</h6>
            <div class="card-body text-center">
                <h2 class="card-title mb-4 text-custom">Register</h2>
                <form method="POST">
                    <div class="mb-3 text-start">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control form-control-custom" id="username" name="username"  required>
                    </div>
                    <div class="mb-3 text-start">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control form-control-custom" id="password" name="password" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label for="role" class="form-label">Role:</label>
                        <select class="form-control form-control-custom" id="role" name="role" required>
                            <option value=""disabled selected>Select your role</option>
                            <option value="admin">Admin</option>
                            <option value="editor">Editor</option>
                        </select>
                    </div>
                    <button class="btn btn-custom w-100 py-2 text-white" type="submit">Sign up</button>
                    <p class="mt-3">Already have an account? <a class="text-custom" href="login.php">Login Here</a></p>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
