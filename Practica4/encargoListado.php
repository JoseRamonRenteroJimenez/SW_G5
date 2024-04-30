<?php
namespace es\ucm\fdi\aw;
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/clases/Encargo.php';
require_once __DIR__.'/includes/formularios/FormularioEncargoListado.php'; 
require_once __DIR__.'/includes/clases/Usuario.php';

use es\ucm\fdi\aw\FormularioEncargoListado; 

$form = new FormularioEncargoListado();
$htmlFormEncargo = $form->gestiona();

if(isset($_SESSION['rol']) && $_SESSION['rol'] === Usuario::ADMIN_ROLE){
    $tituloPagina = 'Encargos'; 
} else {
    $tituloPagina = 'Tus encargos'; 
}

$contenidoPrincipal = <<<EOS
<h1>Listado de encargos</h1>
$htmlFormEncargo
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
