<?php
require_once __DIR__ . '/partials/admin_header.php';
admin_require_login();

$total = (int)$pdo->query("SELECT COUNT(*) c FROM plans")->fetch()['c'];
$latest = $pdo->query("SELECT id,name,new_price,created_at FROM plans ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<h1>Dashboard</h1>

<div class="grid two">
  <div class="card">
    <div class="badge">Total Plans</div>
    <h2 style="margin:10px 0;"><?php echo $total; ?></h2>
    <a class="btn primary" href="plans.php">Manage Plans</a>
  </div>

  <div class="card">
    <div class="badge">Logged in</div>
    <p style="margin:10px 0;color:var(--muted);font-weight:800;">
      <?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?>
    </p>
  </div>
</div>

<div class="card" style="margin-top:14px;">
  <h2 style="margin:0 0 10px;">Latest Plans</h2>
  <table class="table">
    <thead><tr><th>Name</th><th>Price</th><th>Date</th></tr></thead>
    <tbody>
      <?php foreach ($latest as $p): ?>
        <tr>
          <td><?php echo e($p['name']); ?></td>
          <td>R<?php echo number_format((float)$p['new_price'], 2); ?></td>
          <td><?php echo e($p['created_at']); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>