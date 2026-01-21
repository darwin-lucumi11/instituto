<?php
require_once __DIR__ . '/conexion.php';
header('Content-Type: application/json');

$to = $_POST['to'] ?? '';
$student = $_POST['student'] ?? '';
$body = trim($_POST['body'] ?? '');

if ($body === '') {
    echo json_encode(["ok" => false, "error" => "Mensaje incompleto"]);
    exit;
}

$phones = [];

/* === UN ESTUDIANTE === */
if ($to === 'one') {

    $stmt = $conn->prepare("
        SELECT telefono
        FROM estudiantes
        WHERE id_estudiante = ?
           OR documento = ?
           OR CONCAT(nombres,' ',apellidos) LIKE ?
    ");

    $like = "%$student%";
    $stmt->bind_param("sss", $student, $student, $like);
    $stmt->execute();

    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        $phones[] = $r['telefono'];
    }
}

/* === POR CURSO === */
elseif ($to === 'course') {

    $stmt = $conn->prepare("
        SELECT telefono
        FROM estudiantes
        WHERE id_asignatura = ?
    ");

    $stmt->bind_param("i", $student);
    $stmt->execute();

    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        $phones[] = $r['telefono'];
    }
}

/* === TODOS === */
else {

    $res = $conn->query("
        SELECT telefono
        FROM estudiantes
        WHERE telefono IS NOT NULL AND telefono != ''
    ");

    while ($r = $res->fetch_assoc()) {
        $phones[] = $r['telefono'];
    }
}

if (count($phones) === 0) {
    echo json_encode(["ok" => false, "error" => "No se encontraron teléfonos"]);
    exit;
}

/* === GUARDAR NOTIFICACIÓN === */
$stmt = $conn->prepare("
    INSERT INTO notificaciones (destino, mensaje, canal, fecha_envio)
    VALUES (?, ?, 'whatsapp', NOW())
");

$stmt->bind_param("ss", $to, $body);
$stmt->execute();

echo json_encode([
    "ok" => true,
    "phones" => $phones,
    "body" => $body
]);
