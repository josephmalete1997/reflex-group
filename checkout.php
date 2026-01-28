<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'backend/config/db.php';
$paypal = require 'backend/config/paypal.php';

$buy_id = trim((string)($_GET['buy'] ?? ''));
$cart_ids = [];

if ($buy_id !== '') {
    $cart_ids = [$buy_id];
} else {
    $cart_ids = $_SESSION['cart'] ?? [];
}

$cart_ids = array_values(array_unique(array_filter($cart_ids)));
$cart_plans = [];
$total = 0.0;
$buy_not_found = false;

if ($cart_ids) {
    $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
    $stmt = $pdo->prepare("SELECT id, name, new_price FROM plans WHERE id IN ($placeholders)");
    $stmt->execute($cart_ids);
    $cart_plans = $stmt->fetchAll();
    $total = array_sum(array_map(fn($plan) => (float)$plan['new_price'], $cart_plans));
}
if ($buy_id !== '' && !$cart_plans) {
    $buy_not_found = true;
}

$currency = (string)($paypal['currency'] ?? 'ZAR');
$client_id = (string)($paypal['client_id'] ?? '');
?>
<?php include 'includes/header.php'; ?>

<main>
    <section class="checkout-wrap">
        <div class="checkout-card">
            <h1>Checkout</h1>

            <?php if (!$cart_plans): ?>
                <?php if ($buy_not_found): ?>
                    <p class="checkout-empty">That plan is no longer available. <a href="./">Browse plans</a> to choose another one.</p>
                <?php else: ?>
                    <p class="checkout-empty">Your cart is empty. <a href="./">Browse plans</a> to get started.</p>
                <?php endif; ?>
            <?php else: ?>
                <div class="order-summary">
                    <?php foreach ($cart_plans as $plan): ?>
                        <div class="summary-row">
                            <span class="summary-title"><?php echo htmlspecialchars($plan['name']); ?></span>
                            <span class="summary-price">R<?php echo number_format((float)$plan['new_price'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                    <div class="summary-total">
                        <span>Total</span>
                        <span>R<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>

                <?php if ($client_id === ''): ?>
                    <div class="checkout-warning">
                        PayPal is not configured. Set `PAYPAL_CLIENT_ID` in your environment to enable payments.
                    </div>
                <?php else: ?>
                    <div id="paypal-button-container"></div>
                    <div id="paypal-message" class="checkout-message"></div>
                <?php endif; ?>
            <?php endif; ?>

            <p class="checkout-back"><a href="cart">&larr; Back to cart</a></p>
        </div>
    </section>
</main>

<style>
    .checkout-wrap{max-width:760px;margin:50px auto;padding:0 16px;}
    .checkout-card{background:#fff;border-radius:14px;box-shadow:0 2px 24px rgba(50,80,130,0.07);padding:36px;}
    .order-summary{border:1px solid #e9ecef;border-radius:12px;padding:18px;margin:20px 0 24px;}
    .summary-row{display:flex;justify-content:space-between;gap:12px;padding:10px 0;border-bottom:1px solid #f1f3f5;}
    .summary-row:last-child{border-bottom:none;}
    .summary-title{font-weight:600;color:#1f2a37;}
    .summary-price{font-weight:700;}
    .summary-total{display:flex;justify-content:space-between;align-items:center;margin-top:12px;padding-top:12px;border-top:1px solid #e9ecef;font-weight:800;}
    .checkout-warning{background:#fff3cd;border:1px solid #ffecb5;color:#664d03;padding:12px 14px;border-radius:10px;margin-top:16px;}
    .checkout-message{margin-top:16px;color:#0f5132;font-weight:700;}
    .checkout-empty{color:#6c757d;}
    .checkout-back{margin-top:24px;}
</style>

<?php if ($client_id !== '' && $cart_plans): ?>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo htmlspecialchars($client_id); ?>&currency=<?php echo htmlspecialchars($currency); ?>"></script>
    <script>
        const paypalItems = <?php
            $items = array_map(function ($plan) use ($currency) {
                return [
                    'name' => $plan['name'],
                    'unit_amount' => [
                        'currency_code' => $currency,
                        'value' => number_format((float)$plan['new_price'], 2, '.', '')
                    ],
                    'quantity' => '1'
                ];
            }, $cart_plans);
            echo json_encode($items, JSON_UNESCAPED_SLASHES);
        ?>;
        const paypalTotal = "<?php echo number_format($total, 2, '.', ''); ?>";
        const paypalCurrency = "<?php echo htmlspecialchars($currency); ?>";
        const isBuyNow = <?php echo $buy_id !== '' ? 'true' : 'false'; ?>;

        paypal.Buttons({
            createOrder: (data, actions) => {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            currency_code: paypalCurrency,
                            value: paypalTotal,
                            breakdown: {
                                item_total: {
                                    currency_code: paypalCurrency,
                                    value: paypalTotal
                                }
                            }
                        },
                        items: paypalItems
                    }]
                });
            },
            onApprove: (data, actions) => {
                return actions.order.capture().then(details => {
                    const msg = document.getElementById('paypal-message');
                    msg.textContent = `Payment complete. Thanks, ${details.payer.name.given_name}.`;

                    if (!isBuyNow) {
                        fetch('backend/api/cart?action=clear', {credentials: 'same-origin'});
                    }
                });
            },
            onError: (err) => {
                const msg = document.getElementById('paypal-message');
                msg.textContent = 'Payment could not be completed. Please try again.';
                msg.style.color = '#b02a37';
                console.error(err);
            }
        }).render('#paypal-button-container');
    </script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
