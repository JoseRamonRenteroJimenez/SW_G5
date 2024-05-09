<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php';
require_once __DIR__.'/../../includes/clases/Notificacion.php';

class FormularioNotificacionDetalle extends Formulario
{
    private $idNotificacion;

    public function __construct($idNotificacion) {
        parent::__construct('formNotificacionDetalle', ['urlRedireccion' => RUTA_APP.'/notificacionListado.php']); // Default redirection after processing
        $this->idNotificacion = $idNotificacion;
    }
    
    protected function generaCamposFormulario(&$datos) {
        $notificacion = Notificacion::buscaNotificacionPorId($this->idNotificacion);
        if (!$notificacion) {
            return "<p>Error: La notificación no existe.</p>";
        }
        
        // Check if the notification is unseen and update its state
        if ($notificacion->getEstado() === Notificacion::NO_VISTO_ESTADO) {
            Notificacion::actualizarEstado($this->idNotificacion, Notificacion::VISTO_ESTADO);
            $notificacion->setEstado(Notificacion::VISTO_ESTADO);  // Update the local object if needed
        }
        
        $tipoLink = $this->determineLink($notificacion->getTipo(), $notificacion->getIdReferencia());
        $html = '<h2>Detalles de la Notificación</h2>';
        $html .= "<p>ID Referencia: {$notificacion->getIdReferencia()}</p>";
        $html .= "<p>Tipo: {$notificacion->getTipo()}</p>";
        $html .= "<p>Título: {$notificacion->getTitulo()}</p>";
        $html .= "<p>Estado: {$this->translateEstado($notificacion->getEstado())}</p>";
        $html .= "<p><a href='{$tipoLink}'>Ver Detalles</a></p>";
    
        return $html;
    }
    
    private function determineLink($tipo, $idReferencia) {
        switch ($tipo) {
            case Notificacion::CONTRATO_TIPO:
                return RUTA_APP . "/contratoDetallado.php?id=$idReferencia";
            case Notificacion::ENCARGO_TIPO:
                return RUTA_APP . "/encargoDetallado.php?id=$idReferencia";
            case Notificacion::NOTICIA_TIPO:
                return RUTA_APP . "/noticiaDetallada.php?id=$idReferencia";
            default:
                return RUTA_APP . "/error.php";  // A generic error page or a notification summary page
        }
    }
    
    private function translateEstado($estado) {
        switch ($estado) {
            case Notificacion::NO_VISTO_ESTADO:
                return 'No Visto';
            case Notificacion::VISTO_ESTADO:
                return 'Visto';
            default:
                return 'Desconocido';  // Handle unexpected values gracefully
        }
    }
    
    protected function procesaFormulario(&$datos) {
        // You can implement specific actions here if needed
    }
}
?>
