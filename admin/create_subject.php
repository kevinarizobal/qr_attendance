<?php
include 'connect.php';

if (isset($_POST['subject_name'])) {
    $subject_name = $_POST['subject_name'];
    $query = "INSERT INTO subject (subject_name) VALUES ('$subject_name')";
    $conn->query($query);
}

header("Location: subject.php");
?>
