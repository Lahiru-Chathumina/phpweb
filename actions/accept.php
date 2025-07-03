<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../views/login.php');
    exit;
}

if (isset($_GET['quotation_id'])) {
    $quotation_id = intval($_GET['quotation_id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        SELECT q.id FROM quotations q
        JOIN prescriptions p ON q.prescription_id = p.id
        WHERE q.id = ? AND p.user_id = ?
    ");
    $stmt->bind_param("ii", $quotation_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $conn->begin_transaction();

        $update_accept = $conn->prepare("UPDATE quotations SET status = 'accepted' WHERE id = ?");
        $update_accept->bind_param("i", $quotation_id);
        $update_accept->execute();

        $update_reject = $conn->prepare("UPDATE quotations SET status = 'rejected' WHERE id != ? AND prescription_id = (SELECT prescription_id FROM quotations WHERE id = ?)");
        $update_reject->bind_param("ii", $quotation_id, $quotation_id);
        $update_reject->execute();

        $conn->commit();

        $_SESSION['success'] = 'Quotation accepted successfully.';
        header('Location: ../views/quotations_user.php');
        exit;
    } else {
        $_SESSION['error'] = 'Invalid quotation or permission denied.';
        header('Location: ../views/quotations_user.php');
        exit;
    }
} else {
    header('Location: ../views/quotations_user.php');
    exit;
}
?>
