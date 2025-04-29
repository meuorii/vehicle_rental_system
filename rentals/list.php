<?php include '../includes/db_connect.php'; ?>
<?php include '../includes/admin_sidebar.php'; ?>

<?php
$today = date('Y-m-d');

// Auto-start rentals
$pendingRentals = $conn->query("
  SELECT r.id, r.vehicle_id 
  FROM rentals r 
  WHERE r.rent_date <= '$today' AND r.status = 'Pending'
");
while ($rental = $pendingRentals->fetch_assoc()) {
  $conn->query("UPDATE rentals SET status = 'Active' WHERE id = {$rental['id']}");
  $conn->query("UPDATE vehicles SET status = 'Rented' WHERE id = {$rental['vehicle_id']}");
}

// Auto-complete rentals
$completedRentals = $conn->query("
  SELECT r.id, r.vehicle_id 
  FROM rentals r 
  WHERE r.return_date < '$today' AND r.status = 'Active'
");
while ($rental = $completedRentals->fetch_assoc()) {
  $conn->query("UPDATE rentals SET status = 'Completed' WHERE id = {$rental['id']}");
  $conn->query("UPDATE vehicles SET status = 'Available' WHERE id = {$rental['vehicle_id']}");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Rental List</title>
  <meta charset="utf-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
      color: #fff;
    }
    .main-content {
      margin-left: 260px;
      padding: 30px;
    }
  </style>
</head>
<body>

<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clock-history me-2"></i>All Rentals</h2>
    <a href="add.php" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> New Rental</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 text-center align-middle bg-light text-dark">
          <thead class="table-dark">
            <tr>
              <th>Customer</th>
              <th>Vehicle</th>
              <th>Dates</th>
              <th>Fee (₱)</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = "
              SELECT r.id, r.rent_date, r.return_date, r.total_fee, r.status AS rental_status,
                     c.name AS customer, c.contact_number,
                     v.id AS vehicle_id, v.brand, v.model, v.plate_number, v.status AS vehicle_status
              FROM rentals r
              JOIN customers c ON r.customer_id = c.id
              JOIN vehicles v ON r.vehicle_id = v.id
              ORDER BY r.id DESC
            ";
            $result = $conn->query($query);
            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $badge = match($row['rental_status']) {
                  'Active' => 'bg-warning text-dark',
                  'Completed' => 'bg-success',
                  'Pending' => 'bg-secondary',
                  'Cancelled' => 'bg-danger',
                  default => 'bg-light text-dark'
                };

                echo "<tr>
                  <td>
                    <strong>{$row['customer']}</strong><br>
                    <small>{$row['contact_number']}</small>
                  </td>
                  <td>
                    {$row['brand']} {$row['model']}<br>
                    <small class='text-muted'>{$row['plate_number']}</small>
                  </td>
                  <td>
                    <i class='bi bi-calendar-event'></i> {$row['rent_date']}<br>
                    <i class='bi bi-arrow-return-right'></i> {$row['return_date']}
                  </td>
                  <td>₱" . number_format($row['total_fee'], 2) . "</td>
                  <td>
                    <span class='badge $badge'>{$row['rental_status']}</span><br>
                    <small class='text-muted'>Vehicle: {$row['vehicle_status']}</small>
                  </td>
                  <td class='d-flex gap-2 justify-content-center flex-wrap'>";

                // Return
                if ($row['rental_status'] === 'Active' && $row['vehicle_status'] === 'Rented') {
                  echo "<a href='return.php?id={$row['id']}' class='btn btn-sm btn-primary'><i class='bi bi-check-circle'></i> Return</a>";
                }

                // Maintenance
                if ($row['vehicle_status'] === 'Under Maintenance') {
                  echo "<span class='badge bg-dark'><i class='bi bi-wrench'></i> Maintenance</span>";
                }

                // Delete
                if (in_array($row['rental_status'], ['Pending', 'Cancelled'])) {
                  echo "<a href='delete_rental.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this rental?')\">
                          <i class='bi bi-trash'></i> Delete
                        </a>";
                }

                echo "</td></tr>";
              }
            } else {
              echo "<tr><td colspan='6' class='text-muted'>No rentals found.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Toasts -->
<?php if (isset($_GET['returned'])): ?>
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast text-bg-success border-0 show">
      <div class="d-flex">
        <div class="toast-body"><i class="bi bi-check-circle-fill me-2"></i> Vehicle returned successfully!</div>
        <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if (isset($_GET['started'])): ?>
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast text-bg-info border-0 show">
      <div class="d-flex">
        <div class="toast-body"><i class="bi bi-play-circle-fill me-2"></i> Rental started successfully!</div>
        <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast text-bg-danger border-0 show">
      <div class="d-flex">
        <div class="toast-body"><i class="bi bi-trash-fill me-2"></i> Rental deleted successfully!</div>
        <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
