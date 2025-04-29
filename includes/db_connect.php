<?php
$host = "localhost";
$user = "root";
$pass = ""; // default is empty if you're using XAMPP or Laragon
$db   = "vehicle_rental_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}