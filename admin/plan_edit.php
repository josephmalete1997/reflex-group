<?php
require_once __DIR__ . '/partials/admin_header.php';
admin_require_login();

function upload_plan_image(string $field): ?string {
  if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return null;
  if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;

  $tmp = $_FILES[$field]['tmp_name'];
  $mime = mime_content_type($tmp);
  $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
  if (!isset($allowed[$mime])) return null;
  if ($_FILES[$field]['size'] > 2 * 1024 * 1024) return null;

  $ext = $allowed[$mime];
  $name = 'plan_' . bin2hex(random_bytes(10)) . '.' . $ext;

  $destDir = __DIR__ . '/../uploads/plans';
  if (!is_dir($destDir)) mkdir($destDir, 0775, true);

  $destPath = $destDir . '/' . $name;
  if (!move_uploaded_file($tmp, $destPath)) return null;

  return 'uploads/plans/' . $name;
}

$id = trim((string)($_GET['id'] ?? ''));
if ($id === '') { header("Location: plans.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM plans WHERE id = :id LIMIT 1");
$stmt->execute([':id'=>$id]);
$plan = $stmt->fetch();
if (!$plan) { header("Location: plans.php"); exit; }

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim((string)($_POST['name'] ?? ''));
  $short_desc = trim((string)($_POST['short_desc'] ?? ''));
  $full_desc = trim((string)($_POST['full_desc'] ?? ''));
  $old_price = (float)($_POST['old_price'] ?? 0);
  $new_price = (float)($_POST['new_price'] ?? 0);
  $bedrooms = (int)($_POST['bedrooms'] ?? 0);
  $bathrooms = (int)($_POST['bathrooms'] ?? 0);
  $garage = (int)($_POST['garage'] ?? 0);
  $sqm = (float)($_POST['sqm'] ?? 0);
  $stories = (int)($_POST['stories'] ?? 1);
  $style = trim((string)($_POST['style'] ?? ''));
  $dimensions = trim((string)($_POST['dimensions'] ?? ''));
  $floor_plan = trim((string)($_POST['floor_plan'] ?? ''));

  $img = upload_plan_image('img_upload') ?? trim((string)($_POST['img'] ?? ''));
  if ($img === '') $img = $plan['img'];

  if ($name === '' || $short_desc === '' || $style === '' || $dimensions === '') {
    $err = "Please fill required fields.";
  } else {
    $u = $pdo->prepare("
      UPDATE plans SET
        name=:name, img=:img, short_desc=:short_desc, full_desc=:full_desc,
        old_price=:old_price, new_price=:new_price,
        bedrooms=:bedrooms, bathrooms=:bathrooms, garage=:garage,
        sqm=:sqm, stories=:stories, style=:style, dimensions=:dimensions,
        floor_plan=:floor_plan
      WHERE id=:id
    ");
    $u->execute([
      ':id'=>$id, ':name'=>$name, ':img'=>$img, ':short_desc'=>$short_desc, ':full_desc'=>$full_desc,
      ':old_price'=>$old_price, ':new_price'=>$new_price, ':bedrooms'=>$bedrooms, ':bathrooms'=>$bathrooms,
      ':garage'=>$garage, ':sqm'=>$sqm, ':stories'=>$stories, ':style'=>$style, ':dimensions'=>$dimensions,
      ':floor_plan'=>($floor_plan !== '' ? $floor_plan : null),
    ]);
    header("Location: plans.php");
    exit;
  }
}
?>
<h1>Edit Plan</h1>
<?php if ($err): ?><div class="err"><?php echo e($err); ?></div><?php endif; ?>

<div class="card">
<form method="post" enctype="multipart/form-data">
  <div class="notice">Editing: <strong><?php echo e($plan['id']); ?></strong></div>

  <label>Name *</label>
  <input name="name" required value="<?php echo e($plan['name']); ?>">

  <label>Short Description *</label>
  <input name="short_desc" required value="<?php echo e($plan['short_desc']); ?>">

  <label>Full Description</label>
  <textarea name="full_desc"><?php echo e((string)$plan['full_desc']); ?></textarea>

  <div class="grid two">
    <div>
      <label>Image URL</label>
      <input name="img" value="<?php echo e($plan['img']); ?>">
      <div style="margin-top:10px;"><img class="thumb" src="<?php echo e($plan['img']); ?>" alt=""></div>
    </div>
    <div>
      <label>Upload New Image</label>
      <input type="file" name="img_upload" accept=".jpg,.jpeg,.png,.webp">
    </div>
  </div>

  <div class="grid two">
    <div><label>Old Price</label><input name="old_price" type="number" step="0.01" value="<?php echo e((string)$plan['old_price']); ?>"></div>
    <div><label>New Price</label><input name="new_price" type="number" step="0.01" value="<?php echo e((string)$plan['new_price']); ?>"></div>
  </div>

  <div class="grid two">
    <div><label>Bedrooms</label><input name="bedrooms" type="number" value="<?php echo (int)$plan['bedrooms']; ?>"></div>
    <div><label>Bathrooms</label><input name="bathrooms" type="number" value="<?php echo (int)$plan['bathrooms']; ?>"></div>
  </div>

  <div class="grid two">
    <div><label>Garage</label><input name="garage" type="number" value="<?php echo (int)$plan['garage']; ?>"></div>
    <div><label>Floor Area (sqm)</label><input name="sqm" type="number" step="0.01" value="<?php echo e((string)$plan['sqm']); ?>"></div>
  </div>

  <div class="grid two">
    <div><label>Stories</label><input name="stories" type="number" value="<?php echo (int)$plan['stories']; ?>"></div>
    <div><label>Style *</label><input name="style" required value="<?php echo e($plan['style']); ?>"></div>
  </div>

  <div class="grid two">
    <div><label>Dimensions *</label><input name="dimensions" required value="<?php echo e($plan['dimensions']); ?>"></div>
    <div><label>Floor Plan URL</label><input name="floor_plan" value="<?php echo e((string)$plan['floor_plan']); ?>"></div>
  </div>

  <div class="actions" style="margin-top:14px;">
    <button class="btn primary" type="submit">Update</button>
    <a class="btn ghost" href="plans.php">Back</a>
  </div>
</form>
</div>

<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>
