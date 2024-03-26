<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fiesta'], $_POST['tipo_entrada'], $_POST['precio'])) {
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'reentraste');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    $fiestaId = $_POST['fiesta'];
    $tipo_entrada = $_POST['tipo_entrada'];
    $precio = $_POST['precio'];

    // Suponiendo que el usuario está logueado y su ID está en $_SESSION['usuario_id']
    session_start();
    $usuarioId = $_SESSION['usuario_id'] ?? 0; // Asegúrate de que el usuario esté logueado

    // Obtén el precio máximo permitido y la imagen QR de la fiesta seleccionada
    $consultaFiesta = $conn->prepare("SELECT imagenQR, precio_maximo FROM fiestas WHERE id = ?");
    $consultaFiesta->bind_param("i", $fiestaId);
    $consultaFiesta->execute();
    $resultadoFiesta = $consultaFiesta->get_result();
    
    if ($filaFiesta = $resultadoFiesta->fetch_assoc()) {
        $imagenQR = $filaFiesta['imagenQR'];
        $precio_maximo = $filaFiesta['precio_maximo'];
        
        // Verifica si el precio de la entrada excede el precio máximo permitido
        if ($precio <= $precio_maximo) {
            // Inserta la entrada en la base de datos con tipo y precio
            $insertarEntrada = $conn->prepare("INSERT INTO entradas (usuario_id, fiesta_id, codigo_qr, tipo_entrada, precio) VALUES (?, ?, ?, ?, ?)");
            $insertarEntrada->bind_param("iissd", $usuarioId, $fiestaId, $imagenQR, $tipo_entrada, $precio);
            
            if ($insertarEntrada->execute()) {
                echo "Entrada publicada con éxito.";
            } else {
                echo "Error al publicar la entrada: " . $insertarEntrada->error;
            }
            $insertarEntrada->close();
        } else {
            echo "El precio de la entrada excede el precio máximo permitido para esta fiesta.";
        }
    } else {
        echo "La fiesta seleccionada no existe.";
    }

    $consultaFiesta->close();
    $conn->close();
} else {
    echo "Método no permitido.";
}
?>
