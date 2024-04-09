<?php

require_once __DIR__.'/includes/config.php'; // Carga el archivo de configuración de la aplicación
require_once __DIR__.'/includes/formularios/FormularioAnuncios.php'; 
//require_once __DIR__.'/vistas/misAnunciosVista.php';
require_once __DIR__.'/includes/clases/Anuncios.php';

use es\ucm\fdi\aw\FormularioAnuncios; 

$form = new FormularioAnuncios(); // Instancia la clase FormularioAnuncio
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
