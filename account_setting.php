<?php include 'connect.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Profile Management</title>
  <style>
    .profile-image-preview {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
    }
  </style>
</head>
<body>
  <?php include 'navbar.php';?>
  <div class="container my-5">
    <h2 class="mb-4">Account Profile Management</h2>
    <form action="profile_update.php" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                  <label for="std_no" class="form-label">Student Number</label>
                  <input type="text" class="form-control" id="std_no" value="<?php echo $std_no; ?>" name="std_no" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                  <label for="gender" class="form-label">Gender</label>
                  <select class="form-select" id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                  <label for="year" class="form-label">Year</label>
                  <input type="number" class="form-control" id="year" name="year" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                  <label for="course" class="form-label">Course</label>
                  <input type="text" class="form-control" id="course" name="course" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                  <label for="image" class="form-label">Profile Image</label>
                  <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                  <img id="imagePreview" class="profile-image-preview mt-3" src="default-profile.png" alt="Profile Preview">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" required></textarea>
                </div>
            </div>
        </div>
      <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
  </div>

  <script>
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function() {
        const output = document.getElementById('imagePreview');
        output.src = reader.result;
      }
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>
</body>
</html>
