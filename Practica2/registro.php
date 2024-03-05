<?php

require_once __DIR__.'/includes/config.php';

// Cambiar ruta igual que en el resto 
$htmlFormRegistro = new \es\ucm\fdi\aw\usuarios\FormularioRegistro();
$htmlFormRegistro = $formRegistro->gestiona();


$tituloPagina = 'Registro';
$contenidoPrincipal=<<<EOF
  	<h1>Registro en el sistema</h1>
    $htmlFormRegistro
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantillaLoginRegistro.php', $params);

// Auqi igual que en resto tenemos que cambiar algunas cosas
// Cambiar las rutas para que sean funcionales
// Revisar que esta funcionando bien
// Algunos nombres pueden no coincidir y por tanto ponerlos bien