<?php
require 'conexion.php';
header('Content-Type: application/json');

if (
    empty($_POST['nombre']) ||
    empty($_POST['intensidad_horaria'])
) {
    echo json_encode([
        "ok" => false,
        "error" => "Datos incompletos"
    ]);
    exit;
}

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'] ?? '';
$intensidad = $_POST['intensidad_horaria'];

$stmt = $conn->prepare(
    "INSERT INTO asignaturas (nombre, descripcion, intensidad_horaria)
     VALUES (?, ?, ?)"
);

if (!$stmt) {
    echo json_encode([
        "ok" => false,
        "error" => "Error en prepare: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("ssi", $nombre, $descripcion, $intensidad);

if (!$stmt->execute()) {
    echo json_encode([
        "ok" => false,
        "error" => "Error al guardar: " . $stmt->error
    ]);
    exit;
}

echo json_encode(["ok" => true]);
