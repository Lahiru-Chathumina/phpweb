<?php
session_start();
$conn = new mysqli("localhost", "root", "", "med_db");

$uid = $_SESSION['user_id'];
$res = $conn->query("SELECT q.*, p.note FROM quotations q
                     JOIN prescriptions p ON q.prescription_id = p.id
                     WHERE p.user_id = $uid");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
  <h3 class="text-primary">My Quotations</h3>

  <?php while ($q = $res->fetch_assoc()) : ?>
    <div class="card my-4 shadow">
      <div class="card-body">
        <p><strong>Note:</strong> <?= htmlspecialchars($q['note']) ?></p>

        <div class="table-responsive">
          <table class="table table-bordered table-hover">
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
              while ($i = $items->fetch_assoc()) :
              ?>
                <tr>
                  <td><?= htmlspecialchars($i['drug']) ?></td>
                  <td><?= $i['qty'] ?></td>
                  <td>Rs. <?= number_format($i['price'], 2) ?></td>
                  <td>Rs. <?= number_format($i['amount'], 2) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <?php if ($q['status'] === 'pending') : ?>
          <div class="mt-3">
            <a href="actions/accept.php?qid=<?= $qid ?>" class="btn btn-success btn-sm">Accept</a>
            <a href="actions/reject.php?qid=<?= $qid ?>" class="btn btn-danger btn-sm">Reject</a>
          </div>
        <?php else : ?>
          <p class="mt-3">Status: 
            <span class="badge bg-<?= $q['status'] === 'accepted' ? 'success' : 'secondary' ?>">
              <?= ucfirst($q['status']) ?>
            </span>
          </p>
        <?php endif; ?>
      </div>
    </div>
  <?php endwhile; ?>
</div>
