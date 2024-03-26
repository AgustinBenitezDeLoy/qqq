<?php
// Asegúrate de iniciar la sesión y verificar si el usuario es administrador
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    exit('Acceso restringido solo a administradores.');
}

// Conexión a la base de datos
$host = 'localhost';
$database = 'reentraste'; // Ajusta el nombre de tu base de datos
$username = 'root'; // Usuario de la base de datos
$password = ''; // Contraseña de la base de datos

$conn = new mysqli($host, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar que se haya proporcionado un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar y ejecutar la consulta SQL para eliminar el usuario
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Usuario eliminado con éxito.";
    } else {
        echo "Error al eliminar el usuario: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "No se especificó ningún ID de usuario para eliminar.";
}

$conn->close();

// Redirigir de nuevo al listado de usuarios
header("Location: listar_usuarios.php");
exit;
?>
