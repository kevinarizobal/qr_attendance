<?php
session_start();  // Start the session
include("connect.php");

// Handle login
if (isset($_POST['login'])) {
    $uname = $_POST['uname'];
    $password = md5($_POST['password']);  // Hash the password

    // Check if the student ID exists and password matches
    $login_sql = "SELECT * FROM `admin` WHERE `uname` = '$uname' AND `password` = '$password' AND `status` = 1";
    $result = $conn->query($login_sql);

    if ($result->num_rows > 0) {
        // If login is successful, fetch user data and store in session
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['uname'];
        $_SESSION['full_name'] = $user['name'];
        $_SESSION['login'] = true;
  
        // Redirect Student to dashboard
        echo "<script>alert('Login successful! Redirecting to admin dashboard...');</script>";
        echo "<script>window.location.href='dashboard.php';</script>";
        
    } else {
        // If login fails, show an error message
        echo "<script>alert('Invalid username or password.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAQR-Code</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .form-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: opacity 0.5s ease-in-out, visibility 0.5s;
        }
        .form-title {
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-custom {
            width: 100%;
        }
        .form-footer {
            text-align: center;
            margin-top: 20px;
        }
        .form-footer a {
            text-decoration: none;
            color: #007bff;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }

        /* Hidden forms for smooth transition */
        .hidden {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }
        .active {
            opacity: 1;
            visibility: visible;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>
    <!-- Login Form -->
    <div class="form-container active" id="loginForm">
        <h2 class="form-title">Login Admin</h2>
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="loginEmail" class="form-label">Username</label>
                <input type="text" name="uname" class="form-control" id="loginEmail" placeholder="Enter your Username" required>
            </div>
            <div class="mb-3">
                <label for="loginPassword" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary btn-custom">Login</button>
        </form>
    </div>



    <!-- Bootstrap 5 JS (Optional, but for full functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
