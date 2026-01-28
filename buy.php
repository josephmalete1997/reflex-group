<?php
declare(strict_types=1);

require_once 'backend/config/db.php';

$plan_id = trim((string)($_GET['plan'] ?? ''));
if ($plan_id !== '') {
    $stmt = $pdo->prepare("SELECT id FROM plans WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $plan_id]);
    if ($stmt->fetch()) {
        header('Location: checkout?buy=' . urlencode($plan_id));
        exit;
    }
}
?>
<?php include 'includes/header.php'; ?>

<main>
    <div class="buy-container">
        <h1>Buy Plans Online</h1>
        <p>Use the building plans store to purchase a plan with PayPal.</p>
        <p><a href="./">&larr; Back to Home</a></p>
    </div>
</main>

<style>
    .buy-container { max-width: 520px; background: #fff; margin: 80px auto; border-radius: 14px; box-shadow: 0 4px 32px rgba(30,50,80,0.13); padding: 32px; }
    .buy-container h1 { margin-bottom: 16px; }
    .buy-container p { margin-bottom: 16px; }
</style>

<?php include 'includes/footer.php'; ?>






