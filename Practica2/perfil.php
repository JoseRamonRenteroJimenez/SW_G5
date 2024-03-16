<!-- Esto no tiene porque implementarse pero, seria la vista del perfil de la empresa o pueblo sin modificar datos -->
<?php include 'perfil_logic.php'; ?> 

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
</head>
<body>
    <h1>Perfil de Usuario</h1>
    <p><strong>Nombre:</strong> <?php echo $usuario['nombre']; ?></p>
    <p><strong>Email:</strong> <?php echo $usuario['email']; ?></p>
    <p><strong>Fecha de Registro:</strong> <?php echo $usuario['fecha_registro']; ?></p>
    <a href="editar_perfil.php">Editar Perfil</a>
</body>
</html>
