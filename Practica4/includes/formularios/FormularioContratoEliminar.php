<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php';
require_once __DIR__.'/../../includes/clases/Contrato.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Servicio.php';

class FormularioContratoEliminar extends Formulario
{
    public function __construct() {
        parent::__construct('formEliminarContrato', ['urlRedireccion' => 'contratoEliminadoResumen.php']);
    }

    protected function generaCamposFormulario(&$datos) {

        if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != Usuario::EMPRESA_ROLE && $_SESSION['rol'] != Usuario::PUEBLO_ROLE)) {
            // Si el rol no es válido, mostrar un mensaje de advertencia
            return '<p>Inicie sesión como un rol válido para trabajar con contratos.</p>';
        }

        // Obtener información de los contratos dependiendo del rol del usuario
        if (isset($_SESSION['rol'])) {
            switch ($_SESSION['rol']) {
                case Usuario::EMPRESA_ROLE:
                    // Si el usuario es empresa, obtener los contratos de esa empresa
                    $contratos = Contrato::buscaContratosPorEmpresa($_SESSION['id']);
                    break;
                case Usuario::PUEBLO_ROLE:
                    $contratos = Contrato::buscaContratosPorPueblo($_SESSION['id']);
                    break;
            }
        }

        // Mostrar los contratos en un formulario para eliminar
        $html = '<fieldset><legend>Eliminar Contratos</legend>';
        foreach ($contratos as $contrato) {
            $html .= '<div>';
            $html .= '<input type="checkbox" id="eliminar_' . $contrato->getId() . '" name="eliminar_' . $contrato->getId() . '">';
            $html .= '<label for="eliminar_' . $contrato->getId() . '">Contrato con ID ' . $contrato->getId() . '</label>';
            $html .= '</div>';
        }
        $html .= '<button type="submit" name="eliminar">Eliminar</button>';
        $html .= '</fieldset>';

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        // Procesar la eliminación de los contratos
        foreach ($datos as $key => $value) {
            if (strpos($key, 'eliminar_') === 0) {
                $idContrato = substr($key, strlen('eliminar_'));
                $contrato = Contrato::buscaContratoPorId($idContrato);
                Servicio::disminuirServiciosPorEmpresaYPueblo(Empresa::getAmbitoEmpresa($contrato->getIdEmpresa()),$contrato->getIdPueblo());
                // Eliminar el contrato utilizando el método estático de la clase Contrato
                if (!Contrato::eliminaContratoPorId($idContrato)) {
                    return "Error al eliminar el contrato.";
                }
            }
        }
        
        return true; // Eliminación exitosa
    }
}
?>
