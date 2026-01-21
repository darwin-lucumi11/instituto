<?php
include 'conexion.php';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=estudiantes.csv');
$out = fopen('php://output','w');
fputcsv($out, ['documento','nombres','apellidos','telefono','asignatura']);
$sql = 'SELECT e.documento, e.nombres, e.apellidos, e.telefono, a.nombre AS asignatura FROM estudiantes e LEFT JOIN asignaturas a ON e.id_asignatura=a.id_asignatura ORDER BY e.nombres';
$res = $conn->query($sql);
if ($res) { while($r = $res->fetch_assoc()) fputcsv($out, $r); }
fclose($out); $conn->close();
?>
