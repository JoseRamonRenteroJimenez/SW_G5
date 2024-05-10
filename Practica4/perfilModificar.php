<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/clases/Usuario.php';
require_once __DIR__.'/includes/formularios/FormularioPerfilModificar.php';
require_once __DIR__.'/includes/formularios/FormularioPerfilBorrar.php'; 

use es\ucm\fdi\aw\FormularioModificarPerfil;
use es\ucm\fdi\aw\FormularioBorrarPerfil;

$formModificar = new FormularioModificarPerfil();
$htmlFormModificarPerfil = $formModificar->gestiona(); 

$formBorrar = new FormularioBorrarPerfil();
$htmlFormBorrarUsuario = $formBorrar->gestiona(); 

$tituloPagina = 'Modificar/Borrar Perfil';

$contenidoPrincipal = <<<EOS
<h1>Modificar tu perfil</h1>
$htmlFormModificarPerfil
<h2>O borrar tu cuenta permanentemente</h2>
$htmlFormBorrarUsuario
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
