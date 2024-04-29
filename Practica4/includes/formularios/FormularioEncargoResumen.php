<?php
namespace es\ucm\fdi\aw\formularios;

require_once __DIR__.'/../clases/Encargo.php';

use es\ucm\fdi\aw\Encargo;

class FormularioEncargoResumen extends Formulario
{
    public function __construct($idEncargo)
    {
        $this->idEncargo = $idEncargo;
    }

    protected function generaCamposFormulario($datos, $errores = array())
    {
        $encargo = Encargo::buscaCEncargoPorId($this->idEncargo);

        if (!$encargo) {
            return '<p>El encargo no existe</p>';
        }

        $html = '<div class="resumen-encargo">';
        $html .= '<h2>Resumen del Encargo</h2>';
        $html .= '<p><strong>ID del Encargo:</strong> ' . $encargo->getId() . '</p>';
        $html .= '<p><strong>Descripción:</strong> ' . $encargo->getDescripcion() . '</p>';
        $html .= '<p><strong>Fecha:</strong> ' . $encargo->getFecha() . '</p>';

        $html .= '</div>';

        return $html;
    }

    protected function procesaFormulario($datos)
    {
        return true;
    }
}
?>
