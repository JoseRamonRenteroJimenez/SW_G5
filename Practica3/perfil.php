<?php

require_once __DIR__.'/includes/config.php';
require_once __Dir__.'/includes/clases/Usuario.php';

use es\ucm\fdi\aw\Usuario;

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php'); 
    exit; 
}

$tituloPagina = 'Perfil';


// Obteniendo información del usuario
$usuario = Usuario::buscaPorId($userId);

$contenidoPrincipal = <<<EOS
<div class="infoUsuario">
EOS;

if ($usuario) {
    $nombreUsuario = $usuario->getNombreUsuario();
    $nombre = $usuario->getNombre();
    $rol = $usuario->getRol();

    $contenidoPrincipal .= <<<EOS
        <h2>Perfil de {$nombreUsuario}</h2>
        <p>Nombre: {$nombre}</p>
        <p>Rol: {$rol}</p>
        EOS;
} else {
    $contenidoPrincipal .= <<<EOS
        <h2>Error al cargar la información del usuario.</h2>
        EOS;
}

$contenidoPrincipal .= <<<EOS
    <a href='{$rutaApp}/src/editaPerfil.php'>Modificar datos</a>
    </div>
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
