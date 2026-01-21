<?php
require_once __DIR__ . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($conn)) {
    echo json_encode(["error" => "Conexión no definida"]);
    exit;
}

$sql = "SELECT id_asignatura, nombre FROM asignaturas ORDER BY nombre";
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
