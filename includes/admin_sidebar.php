<!-- admin_sidebar.php -->
<style>
  .sidebar {
    height: 100vh;
    background-color: #1e1e2f;
    padding: 20px 15px;
    color: #fff;
    position: fixed;
    width: 240px;
    top: 0;
    left: 0;
    z-index: 1030;
  }
  .sidebar h4 {
    color: #d4af37;
    font-weight: bold;
    margin-bottom: 30px;
  }
  .sidebar .section-label {
    text-transform: uppercase;
    font-size: 12px;
    color: #888;
    margin: 25px 0 10px;
    padding-left: 10px;
  }
  .sidebar a {
    display: block;
    color: #ccc;
    padding: 10px 15px;
    margin-bottom: 5px;
    border-radius: 6px;
    text-decoration: none;
    transition: background 0.3s, color 0.3s;
  }
  .sidebar a:hover,
  .sidebar a.active {
    background-color: #34344a;
    color: #fff;
  }

  .main-content {
    margin-left: 260px;
    padding: 30px;
  }

  @media (max-width: 768px) {
    .sidebar {
      width: 100%;
      height: auto;
      position: relative;
    }
    .main-content {
      margin-left: 0;
      padding: 20px;
    }
  }
</style>

<div class="sidebar">
  <h4><i class="bi bi-gem me-2"></i>Admin Panel</h4>

  <div class="section-label">Dashboard</div>
  <a href="/vehicle_rental_system/admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : '' ?>">
  <i class="bi bi-speedometer2 me-2"></i> Dashboard
</a>

<a href="/vehicle_rental_system/vehicles/list.php" class="<?= strpos($_SERVER['PHP_SELF'], 'vehicles') !== false ? 'active' : '' ?>">
  <i class="bi bi-car-front-fill me-2"></i> Vehicles
</a>

<a href="/vehicle_rental_system/rentals/list.php" class="<?= strpos($_SERVER['PHP_SELF'], 'rentals/list.php') !== false ? 'active' : '' ?>">
  <i class="bi bi-clock-history me-2"></i> Rentals
</a>

<a href="/vehicle_rental_system/rentals/calendar.php" class="<?= basename($_SERVER['PHP_SELF']) == 'calendar.php' ? 'active' : '' ?>">
  <i class="bi bi-calendar-week me-2"></i> Calendar
</a>

<a href="/vehicle_rental_system/logout.php">
  <i class="bi bi-box-arrow-right me-2"></i> Logout
</a>
</div>
