<?php
include 'connect.php';
if (isset($_POST['id']) && isset($_POST['room_name'])) {
    $id = $_POST['id'];
    $room_name = $_POST['room_name'];
    $sql = "UPDATE room SET room_name = '$room_name' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Room updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
