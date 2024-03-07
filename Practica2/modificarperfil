<?php

require_once __DIR__.'/includes/config.php';

$ModificarPerfil = new \es\ MODIFICAR RUTA \modificarPerfil();
$ModificarPerfil = $ModificarSubastas->gestiona();

 
$tituloPagina = 'Modificar Perfil';
$contenidoPrincipal=<<<EOF
  	<h1>Mi Perfil</h1>
      $ModificarPerfil
    
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantillaPerfil.php', $params);

// Cambiar las rutas igual que en el resto de archivos que he ido añadidiendo.

// NOSE SI SERÁ NECESARIO DISTINGUIR ENTRE SI ES UN PERFIL DE EMPRESA O PUEBLO
// AUNQUE CREO QUE ESTE PHP PUEDE SERVIR PARA LOS DOS 