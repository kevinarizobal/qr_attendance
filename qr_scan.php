<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 0 0 10px 10px;
        }
        .content {
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        #interactive {
            width: 100%;
            max-height: 400px;
            border: 2px solid #0d6efd;
            border-radius: 0.5rem;
            margin-bottom: 15px;
        }
        .form-select {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 8px rgba(13, 110, 253, 0.25);
        }
        .alert {
            border-radius: 0.5rem;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
        #error-message {
            font-weight: bold;
            color: #dc3545;
        }
        .btn-outline-primary {
            transition: all 0.3s ease;
        }
        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: #fff;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="qrCodeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        QR Code Setting
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="qrCodeDropdown">
                        <li><a class="dropdown-item" href="qr_code.php">Generate QR Code</a></li>
                        <li><a class="dropdown-item" href="#qrCodeHistory">QR Code History</a></li>
                        <li><a class="dropdown-item" href="qr_scan.php">QR Code Scan</a></li>
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
    <div class="content">
        <video id="interactive" class="mb-3"></video>

        <!-- Dropdowns for Room, Subject, and Instructor -->
        <div class="mb-3">
            <label for="roomSelect" class="form-label">Select Room</label>
            <select id="roomSelect" class="form-select">
                <option value="">Choose a room</option>
                <option value="Room A">Room A</option>
                <option value="Room B">Room B</option>
                <option value="Room C">Room C</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="subjectSelect" class="form-label">Select Subject</label>
            <select id="subjectSelect" class="form-select">
                <option value="">Choose a subject</option>
                <option value="Mathematics">Mathematics</option>
                <option value="Science">Science</option>
                <option value="History">History</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="instructorSelect" class="form-label">Select Instructor</label>
            <select id="instructorSelect" class="form-select">
                <option value="">Choose an instructor</option>
                <option value="Instructor A">Instructor A</option>
                <option value="Instructor B">Instructor B</option>
                <option value="Instructor C">Instructor C</option>
            </select>
        </div>

        <div id="result-container" class="alert alert-info" style="display:none;">
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
                    <th>Room</th>
                    <th>Subject</th>
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody id="attendance-table-body" class="text-center">
                <!-- Attendance records will be dynamically inserted here -->
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
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
                                <td>${record.room}</td>
                                <td>${record.subject}</td>
                                <td>${record.instructor}</td>
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
        const selectedRoom = $('#roomSelect').val();
        const selectedSubject = $('#subjectSelect').val();
        const instructorName = $('#instructorSelect').val();

        if (!selectedRoom || !selectedSubject || !instructorName) {
            $('#error-message').text('Please select room, subject, and instructor before scanning.');
            return;
        }

        $.ajax({
            url: 'record_attendance.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                studentNumber: studentNumber,
                room: selectedRoom,
                subject: selectedSubject,
                instructor: instructorName
            }),
            success: function(response) {
                $('#error-message').text('');
                fetchAttendanceRecords();
            },
            error: function() {
                $('#error-message').text('Failed to record attendance.');
            }
        });
    }
</script>

</body>
</html>
