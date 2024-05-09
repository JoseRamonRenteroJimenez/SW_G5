<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Contrato.php';

class FormularioContratoDetalle extends Formulario
{
    private $idContrato;

    public function __construct($idContrato) {
        parent::__construct('formContratoDetalle', ['urlRedireccion' => RUTA_APP.'/contratoListado.php']); 
        $this->idContrato = $idContrato;
    }
    
    protected function generaCamposFormulario(&$datos) {
        $contrato = Contrato::buscaContratoPorId($this->idContrato);
        if (!$contrato) {
            return "<p>Error: El contrato no existe.</p>";
        }
        // Obtener información relevante del contrato
        $nombreEmpresa = Empresa::buscaNombreEmpresa($contrato->getIdEmpresa());
        $nombrePueblo = Pueblo::buscaNombrePueblo($contrato->getIdPueblo());
        $estadoContrato = $this->translateEstado($contrato->getEstado());
    
        $html = '<h2>Detalles del Contrato</h2>';
        $html .= "<p>Empresa: {$nombreEmpresa}</p>";
        $html .= "<p>Pueblo: {$nombrePueblo}</p>";
        $html .= "<p>Fecha Inicio Actual: {$contrato->getFechaInicial()}</p>";
        $html .= "<p>Fecha Fin Actual: {$contrato->getFechaFinal()}</p>";
        $html .= "<p>Términos Actual: {$contrato->getTerminos()}</p>";
        $html .= "<p>Estado: {$estadoContrato}</p>";

       
        $html .= '<div id="modifiableFields" style="display:none;">';
        $html .= '<label for="fechaInicial">Nueva Fecha Inicial:</label>';
        $html .= '<input type="date" id="fechaInicial" name="fechaInicial" value="' . $contrato->getFechaInicial() . '">';
        $html .= '<label for="fechaFinal">Nueva Fecha Final:</label>';
        $html .= '<input type="date" id="fechaFinal" name="fechaFinal" value="' . $contrato->getFechaFinal() . '">';
        $html .= '<label for="terminos">Nuevos Términos:</label>';
        $html .= '<textarea id="terminos" name="terminos">' . htmlspecialchars($contrato->getTerminos()) . '</textarea>';
        $html .= '</div>';
    
        
        $html .= '<div>';
        if ($contrato->getEstado() == Contrato::ALTERADO_ESTADO && $_SESSION['id'] == $contrato->getIdPueblo()) {
            $html .= '<button type="submit" name="accion" value="aceptar">Aceptar Cambios</button>';
            $html .= '<button type="submit" name="accion" value="denegar">Denegar Cambios</button>';
        }
        if ($contrato->getEstado() == Contrato::ESPERA_ESTADO && $_SESSION['id'] == $contrato->getIdPueblo()) {
            $html .= '<button type="submit" name="accion" value="aceptar">Aceptar Contrato</button>';
            $html .= '<button type="submit" name="accion" value="denegar">Denegar Contrato</button>';
        }
        if ($contrato->getEstado() == Contrato::ACTIVO_ESTADO && ($_SESSION['rol'] == Usuario::EMPRESA_ROLE || $_SESSION['rol'] == Usuario::PUEBLO_ROLE)) {
            if($_SESSION['rol'] == Usuario::EMPRESA_ROLE){$html .= '<button type="button" name="accion" value="modificar" onclick="toggleModifiableFields()">Modificar Contrato</button>';}
            $html .= '<button type="submit" name="accion" value="cancelar">Cancelar Contrato</button>';
        }
        $html .= '<button type="submit" name="accion" value="enviarCambios" style="display:none;" id="enviarCambios">Enviar Cambios</button>';
        $html .= '</div>';
    
        return $html;
    }
    // Traduce el estado del contrato a un string legible
    private function translateEstado($estado) {
        switch ($estado) {
            case Contrato::ACTIVO_ESTADO:
                return 'Activo';
            case Contrato::FINALIZADO_ESTADO:
                return 'Finalizado';
            case Contrato::CANCELADO_ESTADO:
                return 'Cancelado';
            case Contrato::ESPERA_ESTADO:
                return 'En espera';
            case Contrato::ALTERADO_ESTADO:
                return 'Modificado en espera';
            default:
                return 'Desconocido';  
        }
    }
    
    
    protected function procesaFormulario(&$datos) {
        if (!isset($datos['accion'])) {
            return "<p>Error: Acción no especificada.</p>";
        }

       
        $contrato = Contrato::buscaContratoPorId($this->idContrato);
        if (!$contrato) {
            return "<p>Error: El contrato no existe.</p>";
        }
    
        $estado = null;
        switch ($datos['accion']) {
            case 'aceptar':
                $estado = Contrato::ACTIVO_ESTADO;
                break;
            case 'denegar':
                $estado = Contrato::CANCELADO_ESTADO;
                break;
            case 'enviarCambios':
                $estado = Contrato::ALTERADO_ESTADO;
                break;
            case 'cancelar':
                $estado = Contrato::CANCELADO_ESTADO;
                break;
        }
    
        if (isset($estado) && Contrato::actualizarEstadoContrato($this->idContrato, $estado)) {
            header('Location: ' . $this->urlRedireccion);
            exit();
        } else {
            return "<p>Error processing request.</p>";
        }
    }
    

    private function confirmarContrato($confirmacion) {
        return Contrato::confirmarContrato($this->idContrato, $confirmacion);
    }
}
?>

<script>
    window.addEventListener('DOMContentLoaded', (event) => { 
        const modifyButton = document.getElementById("modifyButton"); // Botón para modificar los campos
        if (modifyButton) {
            modifyButton.addEventListener('click', toggleModifiableFields);
        } else {
            console.error('Modify button not found');
        }
    });


    function toggleModifiableFields() { // Función para mostrar/ocultar los campos modificables
    var fields = document.getElementById("modifiableFields");
    console.log(fields); 
    var submitBtn = document.getElementById("enviarCambios");
    console.log(submitBtn); 

    var fieldsDisplay = fields.style.display;
    fields.style.display = fieldsDisplay === "none" ? "block" : "none";
    submitBtn.style.display = fieldsDisplay === "none" ? "inline-block" : "none";
    }

</script>
