<?php
// Inicia la sesión al principio del script
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Establece la conexión con la base de datos
$host = 'localhost';
$database = 'reentraste'; // Nombre de tu base de datos
$username = 'root'; // Usuario de la base de datos
$password = ''; // Contraseña del usuario de la base de datos

$conn = new mysqli($host, $username, $password, $database);

// Inicializa variables para el mensaje y la URL de redirección
$mensaje = '';
$urlRedireccion = '';

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $sql = "SELECT id, correo, contraseña, rol FROM usuarios WHERE correo = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($contraseña, $user['contraseña'])) {
            $token = bin2hex(random_bytes(32));
            setcookie("userToken", $token, time() + (86400 * 30), "/", "", true, true);

            $updateTokenSql = "UPDATE usuarios SET session_token = ? WHERE id = ?";
            if ($updateStmt = $conn->prepare($updateTokenSql)) {
                $updateStmt->bind_param("si", $token, $user['id']);
                $updateStmt->execute();

                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['rol'] = $user['rol'];

                if ($user['rol'] == 'administrador') {
                    $urlRedireccion = 'panel_admin.php';
                } elseif ($user['rol'] == 'usuario') {
                    $urlRedireccion = 'area_usuario.php';
                } else {
                    $urlRedireccion = 'pagina_error.php';
                }

                $mensaje = 'Login exitoso. Redirigiendo...';
            } else {
                $mensaje = "Error al guardar el token de sesión.";
            }
        } else {
            $mensaje = "Correo electrónico o contraseña incorrectos.";
        }
        $stmt->close();
    } else {
        $mensaje = "Error al preparar la consulta.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <p><?php echo $mensaje; ?></p>
    <?php if (!empty($urlRedireccion)) : ?>
        <script>
            setTimeout(function() {
                window.location.href = '<?php echo $urlRedireccion; ?>';
            }, 2000); // Redirige después de 2 segundos
        </script>
    <?php endif; ?>
</body>
</html>
