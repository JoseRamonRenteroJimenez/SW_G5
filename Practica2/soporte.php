<!-- Vista del soporte en caso de que el usuario necesite ayuda o algo similar, nose si sera necesario, pero por si acaso -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Soporte o Ayuda</title>
</head>
<body>
    <h1>Soporte o Ayuda</h1>
    <p>Si tienes algún problema o pregunta, por favor llena el siguiente formulario y te responderemos lo antes posible.</p>
    <form action="enviar_soporte.php" method="post">
        <label for="email">Tu email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="mensaje">Mensaje:</label>
        <textarea id="mensaje" name="mensaje" required></textarea><br><br>
        <input type="submit" value="Enviar">
    </form>
</body>
</html>
