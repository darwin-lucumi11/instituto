<?php
require_once __DIR__ . '/conexion.php';
header('Content-Type: application/json; charset=utf-8');

$q = trim($_GET['q'] ?? '');

if ($q === '' || strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
  SELECT id_estudiante, documento, nombres, apellidos
  FROM estudiantes
  WHERE documento LIKE ?
     OR nombres LIKE ?
     OR apellidos LIKE ?
  LIMIT 10
");

$like = "%$q%";
$stmt->bind_param("sss", $like, $like, $like);
$stmt->execute();

$res = $stmt->get_result();
$data = [];

while ($r = $res->fetch_assoc()) {
    $data[] = $r;
}

echo json_encode($data);
