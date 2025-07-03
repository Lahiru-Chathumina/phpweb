<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $address = htmlspecialchars(trim($_POST['address']));
    $contact = htmlspecialchars(trim($_POST['contact']));
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $errors = [];

    if (empty($name)) $errors[] = "Name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters";
    if (!in_array($role, ['user', 'pharmacy'])) $errors[] = "Invalid user role";

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "Email already registered";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../views/register.php');
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, address, contact_no, dob, role) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $password_hash, $address, $contact, $dob, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Registration successful. Please login.';
        header('Location: ../views/login.php');
    } else {
        $_SESSION['errors'] = ['Registration failed: ' . $conn->error];
        header('Location: ../views/register.php');
    }
    exit;
}

header('Location: ../views/register.php');
exit;
?>
