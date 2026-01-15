<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../lib/response.php";

$search   = get_str("q");
$style    = get_str("style");
$bedrooms = get_int("bedrooms");
$bathrooms= get_int("bathrooms");
$stories  = get_int("stories");
$minPrice = get_float("min_price");
$maxPrice = get_float("max_price");

$sort = get_str("sort", "new_price");  // new_price | bedrooms | sqm
$dir  = strtolower(get_str("dir", "asc")) === "desc" ? "DESC" : "ASC";

$allowedSort = ["new_price","bedrooms","sqm","created_at"];
if (!in_array($sort, $allowedSort, true)) $sort = "new_price";

$where = [];
$params = [];

if ($search) {
  $where[] = "(name LIKE :q OR short_desc LIKE :q OR full_desc LIKE :q)";
  $params[":q"] = "%{$search}%";
}
if ($style) {
  $where[] = "style = :style";
  $params[":style"] = $style;
}
if ($bedrooms !== null) {
  $where[] = "bedrooms = :bedrooms";
  $params[":bedrooms"] = $bedrooms;
}
if ($bathrooms !== null) {
  $where[] = "bathrooms = :bathrooms";
  $params[":bathrooms"] = $bathrooms;
}
if ($stories !== null) {
  $where[] = "stories = :stories";
  $params[":stories"] = $stories;
}
if ($minPrice !== null) {
  $where[] = "new_price >= :minp";
  $params[":minp"] = $minPrice;
}
if ($maxPrice !== null) {
  $where[] = "new_price <= :maxp";
  $params[":maxp"] = $maxPrice;
}

$sql = "SELECT id, name, img, short_desc, old_price, new_price, bedrooms, bathrooms, garage, sqm, stories, style, dimensions
        FROM plans";

if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY {$sort} {$dir}";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$plans = $stmt->fetchAll();

json_response([
  "ok" => true,
  "count" => count($plans),
  "plans" => $plans
]);
