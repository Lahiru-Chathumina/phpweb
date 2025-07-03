<?php
include 'db.php';
session_start();
$page = $_GET['page'] ?? 'login';

function loadView($view) {
  $file = "views/$view.php";
  if (file_exists($file)) {
    include $file;
  } else {
    echo "<h3 class='text-danger'>Page not found!</h3>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Medical Prescription System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<?php include 'views/navbar.php';?>


<div class="container mt-4">
  <?php loadView($page); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
