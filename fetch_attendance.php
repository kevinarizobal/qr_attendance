<?php
session_start();

// Redirect to login if 'std_no' session variable is not set
if (!isset($_SESSION['std_no'])) {
    header("Location: index.php");
    exit();
}

// Store 'std_no' session variable in a local variable for query use
$std_no = $_SESSION['std_no'];

// Database configuration
$host = 'localhost';
$db = 'qr_attendance';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the query to select records for the logged-in student
    $stmt = $pdo->prepare("SELECT `id`, `std_no`, `timein`, `timeout`, `logdate`, `status`, `room`, `subject`, `instructor` 
                           FROM `attendance` 
                           WHERE `std_no` = :std_no");

    // Execute the query with the student's std_no
    $stmt->execute(['std_no' => $std_no]);

    // Fetch all matching records and encode them to JSON format
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($records);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
