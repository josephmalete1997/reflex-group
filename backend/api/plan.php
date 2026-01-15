<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../lib/response.php";

$id = get_str("id");
if (!$id) json_response(["ok"=>false,"error"=>"Missing plan id"], 400);

// plan
$stmt = $pdo->prepare("SELECT * FROM plans WHERE id = :id LIMIT 1");
$stmt->execute([":id" => $id]);
$plan = $stmt->fetch();
if (!$plan) json_response(["ok"=>false,"error"=>"Plan not found"], 404);

// features
$f = $pdo->prepare("SELECT feature FROM plan_features WHERE plan_id = :id ORDER BY id ASC");
$f->execute([":id"=>$id]);
$features = array_map(fn($r)=>$r["feature"], $f->fetchAll());

// gallery
$g = $pdo->prepare("SELECT image FROM plan_gallery WHERE plan_id = :id ORDER BY id ASC");
$g->execute([":id"=>$id]);
$gallery = array_map(fn($r)=>$r["image"], $g->fetchAll());

$plan["features"] = $features;
$plan["gallery"] = $gallery;

json_response(["ok"=>true,"plan"=>$plan]);
