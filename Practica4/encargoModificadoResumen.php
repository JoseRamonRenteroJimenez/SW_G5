<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Encargo Modificado'; 

$contenidoPrincipal = <<<EOS
<h1>Encargo modificado correctamente</h1> <!-- Cambiamos el mensaje de confirmación -->
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
