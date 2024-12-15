<?php 
include("connect.php");
include("navbar.php");


// Check if the user is logged in
if (!isset($_SESSION['std_no'])) {
    // If not, redirect to login page
    header("Location: index.php");
    exit();
}

$std_no = $_SESSION['std_no'];

// Fetch the year_level of the logged-in user
$query = "SELECT `year` FROM `user_profile` WHERE `std_no` = '$std_no'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $year_level = $user['year'];
} else {
    // Handle case where user data isn't found
    echo "User profile not found.";
    exit();
}

// Fetch the prospectus data based on the year_level
$query_prospectus = "SELECT `course_code`, `description`, `semester`, `year_level`, `lecture_hours`, `laboratory_hours`, `units`, `pre_requisite` FROM `tbl_prospectus` WHERE `year_level` = '$year_level' AND `semester` = 1";
$prospectus_result = mysqli_query($conn, $query_prospectus);

?>

<div class="container mt-5">
    <div class="row">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3>DASHBOARD STUDENT SCHEDULE</h3>
        </div>
    </div>

        <!-- Display the data table -->
        <table class="table table-striped">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Description</th>
                <th>Semester</th>
                <th>Year Level</th>
                <th>Lecture Hours</th>
                <th>Laboratory Hours</th>
                <th>Units</th>
                <th>Pre-requisite</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($prospectus_result && mysqli_num_rows($prospectus_result) > 0) {
                while ($row = mysqli_fetch_assoc($prospectus_result)) {
                    echo "<tr>";
                    echo "<td>" . $row['course_code'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>" . $row['semester'] . "</td>";
                    echo "<td>" . $row['year_level'] . "</td>";
                    echo "<td>" . $row['lecture_hours'] . "</td>";
                    echo "<td>" . $row['laboratory_hours'] . "</td>";
                    echo "<td>" . $row['units'] . "</td>";
                    echo "<td>" . $row['pre_requisite'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No data available for this year level.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
