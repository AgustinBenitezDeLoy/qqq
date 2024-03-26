<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado. Necesitas ser administrador para acceder a esta página.");
}
?>
<?php
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado. Necesitas ser administrador para acceder a esta página.");
}
?>
