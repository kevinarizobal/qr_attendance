<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Make sure to get the values sent from the frontend
    $id = $_POST['id'];
    $std_no = $_POST['std_no'];
    $name = $_POST['name'];
    $password = md5($_POST['password']); // Use a better hashing mechanism in production

    // Fixing the bind_param issue - We need 4 placeholders for 4 variables
    $stmt = $conn->prepare("UPDATE user SET std_no = ?, name = ?, password = ? WHERE id = ?");
    
    if ($stmt) {
        // Here we are binding all 4 parameters with their types
        $stmt->bind_param("sssi", $std_no, $name, $password, $id);
        
        if ($stmt->execute()) {
            // If the query is successful, return a success response
            echo json_encode(['success' => true]);
        } else {
            // If there's an error executing the query, return the error
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
    } else {
        // If there's an error preparing the query, return the error
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
}
?>
