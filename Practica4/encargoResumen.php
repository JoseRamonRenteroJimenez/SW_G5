<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Encargo Generado'; 

$contenidoPrincipal = <<<EOS
<h1>Encargo generado correctamente</h1> <!-- Cambia el mensaje de confirmación -->
<p>¡Gracias por generar el encargo!</p> <!-- Cambia el mensaje de confirmación -->
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
