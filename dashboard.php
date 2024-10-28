<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['std_no'])) {
    // If not, redirect to login page
    header("Location: index.php");
    exit();
}
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
            <a class="navbar-brand" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- QR Code Setting Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="qrCodeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            QR Code Setting
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="qrCodeDropdown">
                            <li><a class="dropdown-item" href="qr_code.php">Generate QR Code</a></li>
                            <li><a class="dropdown-item" href="#qrCodeHistory">QR Code History</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#accountManagement">Account Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Welcome message -->
        <div class="content">
            <h3>Welcome, <?php echo $_SESSION['full_name']; ?>!</h3>
            <p>This is your dashboard where you can manage your account settings and configure QR codes.</p>
        </div>

        <!-- QR Code Setting -->
        <div class="content menu-item" id="qrCodeSetting">
            <h4>QR Code Setting</h4>
            <p>Manage your QR code settings here. This section will allow you to generate, view, or edit QR codes associated with your account.</p>
        </div>

        <!-- Account Management -->
        <div class="content menu-item" id="accountManagement">
            <h4>Account Management</h4>
            <p>Update your account information such as name, email, or password.</p>
        </div>
    </div>

    <!-- Bootstrap 5 JS (Optional, but for full functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
