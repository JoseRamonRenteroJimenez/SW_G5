<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Tablón de anuncios';

$contenidoPrincipal = <<<EOS
<h1> Vaya... Parece que la página está en mantenimiento, intentalo de nuevo mas tarde.</h1>
<p> Página en mantenimiento, pronto volveras a poder usar el tablón de anuncios. </p>
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
