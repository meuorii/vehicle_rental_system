<?php
include '../includes/db_connect.php';

if (!isset($_GET['id'])) {
  header("Location: list.php");
  exit();
}

$rental_id = $_GET['id'];

// Get vehicle ID for the rental
$rental = $conn->query("SELECT vehicle_id FROM rentals WHERE id = $rental_id")->fetch_assoc();
if ($rental) {
  $vehicle_id = $rental['vehicle_id'];

  // Set rental to Active
  $conn->query("UPDATE rentals SET status = 'Active' WHERE id = $rental_id");

  // Set vehicle to Rented
  $conn->query("UPDATE vehicles SET status = 'Rented' WHERE id = $vehicle_id");

  header("Location: list.php?started=1");
  exit();
} else {
  header("Location: list.php");
  exit();
}
?>
