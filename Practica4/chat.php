<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Chat';

$contenidoPrincipal = <<<EOS
<h1>La página se encuentra en mantenimiento</h1>
<p>Estamos implementando el chat para que la comunación sea mucho mejor... Porfavor... Danos un poco mas de tiempo :)</p>
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
