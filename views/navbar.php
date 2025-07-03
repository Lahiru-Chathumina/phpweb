<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="index.php">Med Prescription</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['role'])): ?>
          <?php if ($_SESSION['role'] === 'user'): ?>
            <li class="nav-item">
              <a class="nav-link <?= $page === 'dashboard_user' ? 'active' : '' ?>" href="index.php?page=dashboard_user">User Dashboard</a>
            </li>
          <?php elseif ($_SESSION['role'] === 'pharmacy'): ?>
            <li class="nav-item">
              <a class="nav-link <?= $page === 'dashboard_pharmacy' ? 'active' : '' ?>" href="index.php?page=dashboard_pharmacy">Pharmacy Dashboard</a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="actions/logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link <?= $page === 'register' ? 'active' : '' ?>" href="index.php?page=register">Register</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $page === 'login' ? 'active' : '' ?>" href="index.php?page=login">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>