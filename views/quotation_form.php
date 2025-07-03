<?php
$pid = $_GET['pid'];
$conn = new mysqli("localhost", "root", "", "med_db");

$images = $conn->query("SELECT image_path FROM prescription_images WHERE prescription_id = $pid");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
  <h3 class="mb-4 text-primary">Prepare Quotation</h3>

  <form method="post" action="actions/quotation_save.php">
    <input type="hidden" name="prescription_id" value="<?= $pid ?>">
    <input type="hidden" name="drugs_json" id="drugs_json">

    <div class="row">
      <div class="col-md-5">
        <h5>Prescription Image</h5>
        <?php
        $imgArray = [];
        while ($img = $images->fetch_assoc()) {
          $imgArray[] = $img['image_path'];
        }

        if (count($imgArray)) {
          echo "<img src='{$imgArray[0]}' class='img-fluid mb-2 rounded border'>";
          if (count($imgArray) > 1) {
            echo "<div class='d-flex gap-2'>";
            for ($i = 1; $i < count($imgArray); $i++) {
              echo "<img src='{$imgArray[$i]}' width='60' class='img-thumbnail'>";
            }
            echo "</div>";
          }
        } else {
          echo "<p class='text-muted'>No images found</p>";
        }
        ?>
      </div>

      <div class="col-md-7">
        <div class="table-responsive">
          <table class="table table-bordered" id="quoteTable">
            <thead class="table-light">
              <tr>
                <th>Drug</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        <p class="fw-bold">Total: <span id="totalAmount">0.00</span> Rs</p>

        <div class="mb-3">
          <label class="form-label">Drug Name</label>
          <input type="text" class="form-control" id="drug" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Quantity (e.g. 10 x 5)</label>
          <input type="text" class="form-control" id="qty" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Unit Price</label>
          <input type="number" class="form-control" id="price" required>
        </div>

        <div class="d-grid gap-2 d-md-flex mb-3">
          <button type="button" class="btn btn-secondary" onclick="addRow()">Add Item</button>
          <button type="submit" class="btn btn-success">Send Quotation</button>
        </div>
      </div>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const items = [];

function addRow() {
  const drug = document.getElementById('drug').value.trim();
  const qtyText = document.getElementById('qty').value.trim();
  const price = parseFloat(document.getElementById('price').value);

  if (!drug || !qtyText || isNaN(price)) return alert("Please fill all fields correctly.");

  const [x, y] = qtyText.toLowerCase().split("x").map(n => parseFloat(n.trim()));
  if (isNaN(x) || isNaN(y)) return alert("Invalid quantity format. Use format like 10 x 2");

  const amount = x * y * price;
  items.push({ drug, quantity: qtyText, price, amount });

  const table = document.querySelector("#quoteTable tbody");
  const row = document.createElement("tr");
  row.innerHTML = `
    <td>${drug}</td>
    <td>${qtyText}</td>
    <td>${price.toFixed(2)}</td>
    <td>${amount.toFixed(2)}</td>
  `;
  table.appendChild(row);

  const total = items.reduce((sum, i) => sum + i.amount, 0);
  document.getElementById("totalAmount").innerText = total.toFixed(2);

  document.getElementById("drugs_json").value = JSON.stringify(items);

  document.getElementById('drug').value
