<?php
require_once __DIR__.'/includes/config.php';
require_once 'includes/vistas/helpers/logout.php';

$tituloPagina = 'Logout';

if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
    $app->redirige('/index.php');
}

$htmlformLogout = new \es\ucm\fdi\aw\usuarios\FormularioLogout();
$htmlformLogout->gestiona();

// AQUI IGUAL, HAY QUE READAPTARLO PARA QUE LA RUTA SEA LA CORRECTA Y FUNCIONE BIEN