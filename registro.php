<?php
// Conexión a la base de datos
$host = "localhost";
$username = "root";
$password = "";
$dbname = "reentraste";
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recoger datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$contraseña = $_POST['contraseña'];
$cedula = $_POST['cedula'];

// Definir el directorio destino para las fotos subidas y el límite de tamaño
$directorioDestino = "uploads/";
$limiteTamano = 5 * 1000 * 1000; // 5 MB en bytes

// Verificar el tamaño de las fotos
if ($_FILES['foto1']['size'] > $limiteTamano || $_FILES['foto2']['size'] > $limiteTamano) {
    echo "Error: El archivo excede el límite de tamaño permitido de 5 MB.";
    exit; // Detener la ejecución del script
}

// Obtener la extensión de cada archivo
$extensionFoto1 = pathinfo($_FILES['foto1']['name'], PATHINFO_EXTENSION);
$extensionFoto2 = pathinfo($_FILES['foto2']['name'], PATHINFO_EXTENSION);

// Construir nuevos nombres de archivo basados en el nombre y apellido del usuario
$nombreArchivoFoto1 = $nombre . "_" . $apellido . "_1." . $extensionFoto1;
$nombreArchivoFoto2 = $nombre . "_" . $apellido . "_2." . $extensionFoto2;

// Definir la ruta completa de destino para cada foto
$archivoFoto1 = $directorioDestino . basename($nombreArchivoFoto1);
$archivoFoto2 = $directorioDestino . basename($nombreArchivoFoto2);

// Intenta mover las fotos subidas al directorio de destino
if (move_uploaded_file($_FILES["foto1"]["tmp_name"], $archivoFoto1) && move_uploaded_file($_FILES["foto2"]["tmp_name"], $archivoFoto2)) {
    // Verificar si el correo ya existe
    $sql = "SELECT id FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "El usuario ya existe";
    } else {
        // Encriptar contraseña
        $contraseñaEncriptada = password_hash($contraseña, PASSWORD_DEFAULT);
        
        // Insertar el nuevo usuario con rol 'usuario' y fotos
        $sql = "INSERT INTO usuarios (nombre, apellido, correo, contraseña, cedula, rol, foto1, foto2) VALUES (?, ?, ?, ?, ?, 'usuario', ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $nombre, $apellido, $correo, $contraseñaEncriptada, $cedula, $archivoFoto1, $archivoFoto2);
        
        if ($stmt->execute()) {
            echo "Nuevo registro creado con éxito";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    echo "Error al cargar las fotos.";
}

$conn->close();
?>
