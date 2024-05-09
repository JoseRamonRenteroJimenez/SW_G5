<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php';  
require_once __DIR__.'/../../includes/clases/Encargo.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Vecino.php';

class FormularioEncargoAceptar extends Formulario 
{
    private $idEncargo; 

    public function __construct() {
        parent::__construct('formEncargo', ['urlRedireccion' => 'encargoResumen.php']); 
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Verificar si el usuario tiene el rol adecuado
        $rol = isset($_SESSION['rol']) ? intval($_SESSION['rol']) : null;
        if ($rol !== Usuario::EMPRESA_ROLE) {
            return "Inicie sesión como empresa para continuar correctamente.";
        }
        
        // Obtener el encargo específico
        $encargo = Encargo::buscaCEncargoPorId($this->idEncargo); 
        if (!$encargo) {
            return "No se encontró el encargo especificado.";
        }
        
        // Obtener información relevante del encargo
        $descripcion = $encargo->getDescripcion(); 
        $idEmpresa = $encargo->getIdEmpresa(); 
        $nombreEmpresa = Empresa::buscaNombreEmpresa($idEmpresa);
        $idVecino = $encargo->getIdVecino(); 
        $nombreVecino = Vecino::buscaNombreVecino($idVecino);
        $fecha = $encargo->getFecha(); 

        // Generar HTML para mostrar el encargo y los botones de aceptar y rechazar
        $html = <<<EOF
        <fieldset>
            <legend>Confirmar Encargo</legend>
            <p>Encargo ID: $this->idEncargo</p>
            <p>Descripción: $descripcion</p>
            <p>ID Empresa: $idEmpresa</p>
            <p>Nombre Empresa: $nombreEmpresa</p>
            <p>ID Vecino: $idVecino</p>
            <p>Nombre Vecino: $nombreVecino</p>
            <p>Duración (fecha): $fecha</p>
            <div>
                <button type="submit" name="confirmar" value="aceptar">Aceptar Encargo</button>
                <button type="submit" name="confirmar" value="rechazar">Rechazar Encargo</button>
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

        // Llamar al método confirmarEncargo dependiendo del botón
        switch ($datos['confirmar']) {
            case 'aceptar':
                return Encargo::confirmarEncargo($this->idEncargo, true);
            case 'rechazar':
                return Encargo::confirmarEncargo($this->idEncargo, false);
            default:
                return false; // Acción no válida
        }
    }
}
?>
