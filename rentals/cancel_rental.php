<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['customer_id']) || !isset($_GET['id'])) {
  header("Location: my_rentals.php");
  exit();
}

$customer_id = $_SESSION['customer_id'];
$rental_id = $_GET['id'];

// Only cancel if rental is pending and belongs to this user
$conn->query("UPDATE rentals SET status = 'Cancelled' WHERE id = $rental_id AND customer_id = $customer_id AND status = 'Pending'");

header("Location: my_rentals.php");
exit();
?>
