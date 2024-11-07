<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subject Management</title>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h2 align="center">Subject Management</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Add Subject</button>
    <table id="subjectTable" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM subject";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['subject_name']}</td>
                        <td>
                            <button class='btn btn-info viewBtn' data-id='{$row['id']}'>View</button>
                            <button class='btn btn-warning editBtn' data-id='{$row['id']}'>Edit</button>
                            <button class='btn btn-danger deleteBtn' data-id='{$row['id']}'>Delete</button>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modals -->
<?php include 'modals.php'; ?>

<script>
    $(document).ready(function() {
        $('#subjectTable').DataTable();

        // Handle view button click
        $('.viewBtn').on('click', function() {
            var id = $(this).data('id');
            $.get('fetch_subject.php', { id: id }, function(data) {
                $('#viewSubjectName').text(data.subject_name);
                $('#viewModal').modal('show');
            }, 'json');
        });

        // Handle edit button click
        $('.editBtn').on('click', function() {
            var id = $(this).data('id');
            $.get('fetch_subject.php', { id: id }, function(data) {
                $('#editId').val(data.id);
                $('#editSubjectName').val(data.subject_name);
                $('#editModal').modal('show');
            }, 'json');
        });

        // Handle delete button click
        $('.deleteBtn').on('click', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('delete_subject.php', { id: id }, function() {
                        location.reload();
                    });
                }
            });
        });
    });
</script>

</body>
</html>
