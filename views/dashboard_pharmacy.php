<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pharmacy') {
    header('Location: login.php');
    exit;
}

$result = $conn->query("SELECT p.*, u.name FROM prescriptions p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <title>Uploaded Prescriptions</title>
</head>
<body>
<div class="container mt-4">
  <h3 class="mb-4">Uploaded Prescriptions</h3>

  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title">User: <?php echo htmlspecialchars($row['name']); ?></h5>
          <p class="card-text"><strong>Note:</strong> <?php echo nl2br(htmlspecialchars($row['note'])); ?></p>
          <p class="card-text"><strong>Delivery Address:</strong> <?php echo htmlspecialchars($row['delivery_address']); ?></p>
          <p class="card-text"><strong>Time Slot:</strong> <?php echo htmlspecialchars($row['delivery_time']); ?></p>
          
          <?php
            $pid = intval($row['id']);
            $quoteRes = $conn->query("SELECT status FROM quotations WHERE prescription_id = $pid LIMIT 1");
          ?>

          <?php if ($quoteRes && $quoteRes->num_rows > 0): 
            $qrow = $quoteRes->fetch_assoc();
          ?>
            <p class="card-text"><strong>Status:</strong> <?php echo ucfirst(htmlspecialchars($qrow['status'])); ?></p>
          <?php else: ?>
            <p class="card-text"><strong>Status:</strong> No quotation yet</p>
            <a href="index.php?page=quotation_form&pid=<?php echo $pid; ?>" class="btn btn-primary btn-sm">Prepare Quotation</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert alert-warning">No prescriptions found.</div>
  <?php endif; ?>
</div>
</body>
</html>
