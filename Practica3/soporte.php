<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/formularios/FormularioSoporte.php'; 

use es\ucm\fdi\aw\FormularioSoporte; 

$formSoporte = new FormularioSoporte();  // Crea un nuevo formulario de soporte
$htmlFormSoporte = $formSoporte->gestiona(); // Se encarga de procesar el formulario

$tituloPagina = 'Soporte o Ayuda';

$contenidoPrincipal = <<<EOS
<h1>Soporte o Ayuda</h1>
$htmlFormSoporte 
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
