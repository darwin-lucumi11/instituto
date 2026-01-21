<?php
header("Content-Type: application/json; charset=utf-8");
include 'conexion.php';

$estudiante = $_POST['estudiante'] ?? '';
$asignatura = $_POST['asignatura'] ?? '';
$valor      = $_POST['valor'] ?? '';
$fecha      = $_POST['fecha'] ?? '';
$estado     = $_POST['estado'] ?? '';

if ($estudiante == "" || $asignatura == "" || $valor == "" || $fecha == "" || $estado == "") {
    echo json_encode(["status" => "error", "message" => "Campos incompletos"]);
    exit;
}

$sql = "INSERT INTO pagos (id_estudiante, id_asignatura, valor, fecha, estado)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iidss", $estudiante, $asignatura, $valor, $fecha, $estado);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Pago registrado"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al registrar el pago"]);
}

$stmt->close();
$conn->close();
?>
