<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'reentraste');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
