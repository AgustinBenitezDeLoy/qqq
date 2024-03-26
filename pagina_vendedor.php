<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Si no hay una sesión de usuario, redirige a login.php
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página del Vendedor</title>
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de ajustar la ruta según sea necesario -->
</head>
<body>
    <h1>Bienvenido al Panel del Vendedor</h1>
    <p>Contenido exclusivo para vendedores...</p>

    <!-- Botón de Cerrar Sesión -->
    <form action="logout.php" method="POST">
        <button type="submit">Cerrar Sesión</button>
    </form>
</body>
</html>
