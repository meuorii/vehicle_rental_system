<?php
include '../includes/db_connect.php';

if (!isset($_GET['id'])) {
  header("Location: list.php");
  exit();
}

$rental_id = $_GET['id'];

// Optional: check status before delete
$result = $conn->query("SELECT vehicle_id, status FROM rentals WHERE id = $rental_id");
$rental = $result->fetch_assoc();

if ($rental && in_array($rental['status'], ['Pending', 'Cancelled'])) {
  $vehicle_id = $rental['vehicle_id'];

  // Set vehicle back to Available
  $conn->query("UPDATE vehicles SET status = 'Available' WHERE id = $vehicle_id");

  // Delete rental record
  $conn->query("DELETE FROM rentals WHERE id = $rental_id");

  header("Location: list.php?deleted=1");
  exit();
}

header("Location: list.php");
exit();
?>
