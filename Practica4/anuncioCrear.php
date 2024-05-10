<?php

require_once __DIR__.'/includes/config.php'; 
require_once __DIR__.'/includes/formularios/FormularioAnuncio.php'; 
require_once __DIR__.'/includes/clases/Anuncio.php';

use es\ucm\fdi\aw\FormularioAnuncios; 

$form = new FormularioAnuncios(); 
$htmlFormNewAd = $form->gestiona(); 

$tituloPagina = 'Tablón de Anuncios'; 

// Actualiza el contenido principal para incluir el formulario
$contenidoPrincipal = <<<EOS
<h1>Tablón de Anuncios</h1>
<p>Explora los anuncios o publica el tuyo.</p>
$htmlFormNewAd 
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
