<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Encargo.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Vecino.php';

class FormularioEncargoDetalle extends Formulario {
    private $idEncargo;

    public function __construct($idEncargo) {
        parent::__construct('formEncargoDetalle', ['urlRedireccion' => RUTA_APP.'/encargoListado.php']);
        $this->idEncargo = $idEncargo;
    }
    
    protected function generaCamposFormulario(&$datos) {
        $encargo = Encargo::buscaEncargoPorId($this->idEncargo);
        if (!$encargo) {
            return "<p>Error: El encargo no existe.</p>";
        }
        
        $nombreEmpresa = Empresa::buscaNombreEmpresa($encargo->getIdEmpresa());
        $nombreVecino = Vecino::buscaNombreVecino($encargo->getIdVecino());
        $estadoEncargo = $encargo->translateEstado();
    
        $html = '<h2>Detalles del Encargo</h2>';
        $html .= "<p>Empresa: {$nombreEmpresa}</p>";
        $html .= "<p>Vecino: {$nombreVecino}</p>";
        $html .= "<p>Fecha: {$encargo->getFecha()}</p>";
        $html .= "<p>Términos: {$encargo->getTerminos()}</p>";
        $html .= "<p>Estado: {$estadoEncargo}</p>";

        // Botones para aceptar, denegar, cancelar o finalizar el encargo
        $html .= '<div>';
        if ($encargo->getEstado() == Encargo::ESPERA_ESTADO && $_SESSION['rol'] == Usuario::EMPRESA_ROLE) {
            $html .= '<button type="submit" name="accion" value="aceptar">Aceptar Encargo</button>';
            $html .= '<button type="submit" name="accion" value="denegar">Denegar Encargo</button>';
        }
        if ($encargo->getEstado() == Encargo::ACTIVO_ESTADO) {
            if ($_SESSION['rol'] == Usuario::EMPRESA_ROLE || $_SESSION['rol'] == Usuario::VECINO_ROLE) {
                $html .= '<button type="submit" name="accion" value="cancelar">Cancelar Encargo</button>';
            }
            if ($_SESSION['rol'] == Usuario::VECINO_ROLE) {
                $html .= '<button type="submit" name="accion" value="finalizar">Finalizar Encargo</button>';
            }
        }
        $html .= '</div>';
    
        return $html;
    }
    
    protected function procesaFormulario(&$datos) {
        if (!isset($datos['accion'])) {
            return "<p>Error: Acción no especificada.</p>";
        }

        $encargo = Encargo::buscaEncargoPorId($this->idEncargo);
        if (!$encargo) {
            return "<p>Error: El encargo no existe.</p>";
        }
    
        $estado = null;
        switch ($datos['accion']) {
            case 'aceptar':
                $estado = Encargo::ACTIVO_ESTADO;
                break;
            case 'denegar':
                $estado = Encargo::CANCELADO_ESTADO;
                break;
            case 'cancelar':
                $estado = Encargo::CANCELADO_ESTADO;
                break;
            case 'finalizar':
                $estado = Encargo::FINALIZADO_ESTADO;
                break;
        }
    
        if ($estado !== null && Encargo::actualizaEstado($this->idEncargo, $estado)) {
            header('Location: ' . $this->urlRedireccion);
            exit();
        } else {
            return "<p>Error al procesar la solicitud.</p>";
        }
    }
}
?>
