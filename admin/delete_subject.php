<?php
include 'connect.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM subject WHERE id=$id";
    $conn->query($query);
}
?>
