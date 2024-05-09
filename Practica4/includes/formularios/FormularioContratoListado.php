<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once __DIR__ . '/Formulario.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';  //Usuario debe estar antes que Pueblo y Empresa
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
require_once __DIR__.'/../../includes/clases/Comunidad.php'; 
require_once __DIR__.'/../../includes/clases/Ambito.php'; 
require_once __DIR__.'/../../includes/clases/Contrato.php';
require_once __DIR__.'/../../includes/clases/Servicio.php';

class FormularioContratoListado extends Formulario
{
    public function __construct() {
        parent::__construct('formContratoListado', ['urlRedireccion' => '']);
    }

    protected function generaCamposFormulario(&$datos) {
        if (!isset($_SESSION['rol'])) {
            return '<p>You must log in to access this functionality.</p>';
        }
    
        $html = '<h2>List of Contracts</h2>';
    
        // Fetch contracts based on user role
        if ($_SESSION['rol'] == Usuario::PUEBLO_ROLE) {
            $contratos = Contrato::buscaContratosPorPueblo($_SESSION['id']);
        } elseif ($_SESSION['rol'] == Usuario::EMPRESA_ROLE) {
            $contratos = Contrato::buscaContratosPorEmpresa($_SESSION['id']);
        } elseif ($_SESSION['rol'] == Usuario::ADMIN_ROLE) {
            $contratos = Contrato::getContratos();
        } else {
            return $html .= '<p>Acceso no autorizado.</p>';
        }
    
        // Check if there are contracts to display
        if (empty($contratos)) {
            return $html . '<p>No contracts found.</p>';
        }
    
        // Return HTML for contracts sorted by state
        $html .= $this->generateContractsTableHtmlSorted($contratos);
        return $html;
    }
    
    private function fetchContracts($role, $userId) {
        switch ($role) {
            case Usuario::PUEBLO_ROLE:
                return Contrato::buscaContratosPorPueblo($userId);
            case Usuario::EMPRESA_ROLE:
                return Contrato::buscaContratosPorEmpresa($userId);
            case Usuario::ADMIN_ROLE:
                return Contrato::getContratos();
            default:
                return [];
        }
    }

    private function generateContractsTableHtmlSorted($contratos) {
        $estados = [
            Contrato::ACTIVO_ESTADO => 'Active',
            Contrato::FINALIZADO_ESTADO => 'Finished',
            Contrato::CANCELADO_ESTADO => 'Canceled',
            Contrato::ESPERA_ESTADO => 'Pending',
            Contrato::ALTERADO_ESTADO => 'Modified Pending'
        ];
        $html = '';
    
        foreach ($estados as $estado => $nombreEstado) {
            $html .= "<h3>$nombreEstado</h3>";
            $filteredContracts = array_filter($contratos, function ($contrato) use ($estado) {
                return $contrato->getEstado() == $estado;
            });
    
            if (empty($filteredContracts)) {
                $html .= "<p>No contracts $nombreEstado.</p>";
                continue;
            }
    
            $html .= '<table border="1">';
            $html .= '<tr><th>ID</th><th>Company</th><th>Village</th><th>Start Date</th><th>End Date</th><th>Terms</th><th>Status</th><th>Details</th></tr>';
            foreach ($filteredContracts as $contrato) {
                $link = $this->generateActionLink($contrato);
                $html .= sprintf(
                    '<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                    $contrato->getId(),
                    Empresa::buscaNombreEmpresa($contrato->getIdEmpresa()),
                    Pueblo::buscaNombrePueblo($contrato->getIdPueblo()),
                    $contrato->getFechaInicial(),
                    $contrato->getFechaFinal(),
                    htmlspecialchars($contrato->getTerminos()),
                    $contrato->translateEstado(),
                    $link
                );
            }
            $html .= '</table>';
        }
        return $html;
    }

    private function generateActionLink($contrato) {
        if ($_SESSION['rol'] == Usuario::PUEBLO_ROLE && $contrato->getEstado() == Contrato::ESPERA_ESTADO) {
            return "<a href='" . RUTA_APP . "/contratoDetallado.php?id={$contrato->getId()}'>View/Action</a>";
        } else {
            return "<a href='" . RUTA_APP . "/contratoDetallado.php?id={$contrato->getId()}'>View</a>";
        }
    }
    
    protected function procesaFormulario(&$datos) {
    }
}
?>
