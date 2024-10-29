<?php
session_start();  // Start the session
include("connect.php");

// Handle registration
if (isset($_POST['register'])) {
    $std_no = $_POST['std_no'];
    $full_name = $_POST['full_name'];
    $password = md5($_POST['password']);  // Hash the password using md5 (for security, consider stronger hashing)

    // Check if the student ID number already exists in the database
    $check_sql = "SELECT * FROM `user` WHERE `std_no` = '$std_no'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Student ID already exists
        echo "<script>alert('Student ID already exists! Please use a different ID.');</script>";
    } else {
        // Insert the new user
        $sql = "INSERT INTO `user`(`std_no`, `name`, `password`) VALUES ('$std_no','$full_name','$password')";

        if ($conn->query($sql) === TRUE) {
            // After inserting into `user`, insert the `std_no` into `user_profile` table
            $profile_sql = "INSERT INTO `user_profile`(`std_no`) VALUES ('$std_no')";

            if ($conn->query($profile_sql) === TRUE) {
                echo "<script>alert('New record created successfully and profile initialized.');</script>";
                echo "<script>window.location.href='index.php';</script>";
            } else {
                echo "Error inserting into user_profile: " . $profile_sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle login
if (isset($_POST['login'])) {
    $std_no = $_POST['std_no'];
    $password = md5($_POST['password']);  // Hash the password
    $user_type = $_POST['user_type'];

    // Check if the student ID exists and password matches
    $login_sql = "SELECT * FROM `user` WHERE `std_no` = '$std_no' AND `password` = '$password' AND `status` = 1 AND `user_type`= '$user_type'";
    $result = $conn->query($login_sql);

    if ($result->num_rows > 0) {
        // If login is successful, fetch user data and store in session
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['std_no'] = $user['std_no'];
        $_SESSION['full_name'] = $user['name'];
        $_SESSION['user_type'] = $user['user_type'];

        // Redirect based on user type
        if ($user['user_type'] == 1) {
            // Redirect Student to dashboard
            echo "<script>alert('Login successful! Redirecting to student dashboard...');</script>";
            echo "<script>window.location.href='dashboard.php';</script>";
        } else if ($user['user_type'] == 2) {
            // Redirect Teacher to teacher dashboard
            echo "<script>alert('Login successful! Redirecting to teacher dashboard...');</script>";
            echo "<script>window.location.href='dashboard_teacher.php';</script>";
        }
    } else {
        // If login fails, show an error message
        echo "<script>alert('Invalid student ID or password.');</script>";
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
        <h2 class="form-title">Login</h2>
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="loginEmail" class="form-label">Student ID No.</label>
                <input type="number" name="std_no" class="form-control" id="loginEmail" placeholder="Enter your ID No." required>
            </div>
            <div class="mb-3">
                <label for="loginPassword" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Enter your password" required>
            </div>
            <div class="mb-3">
                <label for="loginUser" class="form-label">User Type</label>
                <select name="user_type" class="form-select">
                    <option value="1">Student</option>
                    <option value="2">Teacher</option>
                </select>
            </div>
            <button type="submit" name="login" class="btn btn-primary btn-custom">Login</button>
            <div class="form-footer">
                <p>Don't have an account? <a href="javascript:void(0)" onclick="switchToRegister()">Register here</a></p>
            </div>
        </form>
    </div>

    <!-- Register Form -->
    <div class="form-container hidden" id="registerForm">
        <h2 class="form-title">Register</h2>
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="registerUsername" class="form-label">Student ID No.</label>
                <input type="number" name="std_no" class="form-control" id="registerUsername" placeholder="Enter your ID No." required>
            </div>
            <div class="mb-3">
                <label for="registerEmail" class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" id="registerEmail" placeholder="Enter your Name" required>
            </div>
            <div class="mb-3">
                <label for="registerPassword" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="registerPassword" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="register" class="btn btn-primary btn-custom">Register</button>
            <div class="form-footer">
                <p>Already have an account? <a href="javascript:void(0)" onclick="switchToLogin()">Login here</a></p>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS (Optional, but for full functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript to handle form switch -->
    <script>
        function switchToRegister() {
            document.getElementById('loginForm').classList.remove('active');
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('registerForm').classList.remove('hidden');
            document.getElementById('registerForm').classList.add('active');
        }

        function switchToLogin() {
            document.getElementById('registerForm').classList.remove('active');
            document.getElementById('registerForm').classList.add('hidden');
            document.getElementById('loginForm').classList.remove('hidden');
            document.getElementById('loginForm').classList.add('active');
        }
    </script>
</body>
</html>
