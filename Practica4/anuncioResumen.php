<?php
require_once __DIR__.'/includes/config.php';

//En el futuro debe mostrar un resumen del contrato

$tituloPagina = 'Anuncio Creado';

$contenidoPrincipal = <<<EOS
<h1>Anuncio creado correctamente</h1>
<p>¡Seguro que mucha gente lo ve!</p>
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
