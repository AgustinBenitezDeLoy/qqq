<?php
// Inicia la sesión
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    // Si no hay una sesión de usuario, redirige al login
    header("Location: login.html");
    exit;
}

// Aquí puedes obtener más información del usuario desde la base de datos
// utilizando $_SESSION['usuario_id'] si es necesario

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Área del Usuario</title>
</head>
<body>
    <h1>Bienvenido al Área del Usuario</h1>
    <p>Este es un contenido exclusivo para usuarios logueados.</p>
    
    <!-- Enlace para cerrar sesión -->
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
