<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once __DIR__ . '/Formulario.php';
require_once __DIR__.'/../../includes/clases/Notificacion.php';

class FormularioNotificacionListado extends Formulario
{
    public function __construct() {
        parent::__construct('formNotificacionListado', ['urlRedireccion' => '']);
    }

    protected function generaCamposFormulario(&$datos) {
        if (!isset($_SESSION['rol'])) {
            return '<p>Debes iniciar sesión para acceder a esta funcionalidad.</p>';
        }
    
        $html = '<h2>Lista de Notificaciones</h2>';
        
        
        $notificaciones = Notificacion::getNotificacionesPorUsuario($_SESSION['id']);
        
     
        if (empty($notificaciones)) {
            return $html . '<p>No se han encontrado notificaciones.</p>';
        }

     
        $html .= $this->generateNotificationsTableHtml($notificaciones, Notificacion::NO_VISTO_ESTADO);
        $html .= $this->generateNotificationsTableHtml($notificaciones, Notificacion::VISTO_ESTADO);
        
        return $html;
    }

    private function generateNotificationsTableHtml($notificaciones, $estado) {
        $estadoNombre = ($estado === Notificacion::VISTO_ESTADO) ? 'vistas' : 'no vistas';
        $html = "<h3>Notificaciones $estadoNombre </h3>";
        $filteredNotifications = array_filter($notificaciones, function ($notificacion) use ($estado) {
            return $notificacion->getEstado() == $estado;
        });
    
        if (empty($filteredNotifications)) {
            $html .= "<p>Notificaciones $estadoNombre.</p>";
            return $html;
        }
    
        $html .= '<table border="1">';
        $html .= '<tr><th>ID</th><th>Tipo</th><th>Mensaje</th><th>Detalles</th></tr>';
        foreach ($filteredNotifications as $notificacion) {
            $link = $this->generateActionLink($notificacion);
            $html .= sprintf(
                '<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                $notificacion->getId(),
                $notificacion->getTipo(),
                htmlspecialchars($notificacion->getMensaje()),
                $link
            );
        }
        $html .= '</table>';
        
        return $html;
    }
    
    private function generateActionLink($notificacion) {
        return "<a href='". RUTA_APP ."/notificacionDetallado.php?id={$notificacion->getId()}'>Ver detalles</a>";
    }
    
    protected function procesaFormulario(&$datos) {
    }
}
?>
