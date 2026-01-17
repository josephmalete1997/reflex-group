<?php
require 'plans.php';
// Demo cart session
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (isset($_GET['add'])) {
    $plan_id = $_GET['add'];
    if (!in_array($plan_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $plan_id;
    }
    header('Location: cart'); exit;
}
if (isset($_GET['remove'])) {
    $_SESSION['cart'] = array_diff($_SESSION['cart'], [$_GET['remove']]);
    header('Location: cart'); exit;
}

$cart_plans = array_filter($plans, function($plan) { return in_array($plan['id'], $_SESSION['cart']); });
$total = array_sum(array_column($cart_plans, 'new_price'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - Reflex Perspectives</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        .cart-container{max-width:700px;margin:50px auto;background:#fff;border-radius:12px;box-shadow:0 2px 24px rgba(50,80,130,0.07);padding:36px;}
        .cart-plan{display:flex;justify-content:space-between;align-items:center;padding:18px 0;border-bottom:1px solid #eee;}
        .cart-plan:last-child{border:none;}
        .plan-title{font-weight:600;}
        .plan-price{font-weight:700;font-size:1.1em;}
        .remove-btn{background:#ff5555;color:#fff;padding:5px 18px;border:none;border-radius:5px;cursor:pointer;}
        .checkout-btn{background:#143c6f;color:#fff;padding:14px 44px;border:none;font-weight:700;font-size:1.2em;border-radius:7px;cursor:pointer;margin-top:28px;}
        .text-right{text-align:right;}
    </style>
</head>
<body>
    <div class="cart-container">
        <h1 style="margin-bottom:25px">Your Cart</h1>
        <?php if (count($cart_plans)===0): ?>
            <p>Your cart is empty! <a href="./">Go back</a> to add a plan.</p>
        <?php else: ?>
        <?php foreach($cart_plans as $plan): ?>
            <div class="cart-plan">
                <span class="plan-title"><?php echo $plan['name']; ?></span>
                <span class="plan-price">R<?php echo number_format($plan['new_price'],2); ?></span>
                <a href="?remove=<?php echo $plan['id']; ?>" class="remove-btn">Remove</a>
            </div>
        <?php endforeach; ?>
        <div class="text-right" style="margin-top:16px;font-size:1.3em;">
            <b>Total: R<?php echo number_format($total,2); ?></b>
        </div>
        <form method="post">
            <input type="text" name="name" placeholder="Full Name" required style="width:49%;margin-top:24px;padding:11px;border-radius:5px;border:1px solid #bbb;">
            <input type="email" name="email" placeholder="Email" required style="width:49%;margin-top:24px;margin-left:2%;padding:11px;border-radius:5px;border:1px solid #bbb;">
            <button type="submit" class="checkout-btn">Checkout</button>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            echo "<p style='color:green;margin-top:15px'>Thank you, ".htmlspecialchars($_POST['name'])."! Your order was received. We'll contact you at ".htmlspecialchars($_POST['email'])." soon.</p>";
            $_SESSION['cart'] = [];
        }
        ?>
        <?php endif; ?>
        <p style="margin-top:28px"><a href="./">&larr; Continue shopping</a></p>
    </div>
</body>
</html>






