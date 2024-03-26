<?php
include 'conexion.php'; // Asegúrate de tener este archivo para conectar a la DB

// Obtener las fiestas disponibles
$fiestas = $conn->query("SELECT id, nombre FROM fiestas");

// Opcionalmente, podrías obtener los tipos de entrada de otra tabla o definirlos aquí
$tipos_entrada = ['VIP', 'Backstage', 'Campo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicar Entrada</title>
</head>
<body>
    <h2>Publicar Nueva Entrada</h2>
    <form action="procesar_entrada.php" method="post">
        <!-- Selector de Fiesta -->
        <!-- Selector de Tipo de Entrada -->
        <label for="tipo_entrada">Tipo de Entrada:</label><br>
        <select id="tipo_entrada" name="tipo_entrada" required>
            <?php foreach ($tipos_entrada as $tipo): ?>
                <option value="<?= $tipo ?>"><?= $tipo ?></option>
            <?php endforeach; ?>
        </select><br>
        <!-- Campo de Precio -->
        <label for="precio">Precio:</label><br>
        <input type="number" id="precio" name="precio" step="0.01" required><br>
        <input type="submit" value="Publicar Entrada">
    </form>
</body>
</html>
