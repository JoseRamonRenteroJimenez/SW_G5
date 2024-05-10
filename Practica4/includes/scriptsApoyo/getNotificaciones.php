<?php
// getNotificaciones.php
namespace es\ucm\fdi\aw;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Aplicacion.php';
require_once __DIR__ . '/../clases/Notificacion.php';

use es\ucm\fdi\aw\Notificacion;

header('Content-Type: application/json');


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

$idUsuario = $_SESSION['id'];
$notificaciones = Notificacion::getNotificacionesPorUsuario($idUsuario);

$response = [];
foreach ($notificaciones as $notificacion) {
    if ($notificacion->getEstado() == Notificacion::NO_VISTO_ESTADO) {
        $response[] = [
            'id' => $notificacion->getId(),
            'titulo' => $notificacion->getTitulo(),
            'mensaje' => $notificacion->getMensaje(),
            'fecha' => $notificacion->getFecha(),
            'tipo' => $notificacion->getTipo()
        ];
    }
}

echo json_encode($response);
?>
