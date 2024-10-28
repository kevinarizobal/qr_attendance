<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

        .viewport {
            width: 100%;
            height: auto;
            border: 2px solid #0d6efd;
            border-radius: 0.5rem;
            margin-bottom: 15px;
            margin: 0 auto; /* Center the element */
            margin-bottom: 15px; /* Maintain bottom margin */
        }
        #error-message {
            color: red;
            font-weight: bold;
        }
        table {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        th, td {
            vertical-align: middle;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        body {
            background-color: #f7f7f7;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .menu-item {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- QR Code Setting Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="qrCodeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        QR Code Setting
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="qrCodeDropdown">
                        <li><a class="dropdown-item" href="qr_code.php">Generate QR Code</a></li>
                        <li><a class="dropdown-item" href="#qrCodeHistory">QR Code History</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#accountManagement">Account Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <video id="interactive" class="viewport"></video>
    <div id="result-container" style="display:none;">
        <h4>Scanned Result:</h4>
        <p id="result" class="lead"></p>
    </div>
    <p id="error-message" class="text-danger"></p>

    <h3 class="mt-4">Attendance Records</h3>
    <table class="table table-striped table-hover table-bordered mt-3">
        <thead class="table-light text-center">
            <tr>
                <th>ID</th>
                <th>Student Number</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="attendance-table-body" class="text-center">
            <!-- Attendance records will be dynamically inserted here -->
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<!-- Bootstrap 5 JS (Optional, but for full functionality) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

    scanner.addListener('scan', function (content) {
        document.getElementById("result").innerText = content;
        document.querySelector("#result-container").style.display = '';
        recordAttendance(content);
    });

    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            document.getElementById('error-message').innerText = 'No cameras found.';
        }
    }).catch(function (err) {
        document.getElementById('error-message').innerText = 'Camera access error: ' + err;
    });

    // Fetch attendance records on page load
    $(document).ready(function() {
        fetchAttendanceRecords();
    });

    function fetchAttendanceRecords() {
        $.ajax({
            url: 'fetch_attendance.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    $('#error-message').text(data.error);
                } else {
                    $('#attendance-table-body').empty();
                    data.forEach(record => {
                        $('#attendance-table-body').append(`
                            <tr>
                                <td>${record.id}</td>
                                <td>${record.std_no}</td>
                                <td>${record.timein}</td>
                                <td>${record.timeout ? record.timeout : '---'}</td>
                                <td>${record.logdate}</td>
                                <td>${record.status}</td>
                            </tr>
                        `);
                    });
                }
            },
            error: function() {
                $('#error-message').text('Failed to fetch attendance records.');
            }
        });
    }

    function recordAttendance(studentNumber) {
        $.ajax({
            url: 'record_attendance.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ studentNumber: studentNumber }),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    fetchAttendanceRecords();
                } else {
                    $('#error-message').text(response.error || 'Attendance recording failed.');
                }
            },
            error: function() {
                $('#error-message').text('Failed to record attendance.');
            }
        });
    }
</script>

</body>
</html>
