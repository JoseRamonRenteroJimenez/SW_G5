<?php
require_once __DIR__.'/includes/config.php';

// En el futuro debe mostrar un resumen del encargo

$tituloPagina = 'Encargo Generado'; // Cambia el título de la página a "Encargo Generado"

$contenidoPrincipal = <<<EOS
<h1>Encargo generado correctamente</h1> <!-- Cambia el mensaje de confirmación -->
<p>¡Gracias por generar el encargo!</p> <!-- Cambia el mensaje de confirmación -->
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
