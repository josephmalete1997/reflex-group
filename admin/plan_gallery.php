<?php
require_once __DIR__ . '/partials/admin_header.php';
admin_require_login();

function upload_gallery_image(string $field): ?string {
  if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return null;
  if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;

  $tmp = $_FILES[$field]['tmp_name'];
  $mime = mime_content_type($tmp);
  $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
  if (!isset($allowed[$mime])) return null;
  if ($_FILES[$field]['size'] > 2 * 1024 * 1024) return null;

  $ext = $allowed[$mime];
  $name = 'gallery_' . bin2hex(random_bytes(10)) . '.' . $ext;

  $destDir = __DIR__ . '/../uploads/plans';
  if (!is_dir($destDir)) mkdir($destDir, 0775, true);
  $destPath = $destDir . '/' . $name;
  if (!move_uploaded_file($tmp, $destPath)) return null;

  return 'uploads/plans/' . $name;
}

$id = trim((string)($_GET['id'] ?? ''));
if ($id === '') { header("Location: plans.php"); exit; }

$p = $pdo->prepare("SELECT id,name FROM plans WHERE id=:id");
$p->execute([':id'=>$id]);
$plan = $p->fetch();
if (!$plan) { header("Location: plans.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $img = upload_gallery_image('image_upload') ?? trim((string)($_POST['image_url'] ?? ''));
  if ($img !== '') {
    $ins = $pdo->prepare("INSERT INTO plan_gallery (plan_id, image) VALUES (:id,:img)");
    $ins->execute([':id'=>$id,':img'=>$img]);
  }
  header("Location: plan_gallery.php?id=" . urlencode($id));
  exit;
}

if (isset($_GET['del'])) {
  $gid = (int)$_GET['del'];
  $del = $pdo->prepare("DELETE FROM plan_gallery WHERE id=:gid AND plan_id=:id");
  $del->execute([':gid'=>$gid,':id'=>$id]);
  header("Location: plan_gallery.php?id=" . urlencode($id));
  exit;
}

$list = $pdo->prepare("SELECT id, image FROM plan_gallery WHERE plan_id=:id ORDER BY id ASC");
$list->execute([':id'=>$id]);
$images = $list->fetchAll();
?>
<h1>Gallery</h1>
<div class="card">
  <div class="notice"><strong><?php echo e($plan['name']); ?></strong> (<?php echo e($plan['id']); ?>)</div>

  <form method="post" enctype="multipart/form-data" class="grid two">
    <div>
      <label>Image URL</label>
      <input name="image_url" placeholder="plans/pl1.png or uploads/plans/...">
      <button class="btn ghost" type="submit" style="margin-top:10px;">Add URL</button>
    </div>
    <div>
      <label>Upload Image</label>
      <input type="file" name="image_upload" accept=".jpg,.jpeg,.png,.webp">
      <button class="btn primary" type="submit" style="margin-top:10px;">Upload</button>
    </div>
  </form>

  <div style="margin-top:14px;" class="actions">
    <a class="btn ghost" href="plans.php">Back</a>
  </div>

  <table class="table" style="margin-top:12px;">
    <thead><tr><th>Preview</th><th>Path</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($images as $img): ?>
        <tr>
          <td><img class="thumb" src="<?php echo e($img['image']); ?>" alt=""></td>
          <td><?php echo e($img['image']); ?></td>
          <td>
            <a class="btn danger" href="?id=<?php echo urlencode($id); ?>&del=<?php echo (int)$img['id']; ?>" onclick="return confirm('Delete image?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>
