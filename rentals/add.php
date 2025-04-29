<?php 
include '../includes/db_connect.php'; 
include '../includes/auth_check.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Book a Luxury Vehicle</title>
  <meta charset="utf-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
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
    .form-section {
      background: #1e1e2f;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(212,175,55,0.2);
    }
    .calendar-section {
      background: #1e1e2f;
      padding: 1rem;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(212,175,55,0.2);
      margin-top: 2rem;
    }
    .btn-gold {
      background-color: #d4af37;
      color: #000;
      font-weight: bold;
      border-radius: 8px;
    }
    .btn-gold:hover {
      background-color: #e1c452;
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
          <a class="nav-link" href="../index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../cars.php">Rent Cars</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['customer_name']) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="../customer_dashboard.php"><i class="bi bi-grid"></i> Dashboard</a></li>
            <li><a class="dropdown-item" href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Section -->
<div class="container py-5">
  <h2 class="text-center mb-5" style="color:#d4af37;">Reserve Your Ride</h2>

  <div class="row g-4">
    <div class="col-md-6">
      <div class="form-section">
        <form method="POST" id="rentalForm">
          <div class="mb-3">
            <label class="form-label">Select Vehicle</label>
            <select name="vehicle_id" id="vehicle_id" class="form-select" required>
              <option value="">-- Select a Vehicle --</option>
              <?php
                $vehicles = $conn->query("SELECT * FROM vehicles WHERE status != 'Under Maintenance'");
                while ($v = $vehicles->fetch_assoc()) {
                  echo "<option value='{$v['id']}' data-price='{$v['daily_rate']}'>{$v['brand']} {$v['model']} ({$v['plate_number']}) - ₱" . number_format($v['daily_rate'],2) . "/day</option>";
                }
              ?>
            </select>
          </div>

          <div class="row mb-3">
            <div class="col">
              <label class="form-label">Rent Date</label>
              <input type="date" name="rent_date" id="rent_date" class="form-control" required>
            </div>
            <div class="col">
              <label class="form-label">Return Date</label>
              <input type="date" name="return_date" id="return_date" class="form-control" required>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Estimated Total</label>
            <div id="estimatedTotal" class="form-control bg-dark text-white">₱0.00</div>
          </div>

          <div class="d-grid">
            <button type="submit" name="submit" class="btn btn-gold"><i class="bi bi-calendar-plus"></i> Confirm Booking</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-md-6">
      <div class="calendar-section">
        <h5 class="text-center mb-3" style="color:#d4af37;">Vehicle Booking Calendar</h5>
        <div id="calendar"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
let selectedPrice = 0;
let calendar;

document.addEventListener('DOMContentLoaded', function() {
  const rentDateInput = document.getElementById('rent_date');
  const returnDateInput = document.getElementById('return_date');
  const totalDisplay = document.getElementById('estimatedTotal');
  const vehicleSelect = document.getElementById('vehicle_id');

  vehicleSelect.addEventListener('change', function() {
    selectedPrice = parseFloat(this.options[this.selectedIndex].dataset.price || 0);
    updateTotal();
    fetchCalendarEvents(this.value);
  });

  rentDateInput.addEventListener('change', updateTotal);
  returnDateInput.addEventListener('change', updateTotal);

  function updateTotal() {
    const rentDate = new Date(rentDateInput.value);
    const returnDate = new Date(returnDateInput.value);
    if (rentDate && returnDate && !isNaN(rentDate) && !isNaN(returnDate)) {
      const days = Math.max(1, Math.floor((returnDate - rentDate) / (1000 * 60 * 60 * 24)));
      const total = selectedPrice * days;
      totalDisplay.textContent = `₱${total.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
    } else {
      totalDisplay.textContent = "₱0.00";
    }
  }

  function fetchCalendarEvents(vehicleId) {
    if (calendar) {
      calendar.destroy();
    }
    calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
      initialView: 'dayGridMonth',
      events: `../rentals/fetch_rentals_calendar.php?vehicle_id=${vehicleId}`,
      eventColor: '#dc3545',
      height: 500,
    });
    calendar.render();
  }
});
</script>

</body>
</html>
