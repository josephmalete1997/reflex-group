<?php
$plans = [
    "starter" => ["title" => "Starter Plan", "price" => 1500],
    "professional" => ["title" => "Professional Plan", "price" => 5000],
    "premium" => ["title" => "Premium Plan", "price" => 12000],
];

$selected = $_GET['plan'] ?? '';
if (!isset($plans[$selected])) {
    echo "<h2>Invalid plan selected.</h2>";
    exit;
}
$plan = $plans[$selected];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy <?php echo $plan['title']; ?> - Reflex Perspectives</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        .buy-container { max-width: 450px; background: #fff; margin: 80px auto; border-radius: 14px; box-shadow: 0 4px 32px rgba(30,50,80,0.13); padding: 32px; }
        .buy-container h1 { margin-bottom: 16px; }
        .buy-container p { margin-bottom: 28px; }
        .buy-form input { width: 100%; padding: 12px; border-radius: 5px; border: 1px solid #bdbdbd; margin-bottom: 16px; }
        .buy-form button { width: 100%; }
    </style>
</head>
<body>
    <div class="buy-container">
        <h1>Buy <?php echo $plan['title']; ?></h1>
        <p>Price: <b>R<?php echo number_format($plan['price'], 2); ?></b></p>
        <form method="post" class="buy-form">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit" class="cta-btn">Pay Now</button>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<p style='color:green;'>Thank you, ".htmlspecialchars($_POST['name'])."! We will contact you at ".htmlspecialchars($_POST['email'])." to complete your purchase of the ".$plan['title'].".</p>";
        }
        ?>
        <p><a href="/">&larr; Back to Home</a></p>
    </div>
</body>
</html>







