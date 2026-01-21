<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido']);
    exit;
}

$id = $_POST['id_estudiante'] ?? null;
if (!$id) {
    echo json_encode(['ok' => false, 'msg' => 'Falta id_estudiante']);
    exit;
}

$id = intval($id);

$stmt = $conn->prepare("DELETE FROM estudiantes WHERE id_estudiante = ?");
if (!$stmt) {
    echo json_encode(['ok' => false, 'msg' => 'Error prepare: '.$conn->error]);
    exit;
}
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'msg' => 'Estudiante eliminado']);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Error al eliminar: '.$stmt->error]);
}

$stmt->close();
$conn->close();
?>
