<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/formularios/FormularioEncargoCrear.php'; 

use es\ucm\fdi\aw\FormularioEncargoCrear;

$formulario = new FormularioEncargoCrear();
$htmlFormulario = $formulario->gestiona();

$tituloPagina = 'Crear Encargo';

$contenidoPrincipal = <<<HTML
<h1>Crear Encargo</h1>
$htmlFormulario
HTML;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
