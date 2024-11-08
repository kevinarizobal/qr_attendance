<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qr_attendance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the uploads folder exists
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $std_no = $_POST['std_no'];
    $gender = $_POST['gender'];
    $year = $_POST['year'];
    $course = $_POST['course'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    // Handle image upload
    $imagePath = 'default-profile.png'; // Default image if none uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Validate file type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] <= $maxSize) {
            $imagePath = $uploadDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            echo "Invalid file type or file too large.";
        }
    }

    // Check if user profile exists
    $checkStmt = $conn->prepare("SELECT std_no FROM user_profile WHERE std_no = ?");
    $checkStmt->bind_param("s", $std_no);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Profile exists, proceed with update
        $stmt = $conn->prepare("UPDATE user_profile SET gender = ?, year = ?, course = ?, address = ?, email = ?, image = ? WHERE std_no = ?");
        $stmt->bind_param("sssssss", $gender, $year, $course, $address, $email, $imagePath, $std_no);

        if ($stmt->execute()) {
            echo "Profile updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Profile not found for the provided student number.";
    }

    $checkStmt->close();
}

$conn->close();
?>
