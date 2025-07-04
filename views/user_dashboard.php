<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'user') {
    header("Location: ../index.php");
    exit;
}

include 'navbar.php';
require_once '../db.php';

$user_id = $_SESSION['user_id'];
$session_id = session_id();

$preview_items = $conn->query("
    SELECT * FROM quotations_preview WHERE session_id = '$session_id'
");

$quotations = $conn->query("
    SELECT q.*, p.id AS prescription_id 
    FROM quotations q 
    JOIN prescriptions p ON q.prescription_id = p.id 
    WHERE p.user_id = $user_id
    ORDER BY q.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-primary">User Dashboard</h2>
    <div class="alert alert-info">
        Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!
    </div>

    <h4>Current Quotation Preview</h4>
    <?php if ($preview_items->num_rows > 0): ?>
        <table class="table table-bordered mb-4">
            <thead class="table-light">
                <tr>
                    <th>Drug</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                    <th>Added At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $preview_total = 0;
                while ($item = $preview_items->fetch_assoc()):
                    $preview_total += $item['amount'];
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['drug_name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['unit_price'], 2) ?></td>
                        <td><?= number_format($item['amount'], 2) ?></td>
                        <td><?= $item['created_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr class="table-info">
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong><?= number_format($preview_total, 2) ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No current quotation preview items found.</div>
    <?php endif; ?>

    <h4>Your Finalized Quotations</h4>
    <?php if ($quotations->num_rows > 0): ?>
        <?php while ($q = $quotations->fetch_assoc()): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <strong>Prescription ID:</strong> <?= $q['prescription_id'] ?> |
                    <strong>Quotation ID:</strong> <?= $q['id'] ?> |
                    <strong>Status:</strong> 
                    <span class="<?= $q['status'] === 'accepted' ? 'text-success' : ($q['status'] === 'rejected' ? 'text-danger' : 'text-warning') ?>">
                        <?= ucfirst($q['status']) ?>
                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Drug</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $qid = $q['id'];
                            $items = $conn->query("SELECT * FROM quotation_items WHERE quotation_id = $qid");
                            while ($item = $items->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($item['drug_name']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= number_format($item['unit_price'], 2) ?></td>
                                <td><?= number_format($item['unit_price'] * $item['quantity'], 2) ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <tr class="table-info">
                                <td colspan="3"><strong>Total</strong></td>
                                <td><strong><?= number_format($q['total_amount'], 2) ?></strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <?php if ($q['status'] === 'pending'): ?>
                        <form method="POST" action="../actions/accept.php" class="d-inline">
                            <input type="hidden" name="qid" value="<?= $q['id'] ?>">
                            <button class="btn btn-success btn-sm">Accept</button>
                        </form>
                        <form method="POST" action="../actions/reject.php" class="d-inline">
                            <input type="hidden" name="qid" value="<?= $q['id'] ?>">
                            <button class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            No quotations found.
        </div>
    <?php endif; ?>
</div>
</body>
</html>
