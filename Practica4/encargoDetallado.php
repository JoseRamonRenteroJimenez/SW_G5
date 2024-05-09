<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/includes/config.php';
require_once __DIR__ . '/includes/clases/Encargo.php';
require_once __DIR__ . '/includes/formularios/FormularioEncargoDetalle.php';

$idEncargo = isset($_GET['id']) ? $_GET['id'] : null;


$encargo = Encargo::buscaEncargoPorId($idEncargo);
if (!$encargo) {
    die("Error: Contract not found.");
}

$formulario = new FormularioEncargoDetalle($idEncargo);
$formularioHtml = $formulario->gestiona();

$tituloPagina = 'Detalles del Encargo';
$contenidoPrincipal = $formularioHtml;

require __DIR__ . '/includes/vistas/plantillas/plantilla.php';
?>
