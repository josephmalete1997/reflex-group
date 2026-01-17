<?php

declare(strict_types=1);
session_start();
require_once __DIR__ . '/../backend/config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $pass  = (string)($_POST['password'] ?? '');

    $stmt = $pdo->prepare("SELECT id, name, email, password_hash FROM admins WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch();

    if (!$admin || !$pass) {
        $error = 'Invalid email or password.';
    } else {
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header('Location: ./dashboard.php');
        exit;
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>

<body>
    <main class="wrap" style="padding:40px 0;">
        <div class="card" style="max-width:520px;margin:0 auto;">
            <h1>Admin Login</h1>
            <?php if ($error): ?><div class="err"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <form method="post" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                <label>Email</label>
                <input name="email" type="email" required placeholder="">
                <label>Password</label>
                <input name="password" type="password" required placeholder="••••••••">
                <div style="margin-top:14px">
                    <button class="btn primary" type="submit">Sign in</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>