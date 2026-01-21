<?php
header("Content-Type: application/json; charset=utf-8");
include "conexion.php";

$usuario = $_POST["usuario"] ?? "";
$contrasena = $_POST["contrasena"] ?? "";
$rol = $_POST["rol"] ?? "";

if ($usuario === "" || $contrasena === "" || $rol === "") {
    echo json_encode(["success" => false, "message" => "Faltan campos"]);
    exit;
}

$hash = password_hash($contrasena, PASSWORD_DEFAULT);

// validar usuario
$check = $conn->prepare("SELECT id_usuario FROM usuarios WHERE usuario=?");
$check->bind_param("s", $usuario);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Usuario ya existe"]);
    exit;
}

$sql = $conn->prepare(
  "INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, ?)"
);
$sql->bind_param("sss", $usuario, $hash, $rol);

if ($sql->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error al registrar"]);
}
