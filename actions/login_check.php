<?php
session_start();
require_once '../db.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$errors = [];

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}
if (empty($password)) {
    $errors[] = "Password is required.";
}

if (!empty($errors)) {
    $_SESSION['login_errors'] = $errors;
    header("Location: ../views/login.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['logged_in'] = true;

    if ($user['role'] === 'pharmacy') {
        header("Location: ../views/pharmacy_dashboard.php");
    }  if ($user['role'] === 'user') {
        header("Location: ../views/user_dashboard.php");
    } 
    else {
        header("Location: ../views/user_dashboard.php");
    }
    exit;
} else {
    $_SESSION['login_errors'] = ["Invalid email or password."];
    header("Location: ../views/login.php");
    exit;
}
