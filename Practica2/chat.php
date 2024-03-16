<!-- Esta seria una posible vista del chat aunque de momento no sea funcional, pero asi esta -->
<?php include 'mensajes_logic.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensajes Privados</title>
</head>
<body>
    <h1>Mensajes Privados</h1>
    <?php foreach ($mensajes as $mensaje): ?>
        <p><strong>De:</strong> <?php echo $mensaje['de']; ?></p>
        <p><strong>Mensaje:</strong> <?php echo $mensaje['mensaje']; ?></p>
        <hr>
    <?php endforeach; ?>
    <a href="enviar_mensaje.php">Enviar Mensaje</a>
</body>
</html>
