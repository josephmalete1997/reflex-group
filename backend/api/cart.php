<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../lib/response.php";

if (!isset($_SESSION["cart"])) $_SESSION["cart"] = []; // store plan IDs

$action = $_GET["action"] ?? "view";
$action = strtolower((string)$action);

if ($action === "count") {
  json_response(["ok"=>true, "count"=>count($_SESSION["cart"])]);
}

if ($action === "add") {
  $id = $_GET["id"] ?? "";
  $id = trim((string)$id);
  if ($id === "") json_response(["ok"=>false,"error"=>"Missing id"], 400);

  // validate plan exists
  $stmt = $pdo->prepare("SELECT id FROM plans WHERE id=:id");
  $stmt->execute([":id"=>$id]);
  if (!$stmt->fetch()) json_response(["ok"=>false,"error"=>"Plan not found"], 404);

  if (!in_array($id, $_SESSION["cart"], true)) {
    $_SESSION["cart"][] = $id;
  }

  json_response(["ok"=>true, "count"=>count($_SESSION["cart"]), "cart"=>$_SESSION["cart"]]);
}

if ($action === "remove") {
  $id = $_GET["id"] ?? "";
  $id = trim((string)$id);
  $_SESSION["cart"] = array_values(array_filter($_SESSION["cart"], fn($x)=>$x !== $id));
  json_response(["ok"=>true, "count"=>count($_SESSION["cart"]), "cart"=>$_SESSION["cart"]]);
}

if ($action === "clear") {
  $_SESSION["cart"] = [];
  json_response(["ok"=>true, "count"=>0, "cart"=>[]]);
}

// view cart (with plan info)
$ids = $_SESSION["cart"];
if (!$ids) json_response(["ok"=>true,"count"=>0,"items"=>[]]);

$placeholders = implode(",", array_fill(0, count($ids), "?"));
$stmt = $pdo->prepare("SELECT id,name,img,new_price,old_price,short_desc FROM plans WHERE id IN ($placeholders)");
$stmt->execute($ids);
$items = $stmt->fetchAll();

json_response(["ok"=>true, "count"=>count($ids), "items"=>$items]);
