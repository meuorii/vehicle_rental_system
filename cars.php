<?php 
include 'includes/db_connect.php'; 
include 'includes/auth_check.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Rent Luxury Cars</title>
  <meta charset="utf-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
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
    .card {
      background-color: #1e1e2f;
      border: none;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(212, 175, 55, 0.1);
      transition: 0.4s;
      height: 100%;
      color: #fff;
      position: relative;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 30px rgba(212, 175, 55, 0.2);
    }
    .car-img {
      height: 220px;
      object-fit: cover;
      width: 100%;
      filter: brightness(90%);
    }
    .status-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(0, 0, 0, 0.8); /* Black semi-transparent background */
      color: #d4af37;
      padding: 5px 12px;
      border-radius: 50px;
      font-size: 0.75rem;
      font-weight: bold;
      z-index: 10;
      border: 1px solid #d4af37;
    }
    .btn-gold {
      background-color: #d4af37;
      color: #000;
      font-weight: bold;
      width: 100%;
      border-radius: 8px;
    }
    .btn-gold:hover {
      background-color: #e5c452;
    }
  </style>
</head>
<body>

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
        <li class="nav-item">
          <a class="nav-link active" href="cars.php"><i class="bi bi-car-front"></i> Rent Cars</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['customer_name']) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="customer_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a class="dropdown-item" href="auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Cars Listing -->
<div class="container py-5">
  <h2 class="text-center mb-5" style="color:#d4af37;">Available Luxury Vehicles</h2>

  <div class="row g-4">
    <?php
      $vehicles = $conn->query("SELECT * FROM vehicles ORDER BY brand, model");
      while ($v = $vehicles->fetch_assoc()):
        $status = $v['status'];
        $image = !empty($v['image']) ? "uploads/{$v['image']}" : "https://via.placeholder.com/400x220?text=No+Image";
    ?>
    <div class="col-md-4">
      <div class="card">
        <img src="<?= $image ?>" class="car-img">
        <span class="status-badge"><?= htmlspecialchars($status) ?></span>
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($v['brand'] . " " . $v['model']) ?></h5>
          <p class="card-text"></i> ₱<?= number_format($v['daily_rate'], 2) ?>/day</p>
          <p class="card-text "><i class="bi bi-car-front-fill"></i> <?= htmlspecialchars($v['plate_number']) ?></p>
          <?php if ($status === 'Available'): ?>
            <a href="rentals/add.php?vehicle_id=<?= $v['id'] ?>" class="btn btn-gold"><i class="bi bi-calendar-plus"></i> Book Now</a>
          <?php else: ?>
            <button class="btn btn-secondary w-100" disabled><i class="bi bi-x-circle"></i> Not Available</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
