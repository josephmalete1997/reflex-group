<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../../backend/config/db.php';

function admin_require_login(): void {
  if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
  }
}

function e(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Portal | Reflex</title>
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<header class="topbar">
  <div class="wrap topbar-inner">
    <div class="brand">
        <img src="./assets/logo.png" alt="logo" width="20%">
    </div>
    <nav class="nav">
      <?php if (!empty($_SESSION['admin_id'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="plans.php">Plans</a>
        <a href="logout.php">Logout</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="wrap">
