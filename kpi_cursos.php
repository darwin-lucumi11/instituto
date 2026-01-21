<?php
include 'conexion.php';
$res = $conn->query("SELECT COUNT(*) total FROM asignaturas");
$row = $res->fetch_assoc();
echo json_encode(['total' => (int)$row['total']]);
$conn->close();