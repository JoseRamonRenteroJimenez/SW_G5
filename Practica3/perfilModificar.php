<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/clases/Usuario.php';
require_once __DIR__.'/includes/formularios/FormularioPerfilModificar.php';
require_once __DIR__.'/includes/formularios/FormularioPerfilBorrar.php'; 

use es\ucm\fdi\aw\FormularioModificarPerfil;
use es\ucm\fdi\aw\FormularioBorrarPerfil;

// Instancia la clase FormularioModificarPerfil
$formModificar = new FormularioModificarPerfil();
$htmlFormModificarPerfil = $formModificar->gestiona(); // Obtiene el HTML generado por el formulario de modificación

// Instancia la clase FormularioBorrarPerfil
$formBorrar = new FormularioBorrarPerfil();
$htmlFormBorrarUsuario = $formBorrar->gestiona(); // Obtiene el HTML generado por el formulario de borrado

$tituloPagina = 'Modificar/Borrar Perfil'; // Título de la página

// Actualiza el contenido principal para incluir ambos formularios
$contenidoPrincipal = <<<EOS
<h1>Modificar tu perfil</h1>
$htmlFormModificarPerfil
<h2>O borrar tu cuenta permanentemente</h2>
$htmlFormBorrarUsuario
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
