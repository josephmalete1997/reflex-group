<?php
require_once __DIR__ . '/partials/admin_header.php';
admin_require_login();

$plans = $pdo->query("SELECT id,name,img,new_price,style,bedrooms,stories,created_at FROM plans ORDER BY created_at DESC")->fetchAll();
?>
<h1>Plans</h1>

<div class="actions" style="margin-bottom:12px;">
  <a class="btn primary" href="plan_new.php">+ Add Plan</a>
</div>

<div class="card">
  <table class="table">
    <thead>
      <tr>
        <th>Image</th><th>Name</th><th>Price</th><th>Style</th><th>Specs</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($plans as $p): ?>
      <tr>
        <td><img class="thumb" src="../<?php echo e($p['img']); ?>" alt=""></td>
        <td>
          <strong><?php echo e($p['name']); ?></strong><br>
          <span class="badge"><?php echo e($p['id']); ?></span>
        </td>
        <td>R<?php echo number_format((float)$p['new_price'], 2); ?></td>
        <td><?php echo e($p['style']); ?></td>
        <td><?php echo (int)$p['bedrooms']; ?> bed • <?php echo (int)$p['stories']; ?> level</td>
        <td class="actions">
          <a class="btn ghost" href="plan_edit.php?id=<?php echo urlencode($p['id']); ?>">Edit</a>
          <a class="btn ghost" href="plan_features.php?id=<?php echo urlencode($p['id']); ?>">Features</a>
          <a class="btn ghost" href="plan_gallery.php?id=<?php echo urlencode($p['id']); ?>">Gallery</a>
          <a class="btn danger" href="plan_delete.php?id=<?php echo urlencode($p['id']); ?>" onclick="return confirm('Delete this plan?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>
