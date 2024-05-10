<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once __DIR__.'/../../includes/clases/Pueblo.php';
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Vecino.php';
require_once __DIR__.'/../../includes/clases/Administrador.php';
require_once __DIR__.'/../../includes/clases/Contrato.php';
require_once __DIR__.'/../../includes/clases/Anuncio.php';
require_once __DIR__.'/../../includes/clases/Encargo.php';

require_once 'Formulario.php';

class FormularioBorrarPerfil extends Formulario
{
    public function __construct() {
        parent::__construct('formBorrarPerfil', ['urlRedireccion' => 'perfilEliminadoResumen.php']);
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
                Anuncio::eliminarPorIdAutor($_SESSION['id']);
                switch ($_SESSION['rol']) {
                    case Usuario::ADMIN_ROLE:
                        Administrador::eliminarPorId($_SESSION['id']);
                        break;
                    case Usuario::EMPRESA_ROLE:
                        Contrato::eliminarContratosEmpresa($_SESSION['id']);
                        Encargo::eliminarEncargosEmpresa($_SESSION['id']);
                        Empresa::eliminarPorId($_SESSION['id']);
                        break;
                    case Usuario::PUEBLO_ROLE:
                        Pueblo::eliminarPorId($_SESSION['id']);
                        Contrato::eliminarContratosPueblo($_SESSION['id']);
                        break;
                    case USUARIO::VECINO_ROLE:
                        Encargo::eliminarEncargosVecino($_SESSION['id']);
                        Vecino::eliminarPorId($_SESSION['id']);
                    default:
                        // Manejar cualquier otro caso
                        break;
                }

                unset($_SESSION['login']);
                unset($_SESSION['nombre']);
                unset($_SESSION['id']);
                unset($_SESSION['rol']);
                unset($_SESSION['esAdmin']);
                
                header("Location: {$this->urlRedireccion}");
                exit();
            } else {
                $this->errores[] = "No se pudo borrar la cuenta del usuario.";
            }
        }
    }
}
?>
