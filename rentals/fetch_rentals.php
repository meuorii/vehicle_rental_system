<?php
include '../includes/db_connect.php';

$events = [];

$query = "
  SELECT r.rent_date, r.return_date, r.status,
         v.brand, v.model, v.plate_number, v.image,
         c.name AS customer_name
  FROM rentals r
  JOIN vehicles v ON r.vehicle_id = v.id
  JOIN customers c ON r.customer_id = c.id
";

$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
  $title = "{$row['brand']} {$row['model']} ({$row['plate_number']})";

  $color = match ($row['status']) {
    'Pending' => '#6c757d',     // Gray
    'Active' => '#0d6efd',       // Blue
    'Completed' => '#198754',    // Green
    'Cancelled' => '#dc3545',    // Red
    default => '#adb5bd'
  };

  // Prepare image path
  $image = !empty($row['image']) ? "../uploads/{$row['image']}" : "https://via.placeholder.com/400x220?text=No+Image";

  $events[] = [
    'title' => $title,
    'start' => $row['rent_date'],
    'end'   => date('Y-m-d', strtotime($row['return_date'] . ' +1 day')), // FullCalendar exclusive end
    'color' => $color,
    'customer' => $row['customer_name'],
    'image' => $image
  ];
}

echo json_encode($events);
?>
