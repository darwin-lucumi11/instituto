<?php
header("Content-Type: application/json; charset=utf-8");
include "conexion.php";

$sql = "
SELECT s.id_solicitud AS id,
       e.documento,
       e.nombres,
       e.apellidos,
       s.tipo,
       s.detalle,
       s.fecha
FROM solicitudes s
LEFT JOIN estudiantes e ON s.id_estudiante = e.id_estudiante
ORDER BY s.id_solicitud DESC
";

$res = $conn->query($sql);

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
