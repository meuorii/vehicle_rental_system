<?php
include '../includes/db_connect.php';
$id = $_GET['id'];
$conn->query("DELETE FROM vehicles WHERE id = $id");
header("Location: list.php");
