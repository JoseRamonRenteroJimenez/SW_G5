<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Perfil Eliminado';

$contenidoPrincipal = <<<EOS
<h1>Perfil eliminado correctamente</h1>
<p>¡Hasta pronto!</p>
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
