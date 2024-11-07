<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['std_no'])) {
    header("Location: index.php");
    exit();
}

$std_no = $_SESSION['std_no'];

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="attendance_records.csv"');

$output = fopen("php://output", "w");

// Define the CSV header without 'Status'
fputcsv($output, ['ID', 'Student ID', 'Time In', 'Time Out', 'Log Date', 'Room', 'Subject', 'Instructor']);

// SQL query excluding 'status' column and applying filters
$sql = "SELECT `id`, `std_no`, `timein`, `timeout`, `logdate`, `room`, `subject`, `instructor` 
        FROM `attendance` 
        WHERE `std_no` = '$std_no'";

// Check if date filters are set and apply them to the query
if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $sql .= " AND `logdate` BETWEEN '$start_date' AND '$end_date'";
}

$result = $conn->query($sql);

// Fetch rows and write to CSV excluding 'status'
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
exit();
?>
