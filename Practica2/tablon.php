<?php
require_once 'includes/config.php';
//require_once 'includes/vistas/helpers/anuncios.php';

$tituloPagina = 'Tablón de Anuncios';

$contenidoPrincipal=<<<EOS
<h1>Tablón de anuncios</h1>
	<p> Anuncios. </p>
EOS;
/*
// Suponiendo que la función obtenerAnuncios() devuelve un array de anuncios
$anuncios = obtenerAnuncios();

$htmlAnuncios = '<ul class="lista-anuncios">';
foreach ($anuncios as $anuncio) {
    $htmlAnuncios .= <<<EOS
<li>
    <h2>{$anuncio['titulo']}</h2>
    <p>{$anuncio['contenido']}</p>
    <span>Publicado el: {$anuncio['fecha']}</span>
</li>
EOS;
}
$htmlAnuncios .= '</ul>';

$contenidoPrincipal = <<<EOS
<h1>Tablón de Anuncios</h1>
$htmlAnuncios
EOS;
*/
require 'includes/vistas/comun/layout.php';
