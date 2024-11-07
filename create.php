<?php
include ('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $std_no = $conn->real_escape_string($_POST['std_no']);
    $name = $conn->real_escape_string($_POST['name']);
    $password = md5($_POST['password']);

    // Prepare the query
    $stmt = $conn->prepare("INSERT INTO user (std_no, name, password, status, user_type) VALUES (?, ?, ?, 1, 2)");

    if ($stmt) {
        // Bind parameters and execute
        $stmt->bind_param("sss", $std_no, $name, $password);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare statement']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
