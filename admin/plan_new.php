<?php
require_once __DIR__ . '/partials/admin_header.php';
admin_require_login();

function upload_plan_image(string $field, ?string &$errorMsg = null): ?string
{
    $errorMsg = null;

    if (empty($_FILES[$field])) {
        $errorMsg = "No file field found.";
        return null;
    }

    $f = $_FILES[$field];

    // 1) PHP upload errors
    if (($f['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $map = [
            UPLOAD_ERR_INI_SIZE   => "File too large (upload_max_filesize).",
            UPLOAD_ERR_FORM_SIZE  => "File too large (form limit).",
            UPLOAD_ERR_PARTIAL    => "File upload was partial.",
            UPLOAD_ERR_NO_FILE    => "No file uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing temporary folder.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION  => "Upload blocked by a PHP extension."
        ];
        $code = (int)$f['error'];
        $errorMsg = $map[$code] ?? "Upload failed (error code $code).";
        return null;
    }

    // 2) Validate tmp file exists
    $tmp = $f['tmp_name'] ?? '';
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        $errorMsg = "Temporary upload file not found.";
        return null;
    }

    // 3) Validate size
    $size = (int)($f['size'] ?? 0);
    if ($size <= 0) {
        $errorMsg = "File size is 0 bytes.";
        return null;
    }
    if ($size > 2 * 1024 * 1024) {
        $errorMsg = "Image must be under 2MB.";
        return null;
    }

    // 4) Detect mime via finfo (reliable)
    $fi = new finfo(FILEINFO_MIME_TYPE);
    $mime = $fi->file($tmp) ?: 'unknown';

    $allowedMime = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    // 5) Extension fallback (some servers mis-detect mime)
    $origName = (string)($f['name'] ?? '');
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

    $finalExt = null;

    if (isset($allowedMime[$mime])) {
        $finalExt = $allowedMime[$mime];
    } elseif (in_array($ext, $allowedExt, true)) {
        // If mime is weird, but extension is trusted + file is uploaded, allow it
        $finalExt = ($ext === 'jpeg') ? 'jpg' : $ext;
    } else {
        $errorMsg = "Invalid image type. Detected mime: $mime, extension: .$ext";
        return null;
    }

    // 6) Destination folder
    $destDir = __DIR__ . '/../uploads/plans';
    if (!is_dir($destDir) && !mkdir($destDir, 0775, true)) {
        $errorMsg = "Failed to create uploads folder: $destDir";
        return null;
    }
    if (!is_writable($destDir)) {
        $errorMsg = "Uploads folder not writable: $destDir";
        return null;
    }

    $name = 'plan_' . bin2hex(random_bytes(10)) . '.' . $finalExt;
    $destPath = $destDir . '/' . $name;

    // 7) Move
    if (!move_uploaded_file($tmp, $destPath)) {
        $errorMsg = "move_uploaded_file() failed. Check folder permissions.";
        return null;
    }

    // public path used by the website
    return 'uploads/plans/' . $name;
}


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = trim((string)($_POST['id'] ?? ''));
    $name        = trim((string)($_POST['name'] ?? ''));
    $short_desc  = trim((string)($_POST['short_desc'] ?? ''));
    $full_desc   = trim((string)($_POST['full_desc'] ?? ''));
    $old_price   = (float)($_POST['old_price'] ?? 0);
    $new_price   = (float)($_POST['new_price'] ?? 0);
    $bedrooms    = (int)($_POST['bedrooms'] ?? 0);
    $bathrooms   = (int)($_POST['bathrooms'] ?? 0);
    $garage      = (int)($_POST['garage'] ?? 0);
    $sqm         = (float)($_POST['sqm'] ?? 0);
    $stories     = (int)($_POST['stories'] ?? 1);
    $style       = trim((string)($_POST['style'] ?? ''));
    $dimensions  = trim((string)($_POST['dimensions'] ?? ''));
    $floor_plan  = trim((string)($_POST['floor_plan'] ?? ''));

    if ($id === '' || $name === '' || $short_desc === '' || $style === '' || $dimensions === '') {
        $error = 'Please fill all required fields.';
    } else {
        $img = upload_plan_image('img_upload');
        if (!$img) {
            $error = 'Please upload a valid plan image.';
        }
    }

    if ($error === '') {
        $stmt = $pdo->prepare("
            INSERT INTO plans
            (id, name, img, short_desc, full_desc, old_price, new_price,
             bedrooms, bathrooms, garage, sqm, stories, style, dimensions, floor_plan)
            VALUES
            (:id, :name, :img, :short_desc, :full_desc, :old_price, :new_price,
             :bedrooms, :bathrooms, :garage, :sqm, :stories, :style, :dimensions, :floor_plan)
        ");

        $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':img' => $img,
            ':short_desc' => $short_desc,
            ':full_desc' => $full_desc,
            ':old_price' => $old_price,
            ':new_price' => $new_price,
            ':bedrooms' => $bedrooms,
            ':bathrooms' => $bathrooms,
            ':garage' => $garage,
            ':sqm' => $sqm,
            ':stories' => $stories,
            ':style' => $style,
            ':dimensions' => $dimensions,
            ':floor_plan' => $floor_plan !== '' ? $floor_plan : null
        ]);

        header('Location: plans.php');
        exit;
    }
}
?>

<h1>Add Plan</h1>

<?php if ($error): ?>
    <div class="err"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <form method="post" enctype="multipart/form-data">
        <div class="grid two">
            <div>
                <label>Plan ID *</label>
                <input name="id" required>
            </div>
            <div>
                <label>Name *</label>
                <input name="name" required>
            </div>
        </div>

        <label>Short Description *</label>
        <input name="short_desc" required>

        <label>Full Description</label>
        <textarea name="full_desc"></textarea>

        <label>Upload Plan Image *</label>
        <input type="file" name="img_upload" accept=".jpg,.jpeg,.png,.webp" required>

        <div class="grid two">
            <div><label>Old Price</label><input name="old_price" type="number" step="0.01"></div>
            <div><label>New Price</label><input name="new_price" type="number" step="0.01" required></div>
        </div>

        <div class="grid two">
            <div><label>Bedrooms</label><input name="bedrooms" type="number"></div>
            <div><label>Bathrooms</label><input name="bathrooms" type="number"></div>
        </div>

        <div class="grid two">
            <div><label>Garage</label><input name="garage" type="number"></div>
            <div><label>Floor Area (sqm)</label><input name="sqm" type="number" step="0.01"></div>
        </div>

        <div class="grid two">
            <div><label>Stories</label><input name="stories" type="number" value="1"></div>
            <div><label>Style *</label><input name="style" required></div>
        </div>

        <div class="grid two">
            <div><label>Dimensions *</label><input name="dimensions" required></div>
            <div><label>Floor Plan URL</label><input name="floor_plan"></div>
        </div>

        <div class="actions" style="margin-top:14px;">
            <button class="btn primary" type="submit">Save Plan</button>
            <a class="btn ghost" href="plans.php">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>