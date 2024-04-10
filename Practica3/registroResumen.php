<?php
require_once __DIR__.'/includes/config.php';

// Verifica si el nombre del usuario está definido en la sesión
if (isset($_SESSION['nombre'])) {
    $nombreUsuario = $_SESSION['nombre'];
    $tituloPagina = '¡Bienvenido!';

    // Construye el contenido principal con un mensaje de bienvenida al usuario
    $contenidoPrincipal = "<h1>¡Hola, $nombreUsuario!</h1>";
    $contenidoPrincipal .= "<p>Te damos la bienvenida a nuestro sitio. Gracias por registrarte.</p>";
} else {
    // Si el nombre del usuario no está definido en la sesión, redirige a otra página o realiza otras acciones según sea necesario
    header("Location: index.php");
    exit(); // Asegura que el script se detenga después de redirigir
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
