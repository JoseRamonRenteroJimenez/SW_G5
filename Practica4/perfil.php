<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/clases/Usuario.php';
require_once __DIR__.'/includes/formularios/FormularioPerfil.php';
require_once __DIR__.'/includes/formularios/FormularioPerfilModificar.php';

use es\ucm\fdi\aw\FormularioPerfil;
use es\ucm\fdi\aw\Usuario;

// Asegurarse de que la cuenta esté iniciada
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
// Buscar usuario por ID utilizando $_SESSION['id'] solo si está definido
$usuario = Usuario::buscaPorId($_SESSION['id']);

// Definir la ruta de la imagen por defecto
$rutaImagenDefecto = 'imagenes/default.png';

// Determinar la imagen del usuario, si está disponible
$rutaImagenBD = $rutaImagenDefecto; // Define un valor predeterminado para la ruta de la imagen
if ($usuario) {
    $rutaImagenBD = $usuario->getNombreImg() ?: $rutaImagenDefecto;
}
$rutaImagen = $rutaImagenBD;

// Crear el formulario de perfil
$form = new FormularioPerfil();
$htmlFormNewAd = $form->gestiona();

$tituloPagina = 'Perfil';

// Añadir un contenedor para las notificaciones en el perfil
$contenidoPrincipal = <<<EOS
<h1>Tu perfil</h1>
<div id="perfil">
<img src="$rutaImagen" alt="Foto de perfil" style="width: 100px; height: 100px; border-radius: 50%;">
<div id="notificaciones" style="background-color: #f8f9fa; padding: 10px; margin-bottom: 20px;">
    <h2>Notificaciones</h2>
    <ul id="notification-list">
        <!-- Dynamic notifications will be inserted here -->
    </ul>
</div>

$htmlFormNewAd
EOS;

$cssEspecifico = 'perfil.css'; // Nombre del archivo CSS específico para perfil
require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    function fetchNotificaciones() {
        $.get('includes/scriptsApoyo/getNotificaciones.php', function(data) {
            var html = '';
            data.forEach(function(notif) {
                html += '<li>' + notif.titulo + ' - ' + notif.mensaje + '</li>';
            });
            $('#notification-list').html(html); // Update the list within the designated UL
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error fetching notifications: " + textStatus + ", " + errorThrown);
            $('#notification-list').html('<li>Error loading notifications. Please check console for more details.</li>');
        });

    }
    setInterval(fetchNotificaciones, 5000); // Refresh every 5 seconds
    fetchNotificaciones(); // Initial fetch
});
</script>

