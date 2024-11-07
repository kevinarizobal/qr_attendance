<?php include 'connect.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management</title>
</head>
<body>
    <?php include 'navbar.php';?>
    <div class="container mt-5">
        <h2 align="center">Room Management</h2>

        <!-- Button to trigger create modal -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Add Room</button>

        <!-- DataTable -->
        <table id="roomTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'connect.php'; // Include your database connection here

                $sql = "SELECT * FROM room";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['room_name']}</td>
                            <td>
                                <button class='btn btn-info btn-sm' onclick='viewRoom({$row['id']})'>View</button>
                                <button class='btn btn-warning btn-sm' onclick='editRoom({$row['id']}, \"{$row['room_name']}\")'>Edit</button>
                                <button class='btn btn-danger btn-sm' onclick='deleteRoom({$row['id']})'>Delete</button>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createForm">
                        <div class="mb-3">
                            <label for="room_name" class="form-label">Room Name</label>
                            <input type="text" class="form-control" id="room_name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Room</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="mb-3">
                            <label for="edit_room_name" class="form-label">Room Name</label>
                            <input type="text" class="form-control" id="edit_room_name" required>
                            <input type="hidden" id="edit_room_id">
                        </div>
                        <button type="submit" class="btn btn-warning">Update Room</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            $('#roomTable').DataTable();
        });

        // Create room
        $('#createForm').on('submit', function(e) {
            e.preventDefault();
            var roomName = $('#room_name').val();
            $.ajax({
                url: 'create_room.php',
                type: 'POST',
                data: { room_name: roomName },
                success: function(response) {
                    $('#createModal').modal('hide');
                    location.reload(); // Reload page to show new room
                }
            });
        });

        // Edit room
        function editRoom(id, name) {
            $('#edit_room_id').val(id);
            $('#edit_room_name').val(name);
            $('#editModal').modal('show');
        }

        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            var roomId = $('#edit_room_id').val();
            var roomName = $('#edit_room_name').val();
            $.ajax({
                url: 'update_room.php',
                type: 'POST',
                data: { id: roomId, room_name: roomName },
                success: function(response) {
                    $('#editModal').modal('hide');
                    location.reload();
                }
            });
        });

        // View room (Simple alert for demonstration)
        function viewRoom(id) {
            $.ajax({
                url: 'view_room.php',
                type: 'GET',
                data: { id: id },
                success: function(response) {
                    alert(response); // For now, just show data in alert box
                }
            });
        }

        // Delete room
        function deleteRoom(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_room.php',
                        type: 'POST',
                        data: { id: id },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Your room has been deleted.',
                                'success'
                            );
                            location.reload();
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>
