<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/includes/config.php';
require_once __DIR__ . '/includes/clases/Anuncio.php';
require_once __DIR__ . '/includes/formularios/FormularioAnuncioDetalle.php';

$idAnuncio = isset($_GET['id']) ? $_GET['id'] : null;


$anuncio = Anuncio::buscarPorId($idAnuncio);
if (!$anuncio) {
    die("Error: Anuncio no encontrado.");
}

// Instantiate FormularioContratoDetalle and get the HTML
$formulario = new FormularioAnuncioDetalle($idAnuncio);
$formularioHtml = $formulario->gestiona();

$tituloPagina = 'Detalles del Anuncio';
$contenidoPrincipal = $formularioHtml;

require __DIR__ . '/includes/vistas/plantillas/plantilla.php';
?>
