<?php
include 'connect.php';
if (isset($_POST['room_name'])) {
    $room_name = $_POST['room_name'];
    $sql = "INSERT INTO room (room_name) VALUES ('$room_name')";
    if ($conn->query($sql) === TRUE) {
        echo "Room created successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
