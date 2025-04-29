<?php include '../includes/db_connect.php'; ?>
<?php include '../includes/admin_sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Vehicle List</title>
  <meta charset="utf-8" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .vehicle-img {
      width: 200px;
      height: auto;
      margin-bottom: 10px;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }
    .main-content {
      margin-left: 260px;
      padding: 30px;
    }
  </style>
</head>
<body class="bg-dark text-white">

<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-car-front-fill me-2"></i>Vehicle List</h2>
    <a href="add.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Vehicle</a>
  </div>

  <?php
  // Handle status update
  if (isset($_POST['update_status'])) {
    $id = $_POST['vehicle_id'];
    $new_status = $_POST['status'];
    $conn->query("UPDATE vehicles SET status = '$new_status' WHERE id = $id");
    echo "<div class='alert alert-info'>Vehicle status updated.</div>";
  }
  ?>

  <div class="table-responsive">
    <table class="table table-bordered align-middle text-center bg-light text-dark">
      <thead class="table-dark">
        <tr>
          <th>Image</th>
          <th>Brand</th>
          <th>Model</th>
          <th>Plate #</th>
          <th>Rate (₱)</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $vehicles = $conn->query("SELECT * FROM vehicles");
        while ($row = $vehicles->fetch_assoc()) {
          $imagePath = !empty($row['image']) ? "../uploads/{$row['image']}" : "https://via.placeholder.com/200x120?text=No+Image";
          $smart_status = $row['status'];

          // Detect rental status override
          $rentalCheck = $conn->query("SELECT status FROM rentals WHERE vehicle_id = {$row['id']} ORDER BY id DESC LIMIT 1");
          if ($rentalCheck->num_rows > 0) {
            $latestRental = $rentalCheck->fetch_assoc();
            if ($latestRental['status'] === 'Active') {
              $smart_status = 'Rented';
            } elseif ($latestRental['status'] === 'Pending') {
              $smart_status = 'Reserved';
            }
          }

          echo "<tr>
                  <td><img src='$imagePath' class='vehicle-img'></td>
                  <td>{$row['brand']}</td>
                  <td>{$row['model']}</td>
                  <td>{$row['plate_number']}</td>
                  <td>₱" . number_format($row['daily_rate'], 2) . "</td>
                  <td>
                    <form method='POST' class='d-flex justify-content-center align-items-center gap-2'>
                      <input type='hidden' name='vehicle_id' value='{$row['id']}'>
                      <select name='status' class='form-select form-select-sm' " . 
                        (($smart_status === 'Rented' || $smart_status === 'Reserved') ? "disabled" : "") . ">
                        <option value='Available' " . ($row['status'] === 'Available' ? "selected" : "") . ">Available</option>
                        <option value='Under Maintenance' " . ($row['status'] === 'Under Maintenance' ? "selected" : "") . ">Under Maintenance</option>
                      </select>
                      <button type='submit' name='update_status' class='btn btn-sm btn-outline-primary' " . 
                        (($smart_status === 'Rented' || $smart_status === 'Reserved') ? "disabled" : "") . ">
                        <i class='bi bi-save'></i>
                      </button>
                    </form>
                    <small class='text-muted d-block mt-1'>Detected: <strong>$smart_status</strong></small>
                  </td>
                  <td>
                    <a href='edit.php?id={$row['id']}' class='btn btn-warning btn-sm'><i class='bi bi-pencil-square'></i> Edit</a>
                    <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Delete this vehicle?')\"><i class='bi bi-trash'></i> Delete</a>
                  </td>
                </tr>";
        }
      ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
