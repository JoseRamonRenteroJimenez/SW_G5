<?php
namespace es\ucm\fdi\aw\formularios;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php';
require_once __DIR__.'/../../includes/clases/Contrato.php'; 

class FormularioContratoModificar extends Formulario
{
    public function __construct() {
        parent::__construct('formModificarContrato', ['urlRedireccion' => 'contratoModificadoResumen.php']);
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

        // Mostrar los contratos en un formulario para modificar la duración y los términos
        $html = '<fieldset><legend>Modificar Contratos</legend>';
        foreach ($contratos as $contrato) {
            $html .= '<div>';
            $html .= '<label for="duracion_' . $contrato->getId() . '">Duración (días) para el contrato con ID ' . $contrato->getId() . ':</label>';
            $html .= '<input id="duracion_' . $contrato->getId() . '" type="number" name="duracion_' . $contrato->getId() . '" value="' . $contrato->getDuracion() . '" required>';
            $html .= '<label for="terminos_' . $contrato->getId() . '">Términos para el contrato con ID ' . $contrato->getId() . ':</label>';
            $html .= '<input id="terminos_' . $contrato->getId() . '" type="text" name="terminos_' . $contrato->getId() . '" value="' . $contrato->getTerminos() . '" required>';
            $html .= '</div>';
        }
        $html .= '<button type="submit" name="update">Actualizar</button>';
        $html .= '</fieldset>';

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        // Procesar la actualización de los contratos
        foreach ($datos as $key => $value) {
            if (strpos($key, 'duracion_') === 0) {
                $idContrato = substr($key, strlen('duracion_'));
                $duracion = $value;
                $terminos = $datos['terminos_' . $idContrato];
                
                // Actualizar el contrato utilizando el método estático de la clase Contrato
                if (!Contrato::actualiza($idContrato, $duracion, $terminos)) {
                    return "Error al actualizar el contrato.";
                }
            }
        }
        
        return true; // Actualización exitosa
    }
}
?>
