<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pharmacy') {
    header('Location: ../views/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pharmacy_id = $_SESSION['user_id'];
    $prescription_id = intval($_POST['prescription_id']);
    $price = floatval($_POST['price']);
    $notes = trim($_POST['notes']);

    if (!$prescription_id || !$price) {
        $_SESSION['error'] = 'Please fill all required fields.';
        header("Location: ../views/quotation_form.php?prescription_id=$prescription_id");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO quotations (prescription_id, pharmacy_id, price, notes, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iids", $prescription_id, $pharmacy_id, $price, $notes);

    if ($stmt->execute()) {
        $userResult = $conn->query("SELECT u.email FROM users u JOIN prescriptions p ON u.id = p.user_id WHERE p.id = $prescription_id");
        if ($userResult && $userRow = $userResult->fetch_assoc()) {
            $to = $userRow['email'];
            $subject = "New Quotation for Your Prescription";
            $message = "Dear user,\n\nA new quotation has been prepared for your uploaded prescription. Please log in to your account to review and respond.\n\nThank you.";
            $headers = "From: pharmacy@example.com";

            mail($to, $subject, $message, $headers);
        }

        $_SESSION['success'] = 'Quotation submitted successfully.';
        header('Location: ../views/dashboard_pharmacy.php');
        exit;
    } else {
        $_SESSION['error'] = 'Failed to submit quotation.';
        header("Location: ../views/quotation_form.php?prescription_id=$prescription_id");
        exit;
    }
} else {
    header('Location: ../views/dashboard_pharmacy.php');
    exit;
}
?>
