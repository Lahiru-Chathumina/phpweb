<?php
session_start();
$conn = new mysqli("localhost", "root", "", "med_db");

$email = $_POST['email'];
$password = $_POST['password'];

$res = $conn->query("SELECT * FROM users WHERE email='$email'");
$user = $res->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['role'] = $user['role'];

  if ($user['role'] == 'pharmacy') {
    header("Location: ../index.php?page=dashboard_pharmacy");
  } else {
    header("Location: ../index.php?page=dashboard_user");
  }
} else {
  echo "Invalid credentials.";
}
