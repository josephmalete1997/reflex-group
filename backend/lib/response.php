<?php
declare(strict_types=1);

function json_response(array $data, int $status = 200): void {
  http_response_code($status);
  header("Content-Type: application/json; charset=utf-8");
  echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  exit;
}

function get_int(string $key, ?int $default = null): ?int {
  if (!isset($_GET[$key]) || $_GET[$key] === '') return $default;
  return (int)$_GET[$key];
}

function get_float(string $key, ?float $default = null): ?float {
  if (!isset($_GET[$key]) || $_GET[$key] === '') return $default;
  return (float)$_GET[$key];
}

function get_str(string $key, ?string $default = null): ?string {
  if (!isset($_GET[$key]) || trim((string)$_GET[$key]) === '') return $default;
  return trim((string)$_GET[$key]);
}
