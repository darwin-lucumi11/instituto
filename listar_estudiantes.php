<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';
$sql = 'SELECT e.id_estudiante, e.documento, e.nombres, e.apellidos, e.telefono, a.nombre AS asignatura FROM estudiantes e LEFT JOIN asignaturas a ON e.id_asignatura = a.id_asignatura ORDER BY e.nombres';
$res = $conn->query($sql);
$out = [];
if ($res) { while($r=$res->fetch_assoc()) $out[]=$r; }
echo json_encode($out, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
