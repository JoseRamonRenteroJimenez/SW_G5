<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/formularios/FormularioEncargoModificar.php'; 
require_once __DIR__.'/includes/formularios/FormularioEncargoEliminar.php'; 

use es\ucm\fdi\aw\FormularioEncargoModificar; 
use es\ucm\fdi\aw\FormularioEncargoEliminar; 


$formModificarEncargos = new FormularioEncargoModificar();
$htmlFormModificarEncargos = $formModificarEncargos->gestiona(); 

$formEliminarEncargos = new FormularioEncargoEliminar();
$htmlFormEliminarEncargos = $formEliminarEncargos->gestiona(); 

$tituloPagina = 'Modificar Encargo'; 

$contenidoPrincipal = <<<EOS
<h1>Modificar Encargo</h1>
$htmlFormModificarEncargos
<h2>Eliminar Encargo</h2>
$htmlFormEliminarEncargos
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
