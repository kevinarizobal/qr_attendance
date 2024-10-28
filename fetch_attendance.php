<?php
// Database configuration
$host = 'localhost';
$db = 'qr_attendance';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT `id`, `std_no`, `timein`, `timeout`, `logdate`, `status` FROM `attendance`");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($records);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
