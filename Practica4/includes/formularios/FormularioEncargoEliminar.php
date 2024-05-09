<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php';
require_once __DIR__.'/../../includes/clases/Encargo.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once __DIR__.'/../../includes/clases/Empresa.php';

class FormularioEncargoEliminar extends Formulario
{
    public function __construct() {
        parent::__construct('formEliminarEncargo', ['urlRedireccion' => 'encargoEliminadoResumen.php']);
    }

    protected function generaCamposFormulario(&$datos) {

        if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != Usuario::VECINO_ROLE && $_SESSION['rol'] != Usuario::EMPRESA_ROLE)) {
            // Si el rol no es válido, mostrar un mensaje de advertencia
            return '<p>Inicie sesión como un rol válido para trabajar con encargos.</p>';
        }

        // Obtener información de los encargos dependiendo del rol del usuario
        if (isset($_SESSION['rol'])) {
            switch ($_SESSION['rol']) {
                case Usuario::VECINO_ROLE:
                    // Si el usuario es vecino, obtener los encargos de ese vecino
                    $encargos = Encargo::buscaEncargosPorVecino($_SESSION['id']);
                    break;
                case Usuario::EMPRESA_ROLE:
                    $encargos = Encargo::buscaEncargosPorEmpresa($_SESSION['id']);
                    break;
            }
        }

        // Mostrar los encargos en un formulario para eliminar
        $html = '<fieldset><legend>Eliminar Encargos</legend>';
        foreach ($encargos as $encargo) {
            $html .= '<div>';
            $html .= '<input type="checkbox" id="eliminar_' . $encargo->getId() . '" name="eliminar_' . $encargo->getId() . '">';
            $html .= '<label for="eliminar_' . $encargo->getId() . '">Encargo con ID ' . $encargo->getId() . '</label>';
            $html .= '</div>';
        }
        $html .= '<button type="submit" name="eliminar">Eliminar</button>';
        $html .= '</fieldset>';

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        // Procesar la eliminación de los encargos
        foreach ($datos as $key => $value) {
            if (strpos($key, 'eliminar_') === 0) {
                $idEncargo = substr($key, strlen('eliminar_'));
                // Eliminar el encargo utilizando el método estático de la clase Encargo
                if (!Encargo::eliminaEncargoPorId($idEncargo)) {
                    return "Error al eliminar el encargo.";
                }
            }
        }
        
        return true; // Eliminación exitosa
    }
}
?>
