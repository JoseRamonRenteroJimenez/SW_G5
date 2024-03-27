<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Faqs';

$contenidoPrincipal = <<<EOS
<p>Una vez sea funcional la web, responderemos a todas las dudas que os puedan surgir a quienes querais formar parte de este proyecto.</p>
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
