<?php
include 'connect.php';

if (isset($_POST['id'], $_POST['subject_name'])) {
    $id = $_POST['id'];
    $subject_name = $_POST['subject_name'];
    $query = "UPDATE subject SET subject_name='$subject_name' WHERE id=$id";
    $conn->query($query);
}

header("Location: subject.php");
?>
