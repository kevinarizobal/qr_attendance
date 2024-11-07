<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM subject WHERE id=$id";
    $result = $conn->query($query);
    echo json_encode($result->fetch_assoc());
}
?>
