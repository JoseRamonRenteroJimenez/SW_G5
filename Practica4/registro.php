<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/clases/Usuario.php';
require_once __DIR__.'/includes/formularios/FormularioRegistroRol.php'; // Asegúrate de que la ruta sea correcta

use es\ucm\fdi\aw\FormularioRegistroRol;

$form = new FormularioRegistroRol();
$htmlFormRegistro = $form->gestiona();

$tituloPagina = 'Registro';

$contenidoPrincipal = <<<EOS
<h1>Registro de usuario</h1>
$htmlFormRegistro
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
