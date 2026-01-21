<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$sql = "SELECT 
        p.id_pago,
        p.id_estudiante,
        p.id_asignatura,
        p.valor,
        p.fecha,      
        p.estado,
        e.documento,
        e.nombres,
        e.apellidos,
        a.nombre AS asignatura
        FROM pagos p
        LEFT JOIN estudiantes e ON p.id_estudiante = e.id_estudiante
        LEFT JOIN asignaturas a ON p.id_asignatura = a.id_asignatura
        ORDER BY p.fecha DESC";  

$res = $conn->query($sql);
$out = [];

if ($res) {
  while ($r = $res->fetch_assoc()) {
    $out[] = $r;
  }
}

echo json_encode($out, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
