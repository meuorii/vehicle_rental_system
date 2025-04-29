<?php
include '../includes/db_connect.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['customer_id'])) {
  header("Location: ../auth/login.php");
  exit();
}

$customer_id = $_SESSION['customer_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>My Rentals | LuxCarRentals</title>
  <meta charset="utf-8" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #121212;
      color: #fff;
      font-family: 'Poppins', sans-serif;
    }
    .navbar {
      background-color: #1e1e2f;
    }
    .navbar-brand {
      color: #d4af37;
      font-weight: bold;
      font-size: 24px;
    }
    .navbar-brand:hover {
      color: #c9a72c;
    }
    .section-title {
      text-align: center;
      margin: 40px 0;
      color: #d4af37;
    }
    .card {
      background: #1e1e2f;
    }
    .table {
      color: #ccc;
    }
    .table th, .table td {
      vertical-align: middle;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="../index.php"><i class="bi bi-gem"></i> LuxCarRentals</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="../index.php"><i class="bi bi-house-door"></i> Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../cars.php"><i class="bi bi-car-front"></i> Rent Cars</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['customer_name']) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="../customer_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a class="dropdown-item" href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Page Content -->
<div class="container py-5">
  <h2 class="section-title"><i class="bi bi-calendar-check-fill me-2"></i>My Rentals</h2>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th>Vehicle</th>
              <th>Plate</th>
              <th>Dates</th>
              <th>Fee (₱)</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $query = "
              SELECT r.id, r.rent_date, r.return_date, r.total_fee, r.status,
                     v.brand, v.model, v.plate_number
              FROM rentals r
              JOIN vehicles v ON r.vehicle_id = v.id
              WHERE r.customer_id = $customer_id
              ORDER BY r.id DESC
            ";
            $result = $conn->query($query);
            if ($result->num_rows === 0) {
              echo "<tr><td colspan='6' class='text-muted'>No rentals yet.</td></tr>";
            } else {
              while ($row = $result->fetch_assoc()) {
                $badge = match($row['status']) {
                  'Active' => 'bg-warning text-dark',
                  'Completed' => 'bg-success',
                  'Pending' => 'bg-secondary',
                  'Cancelled' => 'bg-danger',
                  default => 'bg-light text-dark'
                };

                echo "<tr>
                        <td>{$row['brand']} {$row['model']}</td>
                        <td>{$row['plate_number']}</td>
                        <td>
                          <i class='bi bi-calendar-event'></i> {$row['rent_date']}<br>
                          <i class='bi bi-arrow-return-right'></i> {$row['return_date']}
                        </td>
                        <td>₱" . number_format($row['total_fee'], 2) . "</td>
                        <td><span class='badge $badge'>{$row['status']}</span></td>
                        <td>";
                if ($row['status'] === 'Pending') {
                  echo "<a href='cancel_rental.php?id={$row['id']}' class='btn btn-sm btn-outline-danger' onclick=\"return confirm('Cancel this rental?')\">
                          <i class='bi bi-x-circle'></i> Cancel
                        </a>";
                } else {
                  echo "<span class='text-muted'>-</span>";
                }
                echo "</td></tr>";
              }
            }
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
