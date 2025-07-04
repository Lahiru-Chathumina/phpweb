<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Pharmacy System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php include 'navbar.php'; ?>

<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Pharmacy System Login</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['login_errors'])): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($_SESSION['login_errors'] as $error): ?>
                                <div><?= $error ?></div>
                            <?php endforeach; unset($_SESSION['login_errors']); ?>
                        </div>
                    <?php endif; ?>
                    <form action="../actions/login_check.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required />
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg">Login</button>
                        </div>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="#">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
