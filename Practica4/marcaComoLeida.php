<?php
require_once __DIR__.'/includes/config.php'; 
require_once __DIR__.'/includes/Aplicacion.php'; 

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $notificacionId = $_GET['id'];
    $usuarioId = $_SESSION['usuario_id']; // Asume que el ID del usuario está almacenado en la sesión

    $conn = Aplicacion::getInstance()->getConexionBd();

    // Verifica que la notificación pertenezca al usuario
    $stmt = $conn->prepare("SELECT * FROM notificaciones WHERE id = ? AND idReceptor = ?");
    $stmt->bind_param("ii", $notificacionId, $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Actualizar el estado de la notificación a leída
        $update = $conn->prepare("UPDATE notificaciones SET estado = 1 WHERE id = ?");
        $update->bind_param("i", $notificacionId);
        $update->execute();
        $update->close();
        echo "Notificación marcada como leída.";
    } else {
        echo "No se encontró la notificación o no tienes permiso para marcarla como leída.";
    }
    $stmt->close();
} else {
    echo "No se proporcionó ID de notificación.";
}

?>
