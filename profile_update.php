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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $std_no = $_POST['std_no'];
    $gender = $_POST['gender'];
    $year = $_POST['year'];
    $course = $_POST['course'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    // Check if user profile exists
    $checkStmt = $conn->prepare("SELECT std_no FROM user_profile WHERE std_no = ?");
    $checkStmt->bind_param("s", $std_no);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Profile exists, proceed with update
        $stmt = $conn->prepare("UPDATE user_profile SET gender = ?, year = ?, course = ?, address = ?, email = ? WHERE std_no = ?");
        $stmt->bind_param("ssssss", $gender, $year, $course, $address, $email, $std_no);

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
