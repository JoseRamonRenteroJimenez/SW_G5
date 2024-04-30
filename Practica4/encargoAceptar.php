<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/formularios/FormularioEncargoAceptar.php'; 

use es\ucm\fdi\aw\formularios\FormularioEncargoAceptar;

$formulario = new FormularioEncargoAceptar();
$htmlFormulario = $formulario->gestiona();

$tituloPagina = 'Aceptar Encargo';

$contenidoPrincipal = <<<HTML
<h1>Aceptar Encargo</h1>
$htmlFormulario
HTML;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
