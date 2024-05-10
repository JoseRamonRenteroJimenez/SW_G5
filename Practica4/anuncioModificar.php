<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/formularios/FormularioAnuncioModificar.php';
require_once __DIR__.'/includes/formularios/FormularioAnuncioEliminar.php'; 

use es\ucm\fdi\aw\FormularioAnuncioModificar;
use es\ucm\fdi\aw\FormularioAnuncioEliminar;


$formModificarAnuncios = new FormularioAnuncioModificar();
$htmlFormModificarAnuncios = $formModificarAnuncios->gestiona(); 

// Instancia la clase FormularioAnuncioEliminar
$formEliminarAnuncios = new FormularioAnuncioEliminar();
$htmlFormEliminarAnuncios = $formEliminarAnuncios->gestiona(); 

$tituloPagina = 'Modificar Anuncio'; 

// Actualiza el contenido principal para incluir ambos formularios
$contenidoPrincipal = <<<EOS
<h1>Modificar Anuncio</h1>
$htmlFormModificarAnuncios
<h2>Eliminar Anuncio</h2>
$htmlFormEliminarAnuncios
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
