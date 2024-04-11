<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/clases/Usuario.php';
require_once __DIR__.'/includes/formularios/FormularioPerfil.php';
require_once __DIR__.'/includes/formularios/FormularioModificarPerfil.php';


use es\ucm\fdi\aw\FormularioPerfil;

$form = new FormularioPerfil(); // Instancia la clase FormularioAnuncio
$htmlFormNewAd = $form->gestiona(); // Obtiene el HTML generado por el formulario

$tituloPagina = 'Perfil'; // Título de la página

// Actualiza el contenido principal para incluir el formulario
$contenidoPrincipal = <<<EOS
<h1>Tu perfil</h1>
$htmlFormNewAd 
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
