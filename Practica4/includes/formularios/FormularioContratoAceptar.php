<?php
namespace es\ucm\fdi\aw\formularios;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php';  
require_once __DIR__.'/../../includes/clases/Contrato.php';
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Pueblo.php';

class FormularioContratoAceptar extends Formulario
{
    private $idContrato;

    public function __construct() {
        parent::__construct('formContrato', ['urlRedireccion' => 'contratoResumen.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Verificar si el usuario tiene el rol adecuado
        $rol = isset($_SESSION['rol']) ? intval($_SESSION['rol']) : null;
        if ($rol !== Usuario::PUEBLO_ROLE) {
            return "Inicie sesión como pueblo para continuar correctamente.";
        }
        
        // Obtener el contrato específico
        $contrato = Contrato::getContratoById($this->idContrato);
        if (!$contrato) {
            return "No se encontró el contrato especificado.";
        }
        
        // Obtener información relevante del contrato
        $terminos = $contrato->getTerminos();
        $idEmpresa = $contrato->getIdEmpresa();
        $nombreEmpresa = Empresa::buscaNombreEmpresa($idEmpresa);
        $idPueblo = $contrato->getIdPueblo();
        $nombrePueblo = Pueblo::buscaNombrePueblo($idPueblo);
        $fechaInicio = $contrato->getFechaInicio();
        $fechaFinal = $contrato->getFechaFinal();

        // Generar HTML para mostrar el contrato y los botones de aceptar y rechazar
        $html = <<<EOF
        <fieldset>
            <legend>Confirmar Contrato</legend>
            <p>Contrato ID: $this->idContrato</p>
            <p>Términos: $terminos</p>
            <p>ID Empresa: $idEmpresa</p>
            <p>Nombre Empresa: $nombreEmpresa</p>
            <p>ID Pueblo: $idPueblo</p>
            <p>Nombre Pueblo: $nombrePueblo</p>
            <p>Fecha de inicio: $fechaInicio</p>
            <p>Fecha final: $fechaFinal</p>
            <div>
                <button type="submit" name="confirmar" value="aceptar">Aceptar Contrato</button>
                <button type="submit" name="confirmar" value="rechazar">Rechazar Contrato</button>
            </div>
        </fieldset>
    EOF;
        return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        if (!isset($datos['confirmar'])) {
            return false; // No se envió ninguna acción
        }

        // Llamar al método confirmarContrato dependiendo del botón
        switch ($datos['confirmar']) {
            case 'aceptar':
                return Contrato::confirmarContrato($this->idContrato, true);
            case 'rechazar':
                return Contrato::confirmarContrato($this->idContrato, false);
            default:
                return false; // Acción no válida
        }
    }
}
?>
