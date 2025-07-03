<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
          <h4 class="mb-0">Login</h4>
        </div>
        <div class="card-body">
          <form method="post" action="actions/login_check.php">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-success">Login</button>
            </div>
          </form>
        </div>
        <div class="card-footer text-center">
          <small>Don't have an account? <a href="views/register.php">Register</a></small>
        </div>
      </div>
    </div>
  </div>
</div>
