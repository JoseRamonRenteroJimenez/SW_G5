<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Anuncios</title>
    <!-- Utiliza la constante RUTA_CSS para vincular tu hoja de estilos -->
    <link rel="stylesheet" href="<?php echo RUTA_CSS; ?>/estilo.css">
</head>
<body>

<h1>Mis Anuncios</h1>

<?php if (empty($anuncios)): ?>
    <p>No tienes anuncios publicados.</p>
<?php else: ?>
    <div class="lista-anuncios">
        <?php foreach ($anuncios as $anuncio): ?>
            <div class="anuncio">
                <h2><?php echo htmlspecialchars($anuncio['titulo']); ?></h2>
                <p><?php echo htmlspecialchars($anuncio['descripcion']); ?></p>
                <!-- Utiliza las constantes para generar dinámicamente los enlaces -->
                <a href="<?php echo RUTA_APP; ?>/editarAnuncio.php?id=<?php echo $anuncio['id']; ?>">Editar</a>
                <a href="<?php echo RUTA_APP; ?>/borrarAnuncio.php?id=<?php echo $anuncio['id']; ?>" onclick="return confirm('¿Estás seguro de que deseas borrar este anuncio?');">Borrar</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>
