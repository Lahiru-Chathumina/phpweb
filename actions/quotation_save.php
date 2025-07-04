<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prescriptionId = intval($_POST['prescription_id']);
    $items = json_decode($_POST['drugs_json'], true);

    if (!$prescriptionId || empty($items)) {
        echo "<script>alert('Invalid submission.'); window.history.back();</script>";
        exit;
    }

    $total = array_sum(array_column($items, 'amount'));

    $stmt = $conn->prepare("INSERT INTO quotations (prescription_id, total_amount) VALUES (?, ?)");
    $stmt->bind_param("id", $prescriptionId, $total);
    $stmt->execute();
    $quotationId = $stmt->insert_id;
    $stmt->close();

    $itemStmt = $conn->prepare("INSERT INTO quotation_items (quotation_id, drug_name, quantity, unit_price) VALUES (?, ?, ?, ?)");
    foreach ($items as $item) {
        $drug = $item['drug'];
        $quantityParts = explode('x', strtolower($item['quantity']));
        $qty = isset($quantityParts[0], $quantityParts[1]) ? intval($quantityParts[0]) * intval($quantityParts[1]) : 0;
        $price = floatval($item['price']);

        $itemStmt->bind_param("isid", $quotationId, $drug, $qty, $price);
        $itemStmt->execute();
    }
    $itemStmt->close();

    echo "<script>alert('Quotation sent successfully.'); window.location.href = '../pharmacy_dashboard.php';</script>";
} else {
    echo "<script>alert('Invalid request.'); window.location.href = '../index.php';</script>";
}
?>
