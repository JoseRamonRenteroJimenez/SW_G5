<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php';
require_once __DIR__.'/../../includes/clases/Encargo.php'; 

class FormularioEncargoModificar extends Formulario
{
    public function __construct() {
        parent::__construct('formModificarEncargo', ['urlRedireccion' => 'encargoModificadoResumen.php']);
    }

    protected function generaCamposFormulario(&$datos) {

        if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != Usuario::EMPRESA_ROLE && $_SESSION['rol'] != Usuario::VECINO_ROLE)) {
            // Si el rol no es válido, mostrar un mensaje de advertencia
            return '<p>Inicie sesión como un rol válido para trabajar con encargos.</p>';
        }

        // Obtener información de los encargos dependiendo del rol del usuario
        if (isset($_SESSION['rol'])) {
            switch ($_SESSION['rol']) {
                case Usuario::EMPRESA_ROLE:
                    $encargos = Encargo::buscaEncargosPorEmpresa($_SESSION['id']);
                    break;
                case Usuario::VECINO_ROLE:
                    $encargos = Encargo::buscaEncargosPorVecino($_SESSION['id']);
                    break;
            }
        }

        // Mostrar los encargos en un formulario para modificar la descripción y la fecha
        $html = '<fieldset><legend>Modificar Encargos</legend>';
        foreach ($encargos as $encargo) {
            $html .= '<div>';
            $html .= '<label for="descripcion_' . $encargo->getId() . '">Descripción para el encargo con ID ' . $encargo->getId() . ':</label>';
            $html .= '<input id="descripcion_' . $encargo->getId() . '" type="text" name="descripcion_' . $encargo->getId() . '" value="' . $encargo->getDescripcion() . '" required>';
            $html .= '<label for="fecha_' . $encargo->getId() . '">Fecha para el encargo con ID ' . $encargo->getId() . ':</label>';
            $html .= '<input id="fecha_' . $encargo->getId() . '" type="date" name="fecha_' . $encargo->getId() . '" value="' . $encargo->getFecha() . '" required>';
            $html .= '</div>';
        }
        $html .= '<button type="submit" name="update">Actualizar</button>';
        $html .= '</fieldset>';

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        // Procesar la actualización de los encargos
        foreach ($datos as $key => $value) {
            if (strpos($key, 'descripcion_') === 0) {
                $idEncargo = substr($key, strlen('descripcion_'));
                $descripcion = $value;
                $fecha = $datos['fecha_' . $idEncargo];
                
                if (!Encargo::actualiza($idEncargo, $descripcion, $fecha)) {
                    return "Error al actualizar el encargo.";
                }
            }
        }
        
        return true; // Actualización exitosa
    }
}
?>
