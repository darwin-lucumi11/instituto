<?php
include "conexion.php";
header("Content-Type: application/json; charset=utf-8");

$id_estudiante = $_POST["id_estudiante"] ?? "";
$tipo = $_POST["type"] ?? "";
$detalle = $_POST["detail"] ?? "";

if ($id_estudiante == "" || $tipo == "" || $detalle == "") {
  echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios"]);
  exit;
}

$sql = $conn->prepare(
  "INSERT INTO solicitudes (id_estudiante, tipo, detalle, fecha)
   VALUES (?, ?, ?, CURDATE())"
);
$sql->bind_param("iss", $id_estudiante, $tipo, $detalle);

if ($sql->execute()) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false, "message" => "Error al guardar"]);
}
