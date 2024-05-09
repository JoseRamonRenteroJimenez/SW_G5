<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $tituloPagina ?></title>
    <!-- Carga el CSS común para todas las páginas -->
    <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>/prueba.css">
    <!-- Carga el CSS específico si está definido -->
    <?php if (isset($cssEspecifico)): ?>
        <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>/<?= $cssEspecifico ?>">
    <?php endif; ?>
    <?php if (isset($cssAnuncio)): ?>
        <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>/<?= $cssAnuncio ?>">
    <?php endif; ?>
</head>
<body>
<div id="contenedor">
<?php
require(RAIZ_APP.'/vistas/comun/cabecera.php');
require(RAIZ_APP.'/vistas/comun/encabezado.php');
?>
    <main>
        <article>
            <?= $contenidoPrincipal ?>
        </article>
    </main>
<?php
//require(RAIZ_APP.'/vistas/comun/sidebarDer.php');
require(RAIZ_APP.'/vistas/comun/pie.php');
?>
</div>
</body>
</html>
