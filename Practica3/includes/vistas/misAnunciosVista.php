<?php
require_once __DIR__.'/includes/config.php'; // Ruta correcta hacia config.php
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Anuncios</title>
    <link rel="stylesheet" href="<?php echo RUTA_CSS; ?>/estilo.css">
</head>
<body>

<h1>Mis Anuncios</h1>

<?php
if (empty($anuncios)): ?> 
    <p>No tienes anuncios publicados.</p>
<?php else: ?>
    <div class="lista-anuncios">
        <?php foreach ($anuncios as $anuncio): ?>
            <div class="anuncio">
                <h2><?= htmlspecialchars($anuncio->getTitulo()); ?></h2>
                <p><?= htmlspecialchars($anuncio->getDescripcion()); ?></p>
                <a href="<?= RUTA_APP; ?>/editarAnuncio.php?id=<?= $anuncio->getId(); ?>">Editar</a>
                <a href="<?= RUTA_APP; ?>/borrarAnuncio.php?id=<?= $anuncio->getId(); ?>" onclick="return confirm('¿Estás seguro de que deseas borrar este anuncio?');">Borrar</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>
