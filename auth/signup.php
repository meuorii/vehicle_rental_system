<?php include '../includes/db_connect.php'; session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register | Luxury Car Rentals</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #1a1a1a;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .card {
      background-color: #2b2b2b;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 0 30px rgba(212, 175, 55, 0.15);
      width: 100%;
      max-width: 480px;
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
      background-color: #e6c250;
    }
    a {
      color: #d4af37;
    }
  </style>
</head>
<body>

<div class="card">
  <h3 class="text-center mb-4"><i class="bi bi-person-plus-fill me-2"></i>Luxury Rental Sign Up</h3>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="name" class="form-control bg-dark text-white" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Contact Number</label>
      <input type="text" name="contact_number" class="form-control bg-dark text-white" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control bg-dark text-white" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control bg-dark text-white" required>
    </div>
    <button type="submit" name="register" class="btn btn-gold w-100">Create Account</button>
    <div class="text-center mt-3">
      Already have an account? <a href="login.php">Login</a>
    </div>
  </form>

  <?php
  if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact_number'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO customers (name, contact_number, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $contact, $email, $password);
    if ($stmt->execute()) {
      echo "<div class='alert alert-success mt-3'>Account created. <a href='login.php'>Login here</a>.</div>";
    } else {
      echo "<div class='alert alert-danger mt-3'>Email already exists or something went wrong.</div>";
    }
  }
  ?>
</div>

</body>
</html>
