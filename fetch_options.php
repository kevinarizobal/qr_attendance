<?php
include 'connect.php';

// Fetch Rooms
$roomsQuery = "SELECT id, room_name FROM room";
$roomsResult = $conn->query($roomsQuery);

// Fetch Subjects
$subjectsQuery = "SELECT id, subject_name FROM subject";
$subjectsResult = $conn->query($subjectsQuery);

// Fetch Instructors (Assuming user_type = 2 is for instructors)
$instructorsQuery = "SELECT id, name FROM user WHERE user_type = 2";
$instructorsResult = $conn->query($instructorsQuery);
?>

<!-- HTML for Room Select -->
<div class="mb-3">
    <label for="roomSelect" class="form-label">Select Room</label>
    <select id="roomSelect" class="form-select">
        <option value="">Choose a room</option>
        <?php
        if ($roomsResult->num_rows > 0) {
            while ($row = $roomsResult->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['room_name'] . "</option>";
            }
        }
        ?>
    </select>
</div>

<!-- HTML for Subject Select -->
<div class="mb-3">
    <label for="subjectSelect" class="form-label">Select Subject</label>
    <select id="subjectSelect" class="form-select">
        <option value="">Choose a subject</option>
        <?php
        if ($subjectsResult->num_rows > 0) {
            while ($row = $subjectsResult->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['subject_name'] . "</option>";
            }
        }
        ?>
    </select>
</div>

<!-- HTML for Instructor Select -->
<div class="mb-3">
    <label for="instructorSelect" class="form-label">Select Instructor</label>
    <select id="instructorSelect" class="form-select">
        <option value="">Choose an instructor</option>
        <?php
        if ($instructorsResult->num_rows > 0) {
            while ($row = $instructorsResult->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        }
        ?>
    </select>
</div>
