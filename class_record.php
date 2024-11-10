<?php 
include 'connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
</head>
<body>

<?php include 'navbar_teacher.php'; ?>

<div class="container mt-4">
    <h2 class="mb-3">Student Attendance Records</h2>
    
    <!-- Filter Section -->
    <form method="GET" action="">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-control" required>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a id="exportLink" href="export.php" class="btn btn-success">Export to CSV</a>
            </div>
        </div>
    </form>

    <!-- Table Section -->
    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Log Date</th>
                    <th>Room</th>
                    <th>Subject</th>
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include database connection
                include 'connect.php';
                $name = $_SESSION['full_name'];

                $sql = "SELECT `id`, `std_no`, `timein`, `timeout`, `logdate`, `status`, `room`, `subject`, `instructor` 
                        FROM `attendance` 
                        WHERE `instructor` = '$name'";

                if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                    $start_date = $_GET['start_date'];
                    $end_date = $_GET['end_date'];
                    $sql .= " AND `logdate` BETWEEN '$start_date' AND '$end_date'";
                }

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['std_no']}</td>
                                <td>{$row['timein']}</td>
                                <td>{$row['timeout']}</td>
                                <td>{$row['logdate']}</td>
                                <td>{$row['room']}</td>
                                <td>{$row['subject']}</td>
                                <td>{$row['instructor']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript to update export link with filter parameters -->
<script>
    document.querySelector('form').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent form submission to allow export link update
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const exportLink = document.getElementById('exportLink');
        
        // Update the export link with the date range as query parameters
        exportLink.href = `export.php?start_date=${startDate}&end_date=${endDate}`;
        
        this.submit(); // Submit form after updating export link
    });
</script>

</body>
</html>
