<?php
require_once __DIR__.'/includes/config.php';

// Verifica si existe la variable de sesión 'error'
if (isset($_SESSION['error'])) {
    $tituloPagina = 'Error';

    // Mensaje de error por defecto
    $mensajeError = 'Ha ocurrido un error';

    // Se pueden agregar más mensajes de error aquí para reutilización
    $errores = [
        0 => 'Ha intentado registrarse con un nombre en uso',
        // Agrega más mensajes de error según sea necesario
    ];

    // Si el código de error está definido en la lista de errores, utiliza ese mensaje
    if (isset($errores[$_SESSION['error']])) {
        $mensajeError = $errores[$_SESSION['error']];
    }

    // Construye el contenido principal con el mensaje de error
    $contenidoPrincipal = "<h1>$tituloPagina</h1>";
    $contenidoPrincipal .= "<p>$mensajeError</p>";

    // Limpia la variable de sesión 'error' después de mostrar el mensaje
    unset($_SESSION['error']);
} else {
    // Si no hay error, redirige a otra página o realiza otras acciones según sea necesario
    header("Location: index.php");
    exit(); // Asegura que el script se detenga después de redirigir
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
