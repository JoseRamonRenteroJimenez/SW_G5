<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once 'Formulario.php';

class FormularioModificarPerfil extends Formulario
{
    public function __construct() {
        parent::__construct('formPasswordUpdate', ['urlRedireccion' => 'perfil.php']);
    }
    
    protected function generaCamposFormulario(&$datos) { 
        if (!isset($_SESSION['id'])) {
            // Redirecciona al usuario a la página de inicio de sesión si no hay ID en la sesión
            header('Location: login.php');
            exit();
        }

        $usuario = Usuario::buscaPorId($_SESSION['id']);
        if (!$usuario) {
            return "Usuario no encontrado.";
        }
    
        $nombreUsuario = $usuario->getNombreUsuario(); // Obtener el nombre de usuario
        $nombre = $usuario->getNombre(); // Obtener el nombre
    
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'previousPassword', 'password'], $this->errores, 'span', array('class' => 'error'));
    
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
                <button type="submit" name="update">Actualizar</button>
            </div>
        </fieldset>
        EOF;
    }
    
    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        if (!isset($_SESSION['id'])) {
            // Redirecciona al usuario a la página de inicio de sesión si no hay ID en la sesión
            header('Location: login.php');
            exit();
        }
    
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombre = trim($datos['nombre'] ?? '');
        $previousPassword = trim($datos['previousPassword'] ?? '');
        $password = trim($datos['password'] ?? '');
        
        $usuarioId = $_SESSION['id'] ?? null;
        $usuario = Usuario::buscaPorId($usuarioId);
    
        if ($usuario) {
            // Verificar si se intenta cambiar la contraseña
            if (!empty($password) && !empty($previousPassword)) {
                if (!$usuario->compruebaPassword($previousPassword)) {
                    $this->errores['previousPassword'] = 'La contraseña actual no es correcta.';
                } else {
                    // Cambiar la contraseña
                    $usuario->cambiaPassword($password);
                }
            }
            
            if (count($this->errores) === 0) {
                // Actualizar otros datos
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
}
