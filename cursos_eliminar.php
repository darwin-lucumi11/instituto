<?php
require 'conexion.php';

$conn->query(
  "DELETE FROM asignaturas WHERE id_asignatura=".(int)$_POST['id']
);

echo json_encode(["ok" => true]);
