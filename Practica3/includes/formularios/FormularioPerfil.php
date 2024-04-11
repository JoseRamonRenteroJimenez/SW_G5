<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once 'Formulario.php';

class FormularioPerfil extends Formulario
{
    public function __construct() {
        parent::__construct('formPerfil', ['urlRedireccion' => '']);
    }

    protected function generaCamposFormulario(&$datos) {
        // Verificar si el usuario está logeado
        if (!isset($_SESSION['login']) || !$_SESSION['login']) {
            return "Inicie sesión para poder ver su perfil.";
        }

        // Obtener información del usuario
        $usuario = Usuario::buscaPorId($_SESSION['id']);
        if (!$usuario) {
            return "Usuario no encontrado.";
        }

        $nombreUsuario = $usuario->getNombreUsuario();
        $nombre = $usuario->getNombre();
        $rol = $usuario->getRol();

        // Mostrar la información del perfil sin campos de entrada
        $html = <<<EOF
        <fieldset>
            <legend>Información del Perfil</legend>
            <div>
                <p><strong>Nombre de usuario:</strong> $nombreUsuario</p>
            </div>
            <div>
                <p><strong>Nombre:</strong> $nombre</p>
            </div>
            <div>
                <p><strong>Rol:</strong> $rol</p>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos) {
        // Ya que solo mostramos información, no necesitamos procesar el formulario.
    }
}
?>
