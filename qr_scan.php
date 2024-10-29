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
            width: auto;
            max-height: 400px;
            border: 2px solid #0d6efd;
            border-radius: 0.5rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<?php include ("navbar.php");?>

<div class="container mt-5">
    <div class="content">
        <center><video id="interactive" class="mb-3"></video></center>

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
