<?php
session_start();
$username = $_SESSION['username'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <title>User Dashboard</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-light">
  <div class="container">
    <a class="navbar-brand" href="#">MedApp</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="upload_prescription.php">Upload Prescription</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="view_quotations.php">View Quotations</a>
        </li>
      </ul>
      <span class="navbar-text">
        Welcome, <?php echo htmlspecialchars($username); ?> |
        <a href="logout.php">Logout</a>
      </span>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <div class="alert alert-info text-center" role="alert">
    <h3 class="alert-heading">Welcome <?php echo htmlspecialchars($username); ?>!</h3>
    <p>Use the navigation bar to upload a prescription or view your quotations.</p>
  </div>
</div>

</body>
</html>
