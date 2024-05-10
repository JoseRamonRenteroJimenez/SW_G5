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

class FormularioContratoListado extends Formulario
{
    public function __construct() {
        parent::__construct('formContratoListado', ['urlRedireccion' => '']);
    }

    protected function generaCamposFormulario(&$datos) {
        if (!isset($_SESSION['rol'])) {
            return '<p>Debes iniciar sesión para acceder a esta funcionalidad.</p>';
        }
    
        $html = '<h2>Lista de Contratos</h2>';
    
        // Obtener contratos según el rol del usuario
        if ($_SESSION['rol'] == Usuario::PUEBLO_ROLE) {
            $contratos = Contrato::buscaContratosPorPueblo($_SESSION['id']);
        } elseif ($_SESSION['rol'] == Usuario::EMPRESA_ROLE) {
            $contratos = Contrato::buscaContratosPorEmpresa($_SESSION['id']);
        } elseif ($_SESSION['rol'] == Usuario::ADMIN_ROLE) {
            $contratos = Contrato::getContratos();
        } else {
            return $html .= '<p>Acceso no autorizado.</p>';
        }
    
      // Mostrar contratos
        if (empty($contratos)) {
            return $html . '<p>No se han encontrado contratos.</p>';
        }
    
     
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
            Contrato::ACTIVO_ESTADO => 'Activo',
            Contrato::FINALIZADO_ESTADO => 'Finalizado',
            Contrato::CANCELADO_ESTADO => 'Cancelado',
            Contrato::ESPERA_ESTADO => 'Pendiente de Aprobación',
            Contrato::ALTERADO_ESTADO => 'Modificacion pendiente'
        ];
        $html = '';
    
        foreach ($estados as $estado => $nombreEstado) {
            $html .= "<h3>$nombreEstado</h3>";
            $filteredContracts = array_filter($contratos, function ($contrato) use ($estado) {
                return $contrato->getEstado() == $estado;
            });
    
            if (empty($filteredContracts)) {
                $html .= "<p>No hay contratos $nombreEstado.</p>";
                continue;
            }
    
            $html .= '<table border="1">';
            $html .= '<tr><th>ID</th><th>Empresa</th><th>Pueblo</th><th>Fecha de Inicio</th><th>Fecha Final</th><th>Terminos</th><th>Estado</th><th>Detalles</th></tr>';
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
