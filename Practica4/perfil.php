<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/clases/Usuario.php';
require_once __DIR__.'/includes/formularios/FormularioPerfil.php';
require_once __DIR__.'/includes/formularios/FormularioPerfilModificar.php';

use es\ucm\fdi\aw\FormularioPerfil;
use es\ucm\fdi\aw\Usuario;

// Asegurarse de que existe un ID de usuario en la sesión
$usuarioId = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

// Buscar usuario por ID utilizando $_SESSION['id'] solo si está definido
$usuario = isset($_SESSION['id']) ? Usuario::buscaPorId($_SESSION['id']) : null;

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

// Incluir jQuery
$scriptsAdicionales = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';

// Código para cargar y actualizar notificaciones
$scriptsAdicionales .= <<<JS
<script>
$(document).ready(function(){
    function actualizarNotificaciones() {
        $('#notificaciones').load('actualizar_notificaciones.php');
    }
    setInterval(fetchNotificaciones, 5000); // Actualiza cada 5 segundos
    actualizarNotificaciones(); // Cargar al inicio
});
</script>
JS;

// Añadir un contenedor para las notificaciones en el perfil
$contenidoPrincipal = <<<EOS
<h1>Tu perfil</h1>
<div id="perfil">
<img src="$rutaImagen" alt="Foto de perfil" style="width: 100px; height: 100px; border-radius: 50%;">
</div>
<div id="notificaciones" style="background-color: #f8f9fa; padding: 10px; margin-bottom: 20px;">
    <h2>Notificaciones</h2>
    <!-- Las notificaciones se cargarán aquí -->
</div>
$htmlFormNewAd
EOS;

$cssEspecifico = 'perfil.css'; // Nombre del archivo CSS específico para perfil
require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
