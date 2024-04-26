<?php

require_once __DIR__.'/includes/config.php';
require_once __Dir__.'/includes/clases/Contrato.php'; // Posiblemente no haga falta pero por si acaso
require_once __DIR__.'/includes/formularios/FormularioContrato.php'; // Ruta correcta hacia formulariocontrato.php

use es\ucm\fdi\aw\FormularioContrato;

$form = new FormularioContrato();
$htmlFormContrato = $form->gestiona();

$tituloPagina = 'Contrato';

$contenidoPrincipal = <<<EOS
<h1>Contrato entre ambas partes</h1>
$htmlFormContrato
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
