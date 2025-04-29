<?php include '../includes/db_connect.php'; ?>
<?php include '../includes/admin_sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add Vehicle</title>
  <meta charset="utf-8" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
      color: #fff;
    }
    .main-content {
      margin-left: 260px;
      padding: 40px;
    }
    .form-card {
      background-color: #1e1e2f;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }
    label {
      color: #d4af37;
    }
    .btn-primary {
      background-color: #d4af37;
      border: none;
    }
    .btn-primary:hover {
      background-color: #bfa52c;
    }
  </style>
</head>
<body>

<div class="main-content">
  <div class="form-card">
    <h2 class="mb-4"><i class="bi bi-car-front-fill me-2"></i>Add New Vehicle</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Brand</label>
        <input type="text" name="brand" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Model</label>
        <input type="text" name="model" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Plate Number</label>
        <input type="text" name="plate_number" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Daily Rate (₱)</label>
        <input type="number" name="daily_rate" step="0.01" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Upload Image</label>
        <input type="file" name="image" accept="image/*" class="form-control" required>
      </div>
      <div class="d-flex gap-3">
        <button type="submit" name="save" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Vehicle</button>
        <a href="list.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle me-1"></i> Back to List</a>
      </div>
    </form>

    <?php
    if (isset($_POST['save'])) {
      $brand = $_POST['brand'];
      $model = $_POST['model'];
      $plate = $_POST['plate_number'];
      $rate  = $_POST['daily_rate'];

      $image = $_FILES['image']['name'];
      $tmp = $_FILES['image']['tmp_name'];
      $upload_dir = "../uploads/";

      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      $target_file = $upload_dir . basename($image);
      if (move_uploaded_file($tmp, $target_file)) {
        $conn->query("INSERT INTO vehicles (brand, model, plate_number, daily_rate, image)
                      VALUES ('$brand', '$model', '$plate', $rate, '$image')");
        echo "<div class='alert alert-success mt-3'><i class='bi bi-check-circle me-2'></i>Vehicle added successfully!</div>";
      } else {
        echo "<div class='alert alert-danger mt-3'><i class='bi bi-x-circle me-2'></i>Failed to upload image.</div>";
      }
    }
    ?>
  </div>
</div>

</body>
</html>
