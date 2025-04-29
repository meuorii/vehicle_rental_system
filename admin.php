<?php include 'includes/db_connect.php'; ?>
<?php include 'includes/admin_sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Dashboard</title>
  <meta charset="utf-8" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #121212;
      color: #fff;
    }
    .main-content {
      margin-left: 260px;
      padding: 30px;
    }
    .card h4 {
      font-size: 16px;
    }
    .card h2 {
      font-size: 32px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="main-content">
  <h2 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h2>

  <?php
    $vehicles   = $conn->query("SELECT COUNT(*) AS total FROM vehicles")->fetch_assoc()['total'];
    $ongoing    = $conn->query("SELECT COUNT(*) AS total FROM rentals WHERE status IN ('Pending', 'Active')")->fetch_assoc()['total'];
    $completed  = $conn->query("SELECT COUNT(*) AS total FROM rentals WHERE status = 'Completed'")->fetch_assoc()['total'];
    $income     = $conn->query("SELECT SUM(total_fee) AS total FROM rentals WHERE status = 'Completed'")->fetch_assoc()['total'] ?? 0;

    $counts = ['Pending' => 0, 'Active' => 0, 'Completed' => 0, 'Cancelled' => 0];
    $statusQuery = $conn->query("SELECT status, COUNT(*) AS total FROM rentals GROUP BY status");
    while ($row = $statusQuery->fetch_assoc()) {
      $counts[$row['status']] = $row['total'];
    }

    $weekData = [];
    $days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
    for ($i = 6; $i >= 0; $i--) {
      $day = date('Y-m-d', strtotime("-$i days"));
      $label = $days[date('w', strtotime($day))];
      $incomeDay = $conn->query("SELECT SUM(total_fee) AS total FROM rentals WHERE rent_date = '$day'")->fetch_assoc()['total'] ?? 0;
      $bookings = $conn->query("SELECT COUNT(*) AS total FROM rentals WHERE rent_date = '$day'")->fetch_assoc()['total'];
      $weekData[] = [
        'label' => $label,
        'income' => (float)$incomeDay,
        'bookings' => (int)$bookings
      ];
    }
  ?>

  <!-- Summary Cards -->
  <div class="row g-4 mb-5">
    <div class="col-md-3">
      <div class="card bg-primary text-white text-center p-3">
        <h4><i class="bi bi-car-front-fill me-2"></i>Total Vehicles</h4>
        <h2><?= $vehicles ?></h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-dark text-center p-3">
        <h4><i class="bi bi-clock-history me-2"></i>Ongoing Rentals</h4>
        <h2><?= $ongoing ?></h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-success text-white text-center p-3">
        <h4><i class="bi bi-check-circle-fill me-2"></i>Completed</h4>
        <h2><?= $completed ?></h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-dark text-warning text-center p-3 border border-warning">
        <h4><i class="bi bi-cash-coin me-2"></i>Total Income</h4>
        <h2>₱<?= number_format($income, 2) ?></h2>
      </div>
    </div>
  </div>

  <!-- Charts -->
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card p-3 bg-light">
        <canvas id="statusChart"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3 bg-light">
        <canvas id="weeklyChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Chart JS -->
<script>
  const ctx1 = document.getElementById('statusChart').getContext('2d');
  new Chart(ctx1, {
    type: 'pie',
    data: {
      labels: ['Pending', 'Active', 'Completed', 'Cancelled'],
      datasets: [{
        label: 'Rental Status',
        data: [<?= $counts['Pending'] ?>, <?= $counts['Active'] ?>, <?= $counts['Completed'] ?>, <?= $counts['Cancelled'] ?>],
        backgroundColor: ['#6c757d', '#0d6efd', '#198754', '#dc3545']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Rental Status Breakdown'
        }
      }
    }
  });

  const ctx2 = document.getElementById('weeklyChart').getContext('2d');
  new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: [<?= implode(",", array_map(fn($d) => "'".$d['label']."'", $weekData)) ?>],
      datasets: [
        {
          label: 'Total Bookings',
          data: [<?= implode(",", array_column($weekData, 'bookings')) ?>],
          backgroundColor: '#0d6efd'
        },
        {
          label: 'Total Income (₱)',
          data: [<?= implode(",", array_column($weekData, 'income')) ?>],
          backgroundColor: '#d4af37'
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Bookings & Income (Last 7 Days)'
        }
      },
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

</body>
</html>
