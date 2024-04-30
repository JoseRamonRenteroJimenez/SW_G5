<?php
namespace es\ucm\fdi\aw;


require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php';  //Usuario debe estar antes que Pueblo y Empresa
require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php'; 
require_once __DIR__.'/../../includes/clases/Vecino.php';

class FormularioEncargoListado extends Formulario
{
    public function __construct() {
        parent::__construct('formEncargoListado', ['urlRedireccion' => '']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $html = '';

        // Verificar si el usuario está logueado
        if (isset($_SESSION['rol'])) {
            $rol = intval($_SESSION['rol']);
            if ($rol === Usuario::EMPRESA_ROLE) {
                // Si el usuario es empresa, mostrar solo sus encargos
                $encargos = Encargo::buscaEncargosPorEmpresa($_SESSION['id']);
                $html .= '<h2>Tus Encargos:</h2>';
            } elseif ($rol === Usuario::VECINO_ROLE) {
                // Si el usuario es vecino, mostrar solo sus encargos
                $encargos = Encargo::buscaEncargosPorVecino($_SESSION['id']);
                $html .= '<h2>Tus Encargos:</h2>';
            } elseif ($rol === Usuario::ADMIN_ROLE) {
                // Si el usuario es admin, mostrar todos los encargos
                $encargos = Encargo::getEncargos();
                $html .= '<h2>Encargos:</h2>';
            } else {
                // Otros roles no tienen acceso a esta funcionalidad
                $html .= '<p>No tienes permiso para acceder a esta página.</p>';
                return $html;
            }

            // Mostrar encargos
            if (!empty($encargos)) {
                $html .= '<table>';
                $html .= "<tr><td>idEncargo</td><td>terminos</td><td>idVecino</td><td>nombreVecino</td><td>idEmpresa</td><td>nombreEmpresa</td><td>fecha</td><td>estado</td></tr>";
                foreach ($encargos as $encargo) {
                    $idEncargo = $encargo->getId();
                    $terminos = $encargo->getTerminos();
                    $idVecino = $encargo->getIdVecino();
                    $nombreVecino = Vecino::buscaNombreVecino($idVecino);
                    $idEmpresa = $encargo->getIdEmpresa();
                    $nombreEmpresa = Empresa::buscaNombreEmpresa($idEmpresa);
                    $fecha = $encargo->getFecha();
                    $estado = $encargo->getEstado();

                    $html .= "<tr><td>$idEncargo</td><td>$terminos</td><td>$idVecino</td>td>$nombreVecino</td>td>$idEmpresa</td>td>$nombreEmpresa</td><td>$fecha</td><td>$estado</td></tr>";
                }
                $html .= '</table>';
            } else {
                $html .= '<p>No se encontraron encargos.</p>';
            }
        } else {
            $html .= '<p>Debes iniciar sesión para acceder a esta funcionalidad.</p>';
        }

        return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        // Este formulario no procesa ningún dato
        return true;
    }
}
?>
