<?php
session_start();
include 'conexion.php';

// Activar errores visibles (por ahora)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Si se envía el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    $stmt = $conn->prepare('SELECT id_usuario, usuario, contrasena, rol FROM usuarios WHERE usuario = ? LIMIT 1');
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if (password_verify($contrasena, $row['contrasena']) || $contrasena === $row['contrasena']) {
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['rol'] = $row['rol'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar sesión - Instituto</title>
  <link rel="stylesheet" href="formulario.css">
</head>
<body>
  <main>
    <div>
      <strong>SIGI</strong><br>
      <h2>Instituto San Felipe</h2>
    </div>

    <?php if (!empty($error)): ?>
      <p style="color:red;"><b><?= htmlspecialchars($error) ?></b></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <label>
        <i class="fa-solid fa-user"></i>
        <input placeholder="Usuario" name="usuario" type="text" required>
      </label>
      <br><br>
      <label>
        <i class="fa-solid fa-lock"></i>
        <input placeholder="Contraseña" name="contrasena" type="password" required>
      </label>
      <br>
      <a href="#" class="link-contrasena"><b>¿Olvidaste tu contraseña?</b></a>
      <br>
      <button type="submit"><b>Iniciar Sesión</b></button>
      <a href="Registro.html" class="link"><b>Registrarse</b></a> <br>
    </form>
  </main>
</body>
</html>
