<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">User Registration</h4>
    </div>
    <div class="card-body">

      <?php
      if (!empty($_SESSION['errors'])) {
          foreach ($_SESSION['errors'] as $error) {
              echo "<div class='alert alert-danger'>$error</div>";
          }
          unset($_SESSION['errors']);
      }

      if (!empty($_SESSION['success'])) {
          echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
          unset($_SESSION['success']);
      }
      ?>

      <form method="post" action="../actions/register_save.php">
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">Address</label>
          <input type="text" class="form-control" id="address" name="address" required>
        </div>

        <div class="mb-3">
          <label for="contact" class="form-label">Contact Number</label>
          <input type="text" class="form-control" id="contact" name="contact" required>
        </div>

        <div class="mb-3">
          <label for="dob" class="form-label">Date of Birth</label>
          <input type="date" class="form-control" id="dob" name="dob" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
          <label for="role" class="form-label">User Type</label>
          <select class="form-select" id="role" name="role" required>
            <option value="">Select Role</option>
            <option value="user">User</option>
            <option value="pharmacy">Pharmacy</option>
          </select>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-success">Register</button>
        </div>
      </form>
    </div>
  </div>  
</div>