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
            return '<p>You must log in to access this functionality.</p>';
        }
    
        $html = '<h2>List of Notifications</h2>';
        
        // Fetch notifications
        $notificaciones = Notificacion::getNotificacionesPorUsuario($_SESSION['id']);
        
        // Check if there are notifications to display
        if (empty($notificaciones)) {
            return $html . '<p>No notifications found.</p>';
        }

        // Group notifications by seen and unseen status
        $html .= $this->generateNotificationsTableHtml($notificaciones, Notificacion::NO_VISTO_ESTADO);
        $html .= $this->generateNotificationsTableHtml($notificaciones, Notificacion::VISTO_ESTADO);
        
        return $html;
    }

    private function generateNotificationsTableHtml($notificaciones, $estado) {
        $estadoNombre = ($estado === Notificacion::VISTO_ESTADO) ? 'Seen' : 'Unseen';
        $html = "<h3>$estadoNombre Notifications</h3>";
        $filteredNotifications = array_filter($notificaciones, function ($notificacion) use ($estado) {
            return $notificacion->getEstado() == $estado;
        });
    
        if (empty($filteredNotifications)) {
            $html .= "<p>No $estadoNombre notifications.</p>";
            return $html;
        }
    
        $html .= '<table border="1">';
        $html .= '<tr><th>ID</th><th>Type</th><th>Message</th><th>Details</th></tr>';
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
        $baseURL = RUTA_APP;
        $detailsPage = ($notificacion->getTipo() === 'contrato') ? "/contratoDetallado.php?id={$notificacion->getId()}" : "/encargoDetallado.php?id={$notificacion->getId()}";
        return "<a href='{$baseURL}{$detailsPage}'>View Details</a>";
    }
    
    protected function procesaFormulario(&$datos) {
    }
}
?>
