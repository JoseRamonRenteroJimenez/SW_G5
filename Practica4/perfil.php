<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/clases/Usuario.php';
require_once __DIR__.'/includes/formularios/FormularioPerfil.php';
require_once __DIR__.'/includes/formularios/FormularioPerfilModificar.php';

use es\ucm\fdi\aw\FormularioPerfil;

$form = new FormularioPerfil();
$htmlFormNewAd = $form->gestiona();

$tituloPagina = 'Perfil';

// Incluir jQuery 
$scriptsAdicionales = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';

// Código para cargar y actualizar notificaciones
$scriptsAdicionales .= <<<JS
<script>
$(document).ready(function(){
    function actualizarNotificaciones() {
        $('#notificaciones').load('actualizar_notificaciones.php');
    }
    setInterval(fetchNotificaciones, 5000); // Actualiza cada 5 segundos
    actualizarNotificaciones(); // Cargar al inicio
});
</script>
JS;

// Añadir un contenedor para las notificaciones en el perfil
$contenidoPrincipal = <<<EOS
<h1>Tu perfil</h1>
<div id="notificaciones" style="background-color: #f8f9fa; padding: 10px; margin-bottom: 20px;">
    <h2>Notificaciones</h2>
    <!-- Las notificaciones se cargarán aquí -->
</div>
$htmlFormNewAd
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
