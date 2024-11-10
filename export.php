<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['std_no'])) {
    header("Location: index.php");
    exit();
}

$std_no = $_SESSION['std_no'];
$name = $_SESSION['full_name'];

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="attendance_records.csv"');

$output = fopen("php://output", "w");

// Define the CSV header without 'Status'
fputcsv($output, ['ID', 'Student ID', 'Time In', 'Time Out', 'Log Date', 'Room', 'Subject', 'Instructor']);

// Prepare SQL query to filter by instructor's name
$sql = "SELECT `id`, `std_no`, `timein`, `timeout`, `logdate`, `room`, `subject`, `instructor` 
        FROM `attendance` 
        WHERE `instructor` = ?";

// Add date filtering if both dates are provided
if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
    $sql .= " AND `logdate` BETWEEN ? AND ?";
}

$stmt = $conn->prepare($sql);

// Bind parameters based on whether dates are provided
if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $stmt->bind_param("sss", $name, $start_date, $end_date);
} else {
    $stmt->bind_param("s", $name);
}

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

// Fetch rows and write to CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'], 
        $row['std_no'], 
        $row['timein'], 
        $row['timeout'], 
        $row['logdate'], 
        $row['room'], 
        $row['subject'], 
        $row['instructor']
    ]);
}

fclose($output);
$stmt->close();
$conn->close();
exit();
?>
