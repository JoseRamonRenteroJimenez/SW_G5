<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/includes/config.php';
require_once __DIR__ . '/includes/clases/Contrato.php';
require_once __DIR__ . '/includes/formularios/FormularioContratoDetalle.php';

$idContrato = isset($_GET['id']) ? $_GET['id'] : null;

$contrato = Contrato::buscaContratoPorId($idContrato);
if (!$contrato) {
    die("Error: Contrato No encontrado.");
}

// Formulario para gestionar el contrato
$formulario = new FormularioContratoDetalle($idContrato);
$formularioHtml = $formulario->gestiona();

$tituloPagina = 'Detalles del Contrato';
$contenidoPrincipal = $formularioHtml;

require __DIR__ . '/includes/vistas/plantillas/plantilla.php';
?>
