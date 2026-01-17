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
  <link rel="shortcut icon" type="image/png" href="images/logo.png">

  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header class="site-header">
  <div class="container">
    <div class="logo">
      <img src="images/logo.png" alt="logo">
    </div>

    <button id="menu-mobile-bars" class="menu-btn" aria-label="Toggle menu" aria-expanded="false" type="button">
      <i class="fa-solid fa-bars"></i>
    </button>

    <nav class="menu" id="main-menu" aria-label="Main navigation">
      <ul>
        <li><a href="./">Home</a></li>
        <li><a href="our_work">Architecture | Engineering | Construction</a></li>
        <li><a href="contact">Contact</a></li>
      </ul>
    </nav>

    <a href="cart" class="cart-link">
      <i class="fa-solid fa-cart-shopping cart-icon"></i>
      <span class="cart-count"><?php echo $cartCount; ?></span>
    </a>
  </div>
</header>

<style>
  /* Mobile menu button hidden on desktop */
  #menu-mobile-bars {
    display: none;
    background: transparent;
    border: 0;
    font-size: 1.6rem;
    cursor: pointer;
    color: inherit;
    margin-right:10px;
  }

  /* Mobile menu open state */
  .menu.open-menu {
    display: flex;
  }

  @media (max-width: 600px) {
    #menu-mobile-bars {
      display: block;
    }

    /* Hide menu by default on mobile */
    .menu {
      display: none;
      width: 100%;
      margin-top: 10px;
    }

    .menu ul {
      flex-wrap: wrap;
      justify-content: center;
      margin-right: 4px;
      flex-direction: column;
      gap: 10px;
    }

    .info-section {
      padding: 32px 20px;
      margin: 24px 12px;
    }
  }

/* Make header container a positioning context */
header.site-header .container{
  position: relative;
}

/* Cart pinned to top-right */
.cart-link{
  position: absolute;
  top: 12px;     /* adjust */
  right: 12px;   /* adjust */
  display: inline-flex;
  align-items: center;
  gap: 8px;
  z-index: 20;
  text-decoration: none;
}

/* Optional: make the count look nicer */
.cart-count{
  display: inline-grid;
  place-items: center;
  min-width: 20px;
  height: 20px;
  padding: 0 6px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 700;
  background: #0b3c5d;
  color: #fff;
}

/* Keep menu button from overlapping the cart on mobile */
@media (max-width: 600px){
  #menu-mobile-bars{
    position: absolute;
    top: 10px;
    right: 60px; /* leaves space for cart */
    z-index: 21;
  }

  /* Add top padding so content doesn't sit under the icons */
  header.site-header .container{
    padding-top: 52px;
  }
}
</style>

<script>
  // Sticky header shadow
  window.addEventListener('scroll', () => {
    const header = document.querySelector('header.site-header');
    header.classList.toggle('scrolled', window.scrollY > 50);
  });

  // Mobile menu toggle
  const menu = document.getElementById('main-menu');
  const btn = document.getElementById('menu-mobile-bars');

  btn.addEventListener('click', (e) => {
    e.stopPropagation();
    const isOpen = menu.classList.toggle('open-menu');
    btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    btn.innerHTML = isOpen
      ? '<i class="fa-solid fa-xmark"></i>'
      : '<i class="fa-solid fa-bars"></i>';
  });

  // Close menu when a link is clicked
  menu.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => {
      menu.classList.remove('open-menu');
      btn.setAttribute('aria-expanded', 'false');
      btn.innerHTML = '<i class="fa-solid fa-bars"></i>';
    });
  });

  // Close menu when clicking outside
  document.addEventListener('click', (e) => {
    const clickedInside = menu.contains(e.target) || btn.contains(e.target);
    if (!clickedInside) {
      menu.classList.remove('open-menu');
      btn.setAttribute('aria-expanded', 'false');
      btn.innerHTML = '<i class="fa-solid fa-bars"></i>';
    }
  });
</script>


