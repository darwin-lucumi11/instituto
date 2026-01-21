<?php
require 'conexion.php';

$res = $conn->query(
  "SELECT id_asignatura, nombre, intensidad_horaria
   FROM asignaturas ORDER BY id_asignatura DESC"
);

$data = [];
while ($row = $res->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode($data);
