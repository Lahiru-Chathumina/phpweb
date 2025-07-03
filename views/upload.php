<?php
if ($_SESSION['role'] !== 'user') die("Unauthorized");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Upload Prescription</h4>
    </div>
    <div class="card-body">
      <form method="post" action="actions/upload_save.php" enctype="multipart/form-data">
        
        <div class="mb-3">
          <label for="prescriptions" class="form-label">Prescription Files</label>
          <input type="file" class="form-control" name="prescriptions[]" id="prescriptions" multiple required>
        </div>

        <div class="mb-3">
          <label for="note" class="form-label">Note</label>
          <textarea class="form-control" name="note" id="note" rows="3" placeholder="Enter any additional note"></textarea>
        </div>

        <div class="mb-3">
          <label for="delivery_address" class="form-label">Delivery Address</label>
          <input type="text" class="form-control" name="delivery_address" id="delivery_address" placeholder="Enter delivery address" required>
        </div>

        <div class="mb-3">
          <label for="delivery_time" class="form-label">Preferred Delivery Time</label>
          <select class="form-select" name="delivery_time" id="delivery_time" required>
            <option value="">Select Time Slot</option>
            <option value="8am - 10am">8am - 10am</option>
            <option value="10am - 12pm">10am - 12pm</option>
          </select>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-success">Upload</button>
        </div>

      </form>
    </div>
  </div>
</div>
