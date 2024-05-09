<?php
namespace es\ucm\fdi\aw;
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/clases/Notificacion.php';  
require_once __DIR__.'/includes/formularios/FormularioNotificacionListado.php';  
require_once __DIR__.'/includes/clases/Usuario.php';

use es\ucm\fdi\aw\FormularioNotificacionListado;

$form = new FormularioNotificacionListado();
$htmlFormNotificacion = $form->gestiona();

if (isset($_SESSION['rol']) && $_SESSION['rol'] === Usuario::ADMIN_ROLE) {
    $tituloPagina = 'Notificaciones';  
} else {
    $tituloPagina = 'Tus notificaciones';  
}

$contenidoPrincipal = <<<EOS
<h1>Listado de notificaciones</h1>
$htmlFormNotificacion
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
