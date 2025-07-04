<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    if ($_SESSION['role'] === 'pharmacy') {
        header("Location: views/pharmacy_dashboard.php");
    } else {
        header("Location: views/user_dashboard.php");
    }
    exit;
}

header("Location: views/login.php");
exit;

