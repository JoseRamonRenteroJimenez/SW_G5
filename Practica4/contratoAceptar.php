<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/formularios/FormularioContratoAceptar.php'; 

use es\ucm\fdi\aw\formularios\FormularioContratoAceptar;

$formulario = new FormularioContratoAceptar();
$htmlFormulario = $formulario->gestiona();

$tituloPagina = 'Aceptar Contrato';

$contenidoPrincipal = <<<HTML
<h1>Aceptar Contrato</h1>
$htmlFormulario
HTML;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
