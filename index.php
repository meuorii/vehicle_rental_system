<?php
session_start();
include 'includes/db_connect.php'; // Always include db_connect at start if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Luxury Car Rentals</title>
  <meta charset="utf-8" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
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
    .hero {
      position: relative;
      height: 90vh;
      background: url('img/hero.png') no-repeat center center/cover;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: #fff;
    }
    .hero::after {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.6);
    }
    .hero-content {
      position: relative;
      z-index: 2;
    }
    .hero h1 {
      font-size: 4rem;
      color: #d4af37;
      text-shadow: 2px 2px 10px #000;
    }
    .hero p {
      font-size: 1.5rem;
      margin-top: 15px;
    }
    .cta-btn {
      margin-top: 25px;
      background-color: #d4af37;
      border: none;
      color: #121212;
      padding: 12px 30px;
      font-size: 1.2rem;
      border-radius: 50px;
      transition: 0.3s;
    }
    .cta-btn:hover {
      background-color: #c9a72c;
    }
    .section-title {
      color: #d4af37;
      text-align: center;
      margin: 60px 0 30px;
      font-size: 2.5rem;
    }

    /* Timeline */
    .timeline {
      display: flex;
      justify-content: space-around;
      align-items: center;
      flex-wrap: wrap;
      position: relative;
      margin-bottom: 80px;
    }
    .timeline::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 2px;
      background: #d4af37;
    }
    .timeline-item {
      position: relative;
      background: #1e1e2f;
      border-radius: 12px;
      padding: 20px;
      width: 220px;
      text-align: center;
      z-index: 2;
    }
    .timeline-item i {
      font-size: 2rem;
      color: #d4af37;
      margin-bottom: 10px;
    }

    /* Zigzag Packages */
    .package {
      display: flex;
      align-items: center;
      margin-bottom: 50px;
    }
    .package:nth-child(even) {
      flex-direction: row-reverse;
    }
    .package img {
      width: 50%;
      border-radius: 12px;
    }
    .package-content {
      width: 50%;
      padding: 30px;
    }
    .package-content h4 {
      color: #d4af37;
    }

    /* How It Works */
    .stepper {
      margin: 50px auto;
      max-width: 600px;
    }
    .step {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
    }
    .step i {
      font-size: 2rem;
      color: #d4af37;
      margin-right: 20px;
    }
    .footer {
      background-color: #1e1e2f;
      color: #999;
      padding: 20px;
      text-align: center;
      margin-top: 60px;
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

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">
    <h1>Drive in Style</h1>
    <p>Where Luxury Meets The Road</p>
    <a href="customer.php" class="btn cta-btn">Explore Rentals</a>
  </div>
</section>

<!-- Why Choose Us -->
<div class="container my-5">
  <h2 class="section-title">Why Choose Us</h2>
  <div class="timeline">
    <div class="timeline-item">
      <i class="bi bi-award"></i>
      <h5>Prestige Cars</h5>
    </div>
    <div class="timeline-item">
      <i class="bi bi-lightning-charge"></i>
      <h5>Fast Booking</h5>
    </div>
    <div class="timeline-item">
      <i class="bi bi-shield-check"></i>
      <h5>Fully Insured</h5>
    </div>
    <div class="timeline-item">
      <i class="bi bi-headset"></i>
      <h5>24/7 Support</h5>
    </div>
  </div>
</div>

<!-- Featured Rental Packages -->
<div class="container">
  <h2 class="section-title">Featured Packages</h2>
  
  <div class="package">
    <img src="img/bmw.jpg" alt="Package 1">
    <div class="package-content">
      <h4>Weekend Luxury Drive</h4>
      <p>Experience a special 2-day drive in the world's most luxurious vehicles at discounted rates.</p>
    </div>
  </div>

  <div class="package">
    <img src="img/rolls-royce.jpg" alt="Package 2">
    <div class="package-content">
      <h4>VIP Airport Pickup</h4>
      <p>Arrive at the airport and drive away in a Rolls Royce or Bentley - pure luxury service awaits.</p>
    </div>
  </div>

  <div class="package">
    <img src="img/mercedez.avif" alt="Package 3">
    <div class="package-content">
      <h4>Monthly Corporate Plan</h4>
      <p>Exclusive rental packages for executives. Drive premium vehicles for your business needs.</p>
    </div>
  </div>
</div>

<!-- How It Works -->
<div class="container">
  <h2 class="section-title">How It Works</h2>
  <div class="stepper">
    <div class="step">
      <i class="bi bi-car-front"></i>
      <div>
        <h5>1. Choose Your Car</h5>
        <p>Pick from our fleet of luxury vehicles.</p>
      </div>
    </div>
    <div class="step">
      <i class="bi bi-calendar-check"></i>
      <div>
        <h5>2. Book Online</h5>
        <p>Reserve your luxury experience in minutes.</p>
      </div>
    </div>
    <div class="step">
      <i class="bi bi-geo-alt"></i>
      <div>
        <h5>3. Drive Away</h5>
        <p>Pick up your car and enjoy the journey!</p>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  &copy; <?= date('Y') ?> LuxCarRentals. All rights reserved.
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
