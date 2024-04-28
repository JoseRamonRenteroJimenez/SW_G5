<?php
require_once __DIR__.'/includes/config.php'; 
require_once __DIR__.'/includes/Aplicacion.php'; 

// Verificar que el usuario esté logueado y tenga un ID de sesión
if (!isset($_SESSION['usuario_id'])) {
    echo "Usuario no autenticado.";
    exit; // Detener la ejecución si no hay un usuario logueado
}

$conn = Aplicacion::getInstance()->getConexionBd();
$idReceptor = $_SESSION['usuario_id'];

// Preparar y ejecutar la consulta para obtener notificaciones no leídas
$query = "SELECT * FROM notificaciones WHERE idReceptor = ? AND estado = 0"; // Estado 0 para no leídas
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $idReceptor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($notificacion = $result->fetch_assoc()) {
            // Incluye un enlace para marcar la notificación como leída
            echo "<li>{$notificacion['titulo']} - {$notificacion['fecha']} <a href='marcaComoLeida.php?id={$notificacion['id']}'>Marcar como leída</a></li>";
        }
        echo "</ul>";
    } else {
        echo "No tienes notificaciones nuevas.";
    }
    $stmt->close();
} else {
    // Manejo del error si la preparación de la consulta falla
    error_log("Error preparando consulta: " . $conn->error);
    echo "Error al cargar notificaciones.";
}
?>
