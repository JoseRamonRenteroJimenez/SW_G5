<?php

require_once __DIR__.'/includes/config.php';
require_once RUTA_FORM . '/FormularioRegistroPueblo.php';

// Cambiar ruta igual que en el resto 
$htmlFormRegistro = new FormularioRegistroPueblo();
$htmlFormRegistro = $formRegistro->gestiona();


$tituloPagina = 'Registro';
$contenidoPrincipal=<<<EOS;
  	<h1>Registro en el sistema</h1>
    $htmlFormRegistro
    EOS;

require __DIR__.'/includes/vistas/comun/layout.php';

