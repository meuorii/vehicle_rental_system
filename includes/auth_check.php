<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
  header("Location: ../auth/login.php");
  exit();
}
?>
