<?php
// Database configuration
$host = 'localhost';
$db = 'qr_attendance';
$user = 'root';
$pass = '';

$data = json_decode(file_get_contents("php://input"), true);
$studentNumber = $data['studentNumber'];
$currentTime = date("Y-m-d H:i:s"); // Current date and time
$currentDate = date("Y-m-d"); // Current date

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the student already has a timein record for today
    $stmt = $pdo->prepare("SELECT id, timein, timeout FROM attendance WHERE std_no = ? AND logdate = ?");
    $stmt->execute([$studentNumber, $currentDate]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($record) {
        // If a timein record exists but no timeout, update the timeout
        if (is_null($record['timeout'])) {
            $stmt = $pdo->prepare("UPDATE attendance SET timeout = ? WHERE id = ?");
            $stmt->execute([$currentTime, $record['id']]);
            echo json_encode(["status" => "success", "message" => "Timeout recorded."]);
        } else {
            // If both timein and timeout are already set, skip updating
            echo json_encode(["status" => "info", "message" => "Attendance already complete for today."]);
        }
    } else {
        // If no timein record exists, insert a new one with timein
        $stmt = $pdo->prepare("INSERT INTO attendance (std_no, timein, logdate, status) VALUES (?, ?, ?, 'Present')");
        $stmt->execute([$studentNumber, $currentTime, $currentDate]);
        echo json_encode(["status" => "success", "message" => "Timein recorded."]);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
