<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'backend/config/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add'])) {
    $plan_id = trim((string)$_GET['add']);
    if ($plan_id !== '' && !in_array($plan_id, $_SESSION['cart'], true)) {
        $check = $pdo->prepare("SELECT id FROM plans WHERE id = :id LIMIT 1");
        $check->execute([':id' => $plan_id]);
        if ($check->fetch()) {
            $_SESSION['cart'][] = $plan_id;
        }
    }
    header('Location: cart');
    exit;
}

if (isset($_GET['remove'])) {
    $remove_id = trim((string)$_GET['remove']);
    $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], fn($id) => $id !== $remove_id));
    header('Location: cart');
    exit;
}

$cart_ids = array_values(array_unique($_SESSION['cart']));
$cart_plans = [];
$total = 0.0;

if ($cart_ids) {
    $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
    $stmt = $pdo->prepare("SELECT id, name, new_price FROM plans WHERE id IN ($placeholders)");
    $stmt->execute($cart_ids);
    $cart_plans = $stmt->fetchAll();
    $total = array_sum(array_map(fn($plan) => (float)$plan['new_price'], $cart_plans));
}
?>
<?php include 'includes/header.php'; ?>

<main>
    <div class="cart-container">
        <h1 style="margin-bottom:25px">Your Cart</h1>
        <?php if (!$cart_plans): ?>
            <p>Your cart is empty! <a href="./">Go back</a> to add a plan.</p>
        <?php else: ?>
            <?php foreach ($cart_plans as $plan): ?>
                <div class="cart-plan">
                    <span class="plan-title"><?php echo htmlspecialchars($plan['name']); ?></span>
                    <span class="plan-price">R<?php echo number_format((float)$plan['new_price'], 2); ?></span>
                    <a href="?remove=<?php echo urlencode((string)$plan['id']); ?>" class="remove-btn">Remove</a>
                </div>
            <?php endforeach; ?>
            <div class="text-right" style="margin-top:16px;font-size:1.3em;">
                <b>Total: R<?php echo number_format($total, 2); ?></b>
            </div>

            <div class="checkout-actions">
                <a href="checkout" class="checkout-btn">Proceed to Checkout</a>
                <div class="checkout-note">Pay securely with PayPal at checkout.</div>
            </div>
        <?php endif; ?>
        <p style="margin-top:28px"><a href="./">&larr; Continue shopping</a></p>
    </div>
</main>

<style>
    .cart-container{max-width:700px;margin:50px auto;background:#fff;border-radius:12px;box-shadow:0 2px 24px rgba(50,80,130,0.07);padding:36px;}
    .cart-plan{display:flex;justify-content:space-between;align-items:center;padding:18px 0;border-bottom:1px solid #eee;gap:12px;flex-wrap:wrap;}
    .cart-plan:last-child{border:none;}
    .plan-title{font-weight:600;}
    .plan-price{font-weight:700;font-size:1.1em;}
    .remove-btn{background:#ff5555;color:#fff;padding:5px 18px;border:none;border-radius:5px;cursor:pointer;text-decoration:none;}
    .checkout-btn{background:#143c6f;color:#fff;padding:14px 44px;border:none;font-weight:700;font-size:1.1em;border-radius:7px;cursor:pointer;margin-top:18px;display:inline-flex;justify-content:center;text-decoration:none;}
    .checkout-actions{display:flex;flex-direction:column;align-items:flex-end;}
    .checkout-note{margin-top:8px;color:#6c757d;font-size:0.95em;}
    .text-right{text-align:right;}
    @media (max-width: 640px){
        .checkout-actions{align-items:flex-start;}
    }
</style>

<?php include 'includes/footer.php'; ?>




