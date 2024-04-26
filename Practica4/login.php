<?php

require_once __DIR__.'/includes/config.php';
require_once __Dir__.'/includes/clases/Usuario.php';
require_once __DIR__.'/includes/formularios/FormularioLogin.php'; // Ruta correcta hacia FormularioLogin.php

use es\ucm\fdi\aw\FormularioLogin;

$form = new FormularioLogin();
$htmlFormLogin = $form->gestiona();

$tituloPagina = 'Login';

$contenidoPrincipal = <<<EOS
<h1>Acceso al sistema</h1>
$htmlFormLogin
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
