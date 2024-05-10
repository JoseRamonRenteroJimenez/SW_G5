<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once 'Formulario.php';

class FormularioModificarPerfil extends Formulario
{
    public function __construct() {
        parent::__construct('formPasswordUpdate', [
            'urlRedireccion' => 'perfil.php',
            'enctype' => 'multipart/form-data'  // Permitir la carga de archivos
        ]);
    }
    
    protected function generaCamposFormulario(&$datos) { 
        if (!isset($_SESSION['id'])) {
            header('Location: login.php');
            exit();
        }
    
        $usuario = Usuario::buscaPorId($_SESSION['id']);
        if (!$usuario) {
            return "Usuario no encontrado.";
        }
    
        $nombreUsuario = $usuario->getNombreUsuario(); 
        $nombre = $usuario->getNombre(); 
        $rutaImagenActual = $usuario->getNombreImg(); 
    
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'previousPassword', 'password', 'fotoPerfil'], $this->errores, 'span', array('class' => 'error'));
    
        return <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Actualizar Perfil</legend>
            <div>
                <label for="nombreUsuario">Nombre de usuario:</label>
                <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" required>
                {$erroresCampos['nombreUsuario']}
            </div>
            <div>
                <label for="nombre">Nombre:</label>
                <input id="nombre" type="text" name="nombre" value="$nombre" required>
                {$erroresCampos['nombre']}
            </div>
            <div>
                <label for="previousPassword">Contraseña actual (para cambios de contraseña):</label>
                <input id="previousPassword" type="password" name="previousPassword">
                {$erroresCampos['previousPassword']}
            </div>
            <div>
                <label for="password">Nueva contraseña:</label>
                <input id="password" type="password" name="password">
                {$erroresCampos['password']}
            </div>
            <div>
                <label for="fotoPerfil">Foto de perfil:</label>
                <input id="fotoPerfil" type="file" name="fotoPerfil" accept="image/*">
                {$erroresCampos['fotoPerfil']}
                <img src="$rutaImagenActual" alt="Foto de perfil actual" style="width: 100px;">
            </div>
            <div>
                <button type="submit" name="update">Actualizar</button>
            </div>
        </fieldset>
        EOF;
    }
    
    
    protected function procesaFormulario(&$datos) {
        $this->errores = [];
    
        if (!isset($_SESSION['id'])) {
            header('Location: login.php');
            exit();
        }
    
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombre = trim($datos['nombre'] ?? '');
        $previousPassword = trim($datos['previousPassword'] ?? '');
        $password = trim($datos['password'] ?? '');
        
        $usuarioId = $_SESSION['id'];
        $usuario = Usuario::buscaPorId($usuarioId);
    
        if ($usuario) {
            if (!empty($password) && !empty($previousPassword)) {
                if (!$usuario->compruebaPassword($previousPassword)) {
                    $this->errores['previousPassword'] = 'La contraseña actual no es correcta.';
                } else {
                    $usuario->cambiaPassword($password);
                }
            }
    
            if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
                $rutaImagen = $this->manejaCargaDeImagen($_FILES['fotoPerfil'], $usuario->getNombreImg());
                if ($rutaImagen) {
                    $usuario->setNombreImg($rutaImagen);  // Actualizar la ruta de la imagen
                }
            }
    
            if (count($this->errores) === 0) {
                $usuario->setNombreUsuario($nombreUsuario);
                $usuario->setNombre($nombre);
    
                if ($usuario->guarda()) { // Guardar los cambios
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['nombreUsuario'] = $nombreUsuario;
                    header("Location: {$this->urlRedireccion}");
                    exit();
                } else {
                    $this->errores[] = "Error al actualizar el perfil.";
                }
            }
        } else {
            $this->errores[] = "Usuario no identificado.";
        }
    }
    

    private function manejaCargaDeImagen($imagen, $rutaImagenActual = null) {
        if ($rutaImagenActual && file_exists($rutaImagenActual)) {
            unlink($rutaImagenActual);  // Eliminar la imagen anterior
        }
    
        $directorioDestino = "uploads/";
        $nombreArchivo = basename($imagen['name']);
        $rutaDestino = $directorioDestino . $nombreArchivo;
    
        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
            return $rutaDestino;
        } else {
            $this->errores['fotoPerfil'] = 'Error al subir la imagen';
            return null;
        }
    }
}
?>
