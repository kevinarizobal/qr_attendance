<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'qr_attendance';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    // Check if a file is uploaded
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        $fileName = $_FILES['file']['tmp_name'];
        $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        // Validate file extension (CSV or Excel)
        if (in_array($fileExtension, ['csv', 'xlsx'])) {
            require_once 'vendor/autoload.php'; // For handling Excel files (using PhpSpreadsheet)

            // If CSV file
            if ($fileExtension === 'csv') {
                $file = fopen($fileName, 'r');
                fgetcsv($file); // Skip header row

                while (($row = fgetcsv($file)) !== false) {
                    $course_code = $conn->real_escape_string($row[0]);
                    $description = $conn->real_escape_string($row[1]);
                    $semester = $conn->real_escape_string($row[2]);
                    $year_level = $conn->real_escape_string($row[3]);
                    $lecture_hours = $conn->real_escape_string($row[4]);
                    $laboratory_hours = $conn->real_escape_string($row[5]);
                    $units = $conn->real_escape_string($row[6]);
                    $pre_requisite = $conn->real_escape_string($row[7]);

                    $sql = "INSERT INTO tbl_prospectus (`course_code`, `description`, `semester`, `year_level`, `lecture_hours`, `laboratory_hours`, `units`, `pre_requisite`) 
                            VALUES ('$course_code', '$description', '$semester', '$year_level', '$lecture_hours', '$laboratory_hours', '$units', '$pre_requisite')";

                    $conn->query($sql);
                }
                fclose($file);
            } 
            echo "<div class='alert alert-success'>Data successfully imported.</div>";
        } else {
            echo "<div class='alert alert-danger'>Invalid file format. Please upload a CSV or Excel file.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>No file uploaded.</div>";
    }
}

// Fetch data from the database
$sql = "SELECT course_code, description, semester, year_level, lecture_hours, laboratory_hours, units, pre_requisite FROM tbl_prospectus";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
</head>
<body>
    <?php include 'navbar.php';?>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Upload CSV or Excel File</h2>
        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="file" class="form-label">Choose File</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".csv, .xlsx" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Upload and Import</button>
                </form>
            </div>
        </div>

        <h2 class="mb-4 text-center">Data Table</h2>
        <div class="table-responsive">
            <table id="data-table" class="table table-striped table-bordered">
                <thead class="table-dark">
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
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['course_code']); ?></td>
                                <td><?= htmlspecialchars($row['description']); ?></td>
                                <td><?= htmlspecialchars($row['semester']); ?></td>
                                <td><?= htmlspecialchars($row['year_level']); ?></td>
                                <td><?= htmlspecialchars($row['lecture_hours']); ?></td>
                                <td><?= htmlspecialchars($row['laboratory_hours']); ?></td>
                                <td><?= htmlspecialchars($row['units']); ?></td>
                                <td><?= htmlspecialchars($row['pre_requisite']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">No data found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#data-table').DataTable();
        });
    </script>
</body>
</html>
