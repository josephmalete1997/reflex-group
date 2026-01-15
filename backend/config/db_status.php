<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

header("Content-Type: text/plain");

try {
    // simple test query
    $pdo->query("SELECT 1");
    echo "✅ Database connected successfully";
} catch (Throwable $e) {
    echo "❌ Database connection failed";
}
?>