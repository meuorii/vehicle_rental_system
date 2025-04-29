<?php
include '../includes/db_connect.php';
include '../includes/admin_sidebar.php'; 
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM vehicles WHERE id = $id");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edit Vehicle</title>
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
      background-color: #c9a72c;
    }
    .preview-img {
      width: 200px;
      height: auto;
      margin-top: 10px;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>

<div class="main-content">
  <div class="form-card">
    <h2 class="mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Vehicle</h2>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Brand</label>
        <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($row['brand']) ?>" required>
      </div>
      <div class="mb-3">
        <label>Model</label>
        <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($row['model']) ?>" required>
      </div>
      <div class="mb-3">
        <label>Plate Number</label>
        <input type="text" name="plate_number" class="form-control" value="<?= htmlspecialchars($row['plate_number']) ?>" required>
      </div>
      <div class="mb-3">
        <label>Daily Rate (₱)</label>
        <input type="number" name="daily_rate" step="0.01" class="form-control" value="<?= $row['daily_rate'] ?>" required>
      </div>
      <div class="mb-3">
        <label>Current Image</label><br>
        <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" class="preview-img"><br>
        <label class="form-label mt-2">Upload New Image (optional)</label>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>
      <div class="d-flex gap-3">
        <button type="submit" name="update" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
        <a href="list.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle me-1"></i> Cancel</a>
      </div>
    </form>

    <?php
    if (isset($_POST['update'])) {
      $brand = $_POST['brand'];
      $model = $_POST['model'];
      $plate = $_POST['plate_number'];
      $rate  = $_POST['daily_rate'];

      $newImage = $_FILES['image']['name'];
      $tmp = $_FILES['image']['tmp_name'];
      $upload_dir = "../uploads/";

      if (!empty($newImage)) {
        $target_file = $upload_dir . basename($newImage);
        move_uploaded_file($tmp, $target_file);
        $conn->query("UPDATE vehicles SET brand='$brand', model='$model', plate_number='$plate', daily_rate=$rate, image='$newImage' WHERE id=$id");
      } else {
        $conn->query("UPDATE vehicles SET brand='$brand', model='$model', plate_number='$plate', daily_rate=$rate WHERE id=$id");
      }

      echo "<script>
        alert('Vehicle updated successfully!');
        window.location.href = 'list.php';
      </script>";
      exit();
    }
    ?>
  </div>
</div>

</body>
</html>
