<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$res = $conn->query("SELECT COUNT(*) AS total FROM solicitudes");
$row = $res->fetch_assoc();

echo json_encode([
  "total" => (int)$row['total']
]);

$conn->close();
