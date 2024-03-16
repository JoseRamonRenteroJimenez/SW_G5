<?php

require_once __DIR__.'/includes/config.php';

$ModificarPerfil = new RUTA_APP.'/includes/src/pueblos/memoria/Pueblo.php';
$ModificarPerfil = $ModificarSubastas->gestiona();

 
$tituloPagina = 'Modificar Perfil';
$contenidoPrincipal=<<<EOF
  	<h1>Mi Perfil</h1>
      $ModificarPerfil
    
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantillaPerfil.php', $params);

