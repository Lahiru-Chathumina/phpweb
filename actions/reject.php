<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qid = intval($_POST['qid']);
    $conn->query("UPDATE quotations SET status = 'rejected' WHERE id = $qid");
    header("Location: ../user/dashboard.php");
    exit;
}
