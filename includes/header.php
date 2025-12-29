<?php
session_start();
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reflex perspective | Home</title>
    <link rel="stylesheet" href="styles/style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header>
    <div class="container">
        <div class="logo">
            <img src="images/logo.png" alt="logo" height="100px">
        </div>
        <div class="menu">
            <ul>
                <li><a href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="about.php"><i class="fa-solid fa-building"></i> Architecture</a></li>
                <li><a href="services.php"><i class="fa-solid fa-gears"></i> Engineering</a></li>
                <li><a href="contact.php"><i class="fa-solid fa-helmet-safety"></i> Construction</a></li>
                <li><a href="contact.php"><i class="fa-solid fa-envelope"></i> Contact</a></li>
                <li><a href="cart.php" class="cart-link"><i class="fa-solid fa-cart-shopping cart-icon"></i> <span class="cart-count"><?php echo $cartCount; ?></span></a></li>
            </ul>
        </div>
    </div>
</header>