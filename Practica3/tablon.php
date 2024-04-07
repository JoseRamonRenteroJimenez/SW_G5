<?php

require_once __DIR__.'/includes/config.php'; // Carga el archivo de configuración de la aplicación

use es\ucm\fdi\aw\FormularioAnuncio; 

$form = new FormularioAnuncio(); // Instancia la clase FormularioAnuncio
$htmlFormNewAd = $form->gestiona(); // Obtiene el HTML generado por el formulario

$tituloPagina = 'Tablón de Anuncios'; // Título de la página

// Actualiza el contenido principal para incluir el formulario
$contenidoPrincipal = <<<EOS
<h1>Tablón de Anuncios</h1>
<p>Explora los anuncios o publica el tuyo.</p>
$htmlFormNewAd 
EOS;

// Incluye la plantilla que utiliza $contenidoPrincipal.
require __DIR__.'/includes/vistas/plantillas/plantilla.php';
