<?php
// Database configuration
$host = 'localhost';
$db = 'qr_attendance';
$user = 'root';
$pass = '';

$data = json_decode(file_get_contents("php://input"), true);
$studentNumber = $data['studentNumber'];
$room = $data['room'];
$subject = $data['subject'];
$instructor = $data['instructor'];
$currentTime = date("h:i:s A"); // Current time in 12-hour format without date
$currentDate = date("Y-m-d"); // Current date

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the student exists in the user table
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE std_no = ?");
    $stmt->execute([$studentNumber]);
    $isRegistered = $stmt->fetchColumn();

    if (!$isRegistered) {
        echo json_encode(["status" => "error", "message" => "Student is not registered."]);
        exit;
    }

    // Check if the student already has a timein record for today with room and subject
    $stmt = $pdo->prepare("SELECT id, DATE_FORMAT(timein, '%h:%i:%s %p') AS timein, DATE_FORMAT(timeout, '%h:%i:%s %p') AS timeout FROM attendance WHERE std_no = ? AND logdate = ? AND room = ? AND subject = ?");
    $stmt->execute([$studentNumber, $currentDate, $room, $subject]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($record) {
        if (is_null($record['timeout'])) {
            $stmt = $pdo->prepare("UPDATE attendance SET timeout = ? WHERE id = ?");
            $stmt->execute([$currentTime, $record['id']]);
            echo json_encode(["status" => "success", "message" => "Timeout recorded."]);
        } else {
            echo json_encode(["status" => "info", "message" => "Attendance already complete for today."]);
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO attendance (std_no, timein, logdate, status, room, subject, instructor) VALUES (?, ?, ?, 'Present', ?, ?, ?)");
        $stmt->execute([$studentNumber, $currentTime, $currentDate, $room, $subject, $instructor]);
        echo json_encode(["status" => "success", "message" => "Timein recorded."]);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
