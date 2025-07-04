<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? null;  
$user_name = $_SESSION['user_name'] ?? 'Guest';  
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <?= ($role === 'pharmacy') ? 'Pharmacy Panel' : 'User Panel' ?>
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Dashboard</a>
                </li>
            </ul>
            <span class="navbar-text text-white me-3">
                <?= htmlspecialchars($user_name) ?>
            </span>
            <a href="../actions/logout.php" class="btn btn-outline-light me-2">Logout</a>
            <a href="register.php" class="btn btn-outline-light">Register</a>
        </div>
    </div>
</nav>
