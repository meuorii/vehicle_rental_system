<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Fetch customer stats
$customer_id = $_SESSION['customer_id'];

$totalRentals = $conn->query("SELECT COUNT(*) AS total FROM rentals WHERE customer_id = $customer_id")->fetch_assoc()['total'];
$activeRentals = $conn->query("SELECT COUNT(*) AS active FROM rentals WHERE customer_id = $customer_id AND status = 'Active'")->fetch_assoc()['active'];
$completedRentals = $conn->query("SELECT COUNT(*) AS completed FROM rentals WHERE customer_id = $customer_id AND status = 'Completed'")->fetch_assoc()['completed'];
$cancelledRentals = $conn->query("SELECT COUNT(*) AS cancelled FROM rentals WHERE customer_id = $customer_id AND status = 'Cancelled'")->fetch_assoc()['cancelled'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>My Dashboard | Luxury Car Rentals</title>
  <meta charset="utf-8" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #121212;
      color: #ffffff;
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
      border: none;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(212, 175, 55, 0.1);
      padding: 30px;
      text-align: center;
      transition: 0.3s;
      color: #ffffff;
    }
    .card:hover {
      box-shadow: 0 0 20px rgba(212, 175, 55, 0.2);
    }
    .card h3 {
      color: #d4af37;
    }
    .btn-gold {
      background-color: #d4af37;
      color: #121212;
      font-weight: bold;
      border-radius: 50px;
      padding: 10px 30px;
    }
    .btn-gold:hover {
      background-color: #c9a72c;
    }
    .dashboard-actions a {
      margin: 10px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="bi bi-gem"></i> LuxCarRentals</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> Home</a>
        </li>

        <?php if (isset($_SESSION['customer_name'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="cars.php"><i class="bi bi-car-front"></i> Rent Cars</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['customer_name']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="customer.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
              <li><a class="dropdown-item" href="auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
          </li>

        <?php else: ?>
          <li class="nav-item">
            <a href="auth/login.php" class="btn btn-warning ms-2"><i class="bi bi-box-arrow-in-right"></i> Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Dashboard -->
<div class="container my-5">
  <h2 class="section-title">Welcome, <?= htmlspecialchars($_SESSION['customer_name']) ?>!</h2>

  <div class="row g-4 mb-5">
    <div class="col-md-3">
      <div class="card">
        <h3><?= $totalRentals ?></h3>
        <p>Total Rentals</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <h3><?= $activeRentals ?></h3>
        <p>Active Rentals</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <h3><?= $completedRentals ?></h3>
        <p>Completed Rentals</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <h3><?= $cancelledRentals ?></h3>
        <p>Cancelled Rentals</p>
      </div>
    </div>
  </div>

  <div class="text-center dashboard-actions">
    <a href="rentals/my_rentals.php" class="btn btn-gold"><i class="bi bi-clock-history me-1"></i> View My Rentals</a>
    <a href="rentals/add.php" class="btn btn-gold"><i class="bi bi-plus-circle me-1"></i> New Booking</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
