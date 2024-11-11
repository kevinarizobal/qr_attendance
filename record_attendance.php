<?php
// Database configuration
$host = 'localhost';
$db = 'qr_attendance';
$user = 'root';
$pass = '';

// Get JSON input data
$data = json_decode(file_get_contents("php://input"), true);
$studentNumber = $data['studentNumber'];
$room = $data['room'];
$subject = $data['subject'];
$instructor = $data['instructor'];
$currentTime = date("Y-m-d H:i:s"); // Current date and time in DATETIME format

try {
    // Database connection
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the student exists in the user table
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE std_no = ?");
    $stmt->execute([$studentNumber]);
    $isRegistered = $stmt->fetchColumn();

    if (!$isRegistered) {
        echo json_encode(['status' => 'error', 'message' => 'Student not registered.']);
        exit;
    }

    // Check if the student already has attendance for today in the selected room and subject
    $stmt = $pdo->prepare("SELECT * FROM attendance WHERE std_no = ? AND logdate = CURDATE() AND room = ? AND subject = ?");
    $stmt->execute([$studentNumber, $room, $subject]);
    $attendanceRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($attendanceRecord) {
        // If attendance already exists, mark as 'out' and update the time out
        if (!$attendanceRecord['timeout']) {
            $stmt = $pdo->prepare("UPDATE attendance SET timeout = ?, status = 'Checked Out' WHERE id = ?");
            $stmt->execute([$currentTime, $attendanceRecord['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Attendance marked as checked out.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Attendance already recorded for today.']);
        }
    } else {
        // If no attendance exists, insert new record
        $stmt = $pdo->prepare("INSERT INTO attendance (std_no, timein, room, subject, instructor, logdate, status) VALUES (?, ?, ?, ?, ?, CURDATE(), 'Checked In')");
        $stmt->execute([$studentNumber, $currentTime, $room, $subject, $instructor]);
        echo json_encode(['status' => 'success', 'message' => 'Attendance marked as checked in.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
