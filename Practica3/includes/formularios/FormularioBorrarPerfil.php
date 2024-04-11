<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once 'Formulario.php';

class FormularioBorrarPerfil extends Formulario
{
    public function __construct() {
        parent::__construct('formBorrarPerfil', ['urlRedireccion' => 'index.php']);
    }

    protected function generaCamposFormulario(&$datos) {
        if (!isset($_SESSION['login']) || !$_SESSION['login']) {
            return "Inicie sesión para poder borrar su perfil.";
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        return <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Borrar cuenta de usuario</legend>
            <div>
                <p>¿Estás seguro de que quieres borrar tu cuenta?</p>
            </div>
            <div>
                <button type="submit" name="confirm" value="Si">Sí, borrar mi cuenta</button>
                <button type="submit" name="confirm" value="No">No, mantener mi cuenta</button>
            </div>
        </fieldset>
        EOF;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];
        
        $result = $datos['confirm'] ?? '';
        
        if ($result === 'Si') {
            $usuarioId = $_SESSION['id'] ?? null;
            if ($usuarioId && Usuario::eliminarUsuario($usuarioId)) {
                // Desconectar al usuario después de borrar su cuenta
                session_destroy();
                header("Location: {$this->urlRedireccion}");
                exit();
            } else {
                $this->errores[] = "No se pudo borrar la cuenta del usuario.";
            }
        }
    }
}
?>
