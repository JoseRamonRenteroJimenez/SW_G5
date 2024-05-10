<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Contrato Generado';

$contenidoPrincipal = <<<EOS
<h1>Contrato generado correctamente</h1>
<p>¡Gracias por generar el contrato!</p>
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
