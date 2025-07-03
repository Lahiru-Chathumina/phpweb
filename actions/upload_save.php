<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    if (!isset($_FILES['prescription']) || $_FILES['prescription']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Please select a prescription image to upload.';
        header('Location: ../views/upload.php');
        exit;
    }

    $file = $_FILES['prescription'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        $_SESSION['error'] = 'Only JPG, PNG, and GIF files are allowed.';
        header('Location: ../views/upload.php');
        exit;
    }

    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $filename = uniqid() . '_' . basename($file['name']);
    $target_path = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $stmt = $conn->prepare("INSERT INTO prescriptions (user_id, file_path, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("is", $user_id, $filename);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Prescription uploaded successfully.';
            header('Location: ../views/dashboard_user.php');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to save prescription info.';
            header('Location: ../views/upload.php');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Failed to upload file.';
        header('Location: ../views/upload.php');
        exit;
    }
} else {
    header('Location: ../views/upload.php');
    exit;
}
?>
