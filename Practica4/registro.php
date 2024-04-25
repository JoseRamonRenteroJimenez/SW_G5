<?php

require_once __DIR__.'/includes/config.php';
require_once __Dir__.'/includes/clases/Usuario.php';
require_once __DIR__.'/includes/formularios/FormularioRegistro.php'; // Ruta correcta hacia FormularioRegistro.php

use es\ucm\fdi\aw\FormularioRegistro;

$form = new FormularioRegistro();
$htmlFormRegistro = $form->gestiona();

$tituloPagina = 'Registro';

$contenidoPrincipal = <<<EOS
<h1>Registro de usuario</h1>
$htmlFormRegistro
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
