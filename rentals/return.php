<?php
include '../includes/db_connect.php';

if (!isset($_GET['id'])) {
  header("Location: list.php");
  exit();
}

$rental_id = $_GET['id'];

// Fetch rental and vehicle
$result = $conn->query("SELECT vehicle_id FROM rentals WHERE id = $rental_id AND status = 'Active'");
if ($row = $result->fetch_assoc()) {
  $vehicle_id = $row['vehicle_id'];

  // Mark rental as Completed and vehicle as Available
  $conn->query("UPDATE rentals SET status = 'Completed' WHERE id = $rental_id");
  $conn->query("UPDATE vehicles SET status = 'Available' WHERE id = $vehicle_id");

  header("Location: list.php?returned=1");
  exit();
}

header("Location: list.php");
exit();
?>
