<?php
include ("connect.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['std_no'])) {
    // If not, redirect to login page
    header("Location: index.php");
    exit();
}
$std_no =  $_SESSION['std_no'];
$name = $_SESSION['full_name'];
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .menu-item {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">STUDENT QR ATTENDANCE SYSTEM (TEACHER)</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- QR Code Setting Dropdown -->
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard_teacher.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="qrCodeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            QR Code Setting
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="qrCodeDropdown">
                            <li><a class="dropdown-item" href="class_record.php">Class Attendance Record</a></li>
                            <li><a class="dropdown-item" href="qr_scan.php">QR Code Scan</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex ms-auto">
                    <?php
                        if(isset($_SESSION['login']) && $_SESSION['login']==true && $_SESSION['user_type'] == '2')
            
                        {
                        echo<<<data
                            <button type="button" class="btn btn-outline-dark text-white shadow-none dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" aria-label="Toggle navigation">
                                $_SESSION[full_name]
                            </button>
                            <ul class="dropdown-menu dropdown-menu-lg-end">
                                <li><a class="dropdown-item" href="account_setting.php">Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                            </div>
                        data;
                        }
                    ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">

    </div>

    <!-- Bootstrap 5 JS (Optional, but for full functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
