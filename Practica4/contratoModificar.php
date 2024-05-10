<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/formularios/FormularioContratoModificar.php';
require_once __DIR__.'/includes/formularios/FormularioContratoEliminar.php';

use es\ucm\fdi\aw\FormularioContratoModificar;
use es\ucm\fdi\aw\FormularioContratoEliminar;

$formModificarContratos = new FormularioContratoModificar();
$htmlFormModificarContratos = $formModificarContratos->gestiona(); 

$formEliminarContratos = new FormularioContratoEliminar();
$htmlFormEliminarContratos = $formEliminarContratos->gestiona(); 

$tituloPagina = 'Modificar Contrato'; 

// Actualiza el contenido principal para incluir ambos formularios
$contenidoPrincipal = <<<EOS
<h1>Modificar Contrato</h1>
$htmlFormModificarContratos
<h2>Eliminar Contrato</h2>
$htmlFormEliminarContratos
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
