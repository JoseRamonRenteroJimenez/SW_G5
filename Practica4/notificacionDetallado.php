<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/includes/config.php';
require_once __DIR__ . '/includes/clases/Notificacion.php';
require_once __DIR__ . '/includes/formularios/FormularioNotificacionDetalle.php';

$idNotificacion = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch notification details
$notificacion = Notificacion::buscaNotificacionPorId($idNotificacion);
if (!$notificacion) {
    die("Error: Notification not found.");
}

$formulario = new FormularioNotificacionDetalle($idNotificacion);
$formularioHtml = $formulario->gestiona();

$tituloPagina = 'Detalles de la Notificación';
$contenidoPrincipal = $formularioHtml;

require __DIR__ . '/includes/vistas/plantillas/plantilla.php';
?>
