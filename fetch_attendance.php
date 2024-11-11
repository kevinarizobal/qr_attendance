<?php
// Database configuration
$host = 'localhost';
$db = 'qr_attendance';
$user = 'root';
$pass = '';

// Connect to database
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch attendance records
$stmt = $pdo->prepare("SELECT * FROM attendance");
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the records as JSON
echo json_encode($records);
?>
