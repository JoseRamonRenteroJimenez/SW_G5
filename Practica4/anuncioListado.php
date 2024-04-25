<?php
namespace es\ucm\fdi\aw;
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/clases/Anuncio.php'; // Posiblemente no haga falta pero por si acaso
require_once __DIR__.'/includes/clases/Usuario.php';
require_once __DIR__.'/includes/formularios/FormularioAnuncioListado.php';

use es\ucm\fdi\aw\FormularioAnuncioListado; 

$form = new FormularioAnuncioListado(); 
$htmlFormAnuncio = $form->gestiona(); // Esto igual

if(isset($_SESSION['rol']) && $_SESSION['rol'] === Usuario::ADMIN_ROLE){
    $tituloPagina = 'Anuncios';
} else {
    $tituloPagina = 'Tus anuncios'; 
}

$contenidoPrincipal = <<<EOS
<h1>Listado de anuncios</h1>
$htmlFormAnuncio 
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
