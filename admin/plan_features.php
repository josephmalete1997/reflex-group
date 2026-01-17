<?php
require_once __DIR__ . '/partials/admin_header.php';
admin_require_login();

$id = trim((string)($_GET['id'] ?? ''));
if ($id === '') { header("Location: plans.php"); exit; }

$p = $pdo->prepare("SELECT id,name FROM plans WHERE id=:id");
$p->execute([':id'=>$id]);
$plan = $p->fetch();
if (!$plan) { header("Location: plans.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $feature = trim((string)($_POST['feature'] ?? ''));
  if ($feature !== '') {
    $ins = $pdo->prepare("INSERT INTO plan_features (plan_id, feature) VALUES (:id,:f)");
    $ins->execute([':id'=>$id,':f'=>$feature]);
  }
  header("Location: plan_features.php?id=" . urlencode($id));
  exit;
}

if (isset($_GET['del'])) {
  $fid = (int)$_GET['del'];
  $del = $pdo->prepare("DELETE FROM plan_features WHERE id=:fid AND plan_id=:id");
  $del->execute([':fid'=>$fid,':id'=>$id]);
  header("Location: plan_features.php?id=" . urlencode($id));
  exit;
}

$list = $pdo->prepare("SELECT id, feature FROM plan_features WHERE plan_id=:id ORDER BY id ASC");
$list->execute([':id'=>$id]);
$features = $list->fetchAll();
?>
<h1>Features</h1>
<div class="card">
  <div class="notice"><strong><?php echo e($plan['name']); ?></strong> (<?php echo e($plan['id']); ?>)</div>

  <form method="post" class="actions">
    <input name="feature" placeholder="Add a feature..." required>
    <button class="btn primary" type="submit">Add</button>
    <a class="btn ghost" href="plans.php">Back</a>
  </form>

  <table class="table" style="margin-top:12px;">
    <thead><tr><th>Feature</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($features as $f): ?>
        <tr>
          <td><?php echo e($f['feature']); ?></td>
          <td><a class="btn danger" href="?id=<?php echo urlencode($id); ?>&del=<?php echo (int)$f['id']; ?>" onclick="return confirm('Delete feature?')">Delete</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>
