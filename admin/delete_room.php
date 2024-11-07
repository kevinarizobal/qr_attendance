<?php
include 'connect.php';
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM room WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Room deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
