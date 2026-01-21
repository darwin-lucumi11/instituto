<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';
$res = $conn->query('SELECT id_asignatura, nombre FROM asignaturas ORDER BY nombre');
$a = [];
if ($res) { while($r = $res->fetch_assoc()) $a[] = $r; }
echo json_encode($a, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
