<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'pharmacy') {
    header("Location: ../index.php");
    exit;
}

include 'navbar.php';
require_once '../db.php';

$session_id = session_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_images']) && isset($_FILES['prescription_images'])) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    foreach ($_FILES['prescription_images']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['prescription_images']['error'][$key] === UPLOAD_ERR_OK) {
            $fileName = uniqid() . "_" . basename($_FILES['prescription_images']['name'][$key]);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $filePath)) {
                $filePathDb = $conn->real_escape_string('uploads/' . $fileName);
                $conn->query("INSERT INTO prescription_preview_images (session_id, image_path) VALUES ('$session_id', '$filePathDb')");
            }
        }
    }
    echo "<script>alert('Images uploaded successfully!');</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_drug'])) {
    $drug = $conn->real_escape_string($_POST['drug']);
    $qty = intval($_POST['quantity']);
    $unit_price = floatval($_POST['unit_price']);
    $amount = $qty * $unit_price;

    $conn->query("INSERT INTO quotations_preview (session_id, drug_name, quantity, unit_price, amount) 
                  VALUES ('$session_id', '$drug', $qty, $unit_price, $amount)");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_quotation'])) {
    $drugs = $conn->query("SELECT * FROM quotations_preview WHERE session_id = '$session_id'");
    
    if ($drugs->num_rows > 0) {
        $total_amount = 0;

        $conn->query("INSERT INTO prescriptions (user_id) VALUES (0)");
        $prescription_id = $conn->insert_id;

        while ($drug = $drugs->fetch_assoc()) {
            $total_amount += $drug['amount'];
        }

        $conn->query("INSERT INTO quotations (prescription_id, total_amount) VALUES ($prescription_id, $total_amount)");
        $quotation_id = $conn->insert_id;

        $drugs->data_seek(0);
        while ($drug = $drugs->fetch_assoc()) {
            $drug_name = $conn->real_escape_string($drug['drug_name']);
            $qty = $drug['quantity'];
            $unit_price = $drug['unit_price'];
            $conn->query("INSERT INTO quotation_items (quotation_id, drug_name, quantity, unit_price) 
                          VALUES ($quotation_id, '$drug_name', $qty, $unit_price)");
        }

        $conn->query("DELETE FROM quotations_preview WHERE session_id = '$session_id'");
        $conn->query("DELETE FROM prescription_preview_images WHERE session_id = '$session_id'");

        echo "<script>alert('Quotation sent successfully!'); window.location='pharmacy_dashboard.php';</script>";
        exit;
    } else {
        echo "<script>alert('No drugs found in preview to send!');</script>";
    }
}

$result = $conn->query("SELECT * FROM quotations_preview WHERE session_id = '$session_id'");
$images = $conn->query("SELECT * FROM prescription_preview_images WHERE session_id = '$session_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Pharmacy Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
    <h2 class="text-primary">Pharmacy Dashboard</h2>
    <div class="alert alert-success">
        Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!
    </div>

    <h6>Upload Prescription Images (Max 5)</h6>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <input type="file" name="prescription_images[]" multiple accept="image/*" class="form-control mb-2" required />
        <button type="submit" name="upload_images" class="btn btn-secondary">Upload Images</button>
    </form>

    <div class="mb-3">
        <strong>Uploaded Images:</strong><br>
        <?php while ($img = $images->fetch_assoc()): ?>
            <img src="<?= htmlspecialchars($img['image_path']) ?>" width="100" class="img-thumbnail me-2 mb-2" />
        <?php endwhile; ?>
    </div>

    <h6>Add Drug</h6>
    <form method="POST" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="drug" class="form-control" placeholder="Drug" required />
        </div>
        <div class="col-md-3">
            <input type="number" name="quantity" class="form-control" placeholder="Qty" min="1" required />
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" name="unit_price" class="form-control" placeholder="Unit Price" min="0" required />
        </div>
        <div class="col-md-2">
            <button type="submit" name="add_drug" class="btn btn-primary w-100">Add</button>
        </div>
    </form>

    <h6>Quotation Preview</h6>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Drug</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            if ($result->num_rows > 0):
                while ($item = $result->fetch_assoc()):
                    $total += $item['amount'];
            ?>
            <tr>
                <td><?= htmlspecialchars($item['drug_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['unit_price'], 2) ?></td>
                <td><?= number_format($item['amount'], 2) ?></td>
            </tr>
            <?php endwhile; else: ?>
            <tr>
                <td colspan="4" class="text-center">No items added.</td>
            </tr>
            <?php endif; ?>
            <tr class="table-info">
                <td colspan="3"><strong>Total</strong></td>
                <td><strong><?= number_format($total, 2) ?></strong></td>
            </tr>
        </tbody>
    </table>

    <?php if ($total > 0): ?>
    <form method="POST">
        <button type="submit" name="send_quotation" class="btn btn-success">Send Quotation</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>