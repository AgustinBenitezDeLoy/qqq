<?php
// Archivo: obtener_fiestas.php

// Conexión a la base de datos
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'reentraste';

$conn = new mysqli($host, $username, $password, $database);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener las fiestas disponibles
$query = "SELECT id, nombre FROM fiestas";
$fiestas = $conn->query($query);

// Asegúrate de cerrar la conexión más adelante, después de usar $fiestas
?>
