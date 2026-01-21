<?php
header("Content-Type: application/json; charset=utf-8");
include "conexion.php";

if (!isset($_POST["usuario"]) || $_POST["usuario"] === "") {
    echo json_encode(["existe" => false, "error" => "Usuario no enviado"]);
    exit;
}

$usuario = $_POST["usuario"];
$sql = $conn->prepare("SELECT id FROM usuarios WHERE usuario=?");
$sql->bind_param("s", $usuario);
$sql->execute();
$sql->store_result();

echo json_encode(["existe" => $sql->num_rows > 0]);
$conn->close();
?>
