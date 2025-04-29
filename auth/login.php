<?php 
include '../includes/db_connect.php'; 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login | Luxury Car Rentals</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #111;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .card {
      background-color: #2a2a2a;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 0 30px rgba(212, 175, 55, 0.15);
      width: 100%;
      max-width: 400px;
    }
    .form-label, .card h3 {
      color: #f0f0f0;
    }
    .btn-gold {
      background-color: #d4af37;
      color: #000;
      font-weight: bold;
    }
    .btn-gold:hover {
      background-color: #e5c452;
    }
    a {
      color: #d4af37;
    }
  </style>
</head>
<body>

<div class="card">
  <h3 class="text-center mb-4"><i class="bi bi-lock-fill me-2"></i>Luxury Rental Login</h3>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control bg-dark text-white" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control bg-dark text-white" required>
    </div>
    <button type="submit" name="login" class="btn btn-gold w-100">Login</button>
    <div class="text-center mt-3">
      Don't have an account? <a href="signup.php">Sign up</a>
    </div>
  </form>

  <?php
  if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch customer id and name
    $result = $conn->query("SELECT id, name, password FROM customers WHERE email = '$email'");
    if ($result && $result->num_rows === 1) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user['password'])) {
        $_SESSION['customer_id'] = $user['id'];
        $_SESSION['customer_name'] = $user['name']; // ✅ Save customer's real name!

        header("Location: ../index.php"); // ✅ After login, go to homepage
        exit();
      }
    }
    echo "<div class='alert alert-danger mt-3'>Invalid email or password.</div>";
  }
  ?>
</div>

</body>
</html>
