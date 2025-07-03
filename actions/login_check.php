<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$email || !$password) {
        $_SESSION['error'] = 'Please enter both email and password.';
        header('Location: ../views/login.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $password_hash, $role);
        $stmt->fetch();

        if (password_verify($password, $password_hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            if ($role === 'pharmacy') {
                header('Location: ../views/dashboard_pharmacy.php');
            } else {
                header('Location: ../views/dashboard_user.php');
            }
            exit;
        } else {
            $_SESSION['error'] = 'Incorrect password.';
            header('Location: ../views/login.php');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Email not found.';
        header('Location: ../views/login.php');
        exit;
    }
} else {
    header('Location: ../views/login.php');
    exit;
}
?>
