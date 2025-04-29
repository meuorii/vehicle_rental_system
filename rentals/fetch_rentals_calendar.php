<?php
include '../includes/db_connect.php';
session_start();

$vehicle_id = $_GET['vehicle_id'];
$customer_id = $_SESSION['customer_id']; // get current logged in customer

$events = [];

$query = "
  SELECT r.rent_date, r.return_date, r.status, r.customer_id,
         v.brand, v.model, v.plate_number
  FROM rentals r
  JOIN vehicles v ON r.vehicle_id = v.id
  WHERE r.vehicle_id = $vehicle_id
";

$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
  $isMine = ($row['customer_id'] == $customer_id); // Check if the rental is yours

  $title = $isMine ? "🚗 My Booking" : "{$row['brand']} {$row['model']} ({$row['plate_number']})";

  $color = $isMine ? '#d4af37' : match($row['status']) {
    'Pending' => '#6c757d', // Gray
    'Active' => '#0d6efd',  // Blue
    'Completed' => '#198754', // Green
    'Cancelled' => '#dc3545', // Red
    default => '#adb5bd'
  };

  $events[] = [
    'title' => $title,
    'start' => $row['rent_date'],
    'end'   => date('Y-m-d', strtotime($row['return_date'] . ' +1 day')),
    'color' => $color
  ];
}

echo json_encode($events);
?>
