<?php

declare(strict_types=1);
session_start();
require_once __DIR__ . '/../backend/config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $pass1 = (string)($_POST['password'] ?? '');
    $pass2 = (string)($_POST['confirm_password'] ?? '');

    if ($name === '' || $email === '' || $pass1 === '' || $pass2 === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif ($pass1 !== $pass2) {
        $error = 'Passwords do not match.';
    } else {
        // Check if email already exists
        $check = $pdo->prepare("SELECT id FROM admins WHERE email = :email LIMIT 1");
        $check->execute([':email' => $email]);

        if ($check->fetch()) {
            $error = 'Email is already registered.';
        } else {
            // ⚠️ Plain-text password (as requested)
            $stmt = $pdo->prepare("
                INSERT INTO admins (name, email, password_hash)
                VALUES (:name, :email, :password)
            ");
            $stmt->execute([
                ':name'     => $name,
                ':email'    => $email,
                ':password' => $pass1
            ]);

            $success = 'Admin account created successfully.';
        }
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>

<body>
    <main class="wrap" style="padding:40px 0;">
        <div class="card" style="max-width:520px;margin:0 auto;">
            <h1>Admin Register</h1>

            <?php if ($error): ?>
                <div class="err"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="notice"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="post" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                <label>Full Name</label>
                <input name="name" type="text" required placeholder="">

                <label>Email</label>
                <input name="email" type="email" required placeholder="">

                <label>Password</label>
                <input name="password" type="password" required placeholder="••••••••">

                <label>Confirm Password</label>
                <input name="confirm_password" type="password" required placeholder="••••••••">

                <div style="margin-top:14px">
                    <button class="btn primary" type="submit">Create Account</button>
                    <a href="login.php" class="btn ghost" style="margin-left:10px">Back to Login</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
