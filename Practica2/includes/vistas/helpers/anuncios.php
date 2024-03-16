<?php
require_once 'includes/config.php';
//require_once 'includes/funciones.php'; // Asumiendo que aquí tienes funciones para obtener los anuncios

$tituloPagina = 'Tablón de Anuncios';
$anuncios = obtenerAnuncios(); // Supongamos que esta función devuelve todos los anuncios en un array

// Iniciar la variable que contendrá el HTML de los anuncios
$htmlAnuncios = '';

// Crear el HTML para cada anuncio
foreach ($anuncios as $anuncio) {
    $htmlAnuncios .= <<<EOS
    <div class="evento">
        <img src="{$anuncio['imagen']}" alt="Imagen del evento">
        <h3>{$anuncio['titulo']}</h3>
        <p>{$anuncio['descripcion']}</p>
        <p>Organizado por: {$anuncio['organizador']}</p>
        <a href="{$anuncio['url']}">Apuntate!</a>
    </div>
EOS;
}

$contenidoPrincipal = <<<EOS
<div class="container-anuncios">
    <h1>Próximos Eventos</h1>
    <div class="anuncios-grid">
        $htmlAnuncios
    </div>
</div>
EOS;

require 'includes/vistas/comun/layout.php';
