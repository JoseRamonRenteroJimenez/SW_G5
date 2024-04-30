<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/formularios/FormularioContratoCrear.php'; 

use es\ucm\fdi\aw\FormularioContratoCrear;

$formulario = new FormularioContratoCrear();
$htmlFormulario = $formulario->gestiona();

$tituloPagina = 'Crear Contrato';

$contenidoPrincipal = <<<HTML
<h1>Crear Contrato</h1>
$htmlFormulario
HTML;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
