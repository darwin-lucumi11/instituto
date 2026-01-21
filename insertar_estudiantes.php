<?php header('Content-Type: application/json; charset=utf-8'); 
include 'conexion.php'; $documento = trim($_POST['documento'] ?? ''); 
$nombres = trim($_POST['nombres'] ?? ''); 
$apellidos = trim($_POST['apellidos'] ?? ''); 
$telefono = trim($_POST['telefono'] ?? ''); 
$id_asignatura = isset($_POST['id_asignatura']) && $_POST['id_asignatura'] !== '' ? intval($_POST['id_asignatura']) : NULL; 
if (!$documento || !$nombres) { echo json_encode(['ok'=>false,'msg'=>'Faltan campos']); 
exit; } $stmt = $conn->prepare('SELECT id_estudiante FROM estudiantes WHERE documento=? LIMIT 1'); 
$stmt->bind_param('s',$documento); $stmt->execute(); $stmt->store_result(); 
if ($stmt->num_rows>0) { echo json_encode(['ok'=>false,'msg'=>'Documento ya registrado']); 
$stmt->close(); $conn->close(); exit; } $stmt->close(); 
$stmt = $conn->prepare('INSERT INTO estudiantes (documento,nombres,apellidos,telefono,id_asignatura) VALUES (?,?,?,?,?)'); 
if (!$stmt) { echo json_encode(['ok'=>false,'msg'=>$conn->error]); exit; } $stmt->bind_param('ssssi',$documento,$nombres,$apellidos,$telefono,$id_asignatura); 
$ok = $stmt->execute(); if ($ok) echo json_encode(['ok'=>true,'msg'=>'Estudiante registrado']); else echo json_encode(['ok'=>false,'msg'=>$stmt->error]); 
$stmt->close(); $conn->close(); ?>