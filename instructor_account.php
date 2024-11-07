<?php include 'connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Account Management</title>

</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container my-4">
    <h2 class="text-center">Instructor Account Management</h2>
    
    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create Instructor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createForm">
                        <div class="mb-3">
                            <label for="std_no" class="form-label">Student Number</label>
                            <input type="text" class="form-control" id="std_no" name="std_no" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="createBtn">Create Instructor</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- DataTable -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Add Instructor</button>
    <table id="instructorTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Student Number</th>
                <th>Name</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT * FROM user WHERE user_type = 2");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<tr data-id='{$row['id']}'>
                    <td>{$row['std_no']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['created']}</td>
                    <td>
                        <button class='btn btn-primary btn-sm editBtn'>Edit</button>
                        <button class='btn btn-danger btn-sm deleteBtn'>Delete</button>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Instructor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="mb-3">
                        <label for="edit_std_no" class="form-label">Student Number</label>
                        <input type="text" class="form-control" id="edit_std_no" name="std_no" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="edit_password" name="password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="editBtn">Update Instructor</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Initialize DataTable
    $('#instructorTable').DataTable();

    // Create Instructor
    $('#createBtn').on('click', function () {
        let formData = $('#createForm').serialize();
        $.post('create.php', formData, function (response) {
            if (response.success) {
                $('#createModal').modal('hide');
                location.reload();
            } else {
                alert('Error creating instructor');
            }
        }, 'json');
    });

    // Edit Instructor
    $(document).on('click', '.editBtn', function () {
        const id = $(this).closest('tr').data('id');
        $.get('get_instructor.php', { id: id }, function (data) {
            $('#edit_std_no').val(data.std_no);
            $('#edit_name').val(data.name);
            $('#edit_password').val(data.password);
            $('#edit_status').val(data.status);
            $('#editBtn').data('id', data.id);
            $('#editModal').modal('show');
        }, 'json');
    });

    $('#editBtn').on('click', function () {
        const id = $(this).data('id');
        const formData = $('#editForm').serialize() + '&id=' + id;
        $.post('update.php', formData, function (response) {
            if (response.success) {
                $('#editModal').modal('hide');
                location.reload();
            } else {
                alert('Error updating instructor');
            }
        }, 'json');
    });

    // Delete Instructor
    $(document).on('click', '.deleteBtn', function () {
        const id = $(this).closest('tr').data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('delete.php', { id: id }, function (response) {
                    if (response.success) {
                        Swal.fire('Deleted!', 'Instructor has been deleted.', 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error!', 'There was an issue deleting the instructor.', 'error');
                    }
                }, 'json');
            }
        });
    });
});
</script>

</body>
</html>
