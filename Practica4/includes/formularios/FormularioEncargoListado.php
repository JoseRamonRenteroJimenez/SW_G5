<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php'; 
require_once __DIR__.'/../../includes/clases/Vecino.php';
require_once __DIR__.'/../../includes/clases/Encargo.php'; // Ensure this path is correct

class FormularioEncargoListado extends Formulario
{
    public function __construct() {
        parent::__construct('formEncargoListado', ['urlRedireccion' => '']);
    }
    
    protected function generaCamposFormulario(&$datos) {
        if (!isset($_SESSION['rol'])) {
            return '<p>You must log in to access this functionality.</p>';
        }
    
        $html = '<h2>List of Encargos</h2>';
    
        // Fetch encargos based on user role
        if ($_SESSION['rol'] == Usuario::EMPRESA_ROLE) {
            $encargos = Encargo::buscaEncargosPorEmpresa($_SESSION['id']);
        } elseif ($_SESSION['rol'] == Usuario::VECINO_ROLE) {
            $encargos = Encargo::buscaEncargosPorVecino($_SESSION['id']);
        } elseif ($_SESSION['rol'] == Usuario::ADMIN_ROLE) {
            $encargos = Encargo::getEncargos();
        } else {
            return $html .= '<p>Acceso no autorizado.</p>';
        }
    
        // Check if there are encargos to display
        if (empty($encargos)) {
            return $html . '<p>No encargos found.</p>';
        }
    
        // Generate table of encargos
        $html .= $this->generateEncargosTableHtmlSorted($encargos);
        return $html;
    }
    
    private function generateEncargosTableHtmlSorted($encargos) {
        $estados = [
            Encargo::ACTIVO_ESTADO => 'Active',
            Encargo::FINALIZADO_ESTADO => 'Finished',
            Encargo::CANCELADO_ESTADO => 'Canceled',
            Encargo::ESPERA_ESTADO => 'Pending'
        ];
        $html = '';
    
        foreach ($estados as $estado => $nombreEstado) {
            $html .= "<h3>$nombreEstado</h3>";
            $filteredEncargos = array_filter($encargos, function ($encargo) use ($estado) {
                return $encargo->getEstado() == $estado;
            });
    
            if (empty($filteredEncargos)) {
                $html .= "<p>No encargos $nombreEstado.</p>";
                continue;
            }
    
            $html .= '<table border="1">';
            $html .= '<tr><th>ID</th><th>Company</th><th>Resident</th><th>Date</th><th>Terms</th><th>Status</th><th>Details</th></tr>';
            foreach ($filteredEncargos as $encargo) {
                $html .= sprintf(
                    '<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><a href="encargoDetallado.php?id=%d">View/Action</a></td></tr>',
                    $encargo->getId(),
                    Empresa::buscaNombreEmpresa($encargo->getIdEmpresa()),
                    Vecino::buscaNombreVecino($encargo->getIdVecino()),
                    $encargo->getFecha(),
                    htmlspecialchars($encargo->getTerminos()),
                    $encargo->translateEstado(),
                    $encargo->getId()
                );
            }
            $html .= '</table>';
        }
        return $html;
    }
    
    protected function procesaFormulario(&$datos) {
        // This form does not process any data directly
        return true;
    }
}
?>
