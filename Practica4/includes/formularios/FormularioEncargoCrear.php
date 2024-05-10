<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Ambito.php'; 
require_once __DIR__.'/../../includes/clases/Vecino.php'; 
require_once __DIR__.'/../../includes/clases/Encargo.php';

class FormularioEncargoCrear extends Formulario
{
    private $exito = false;

    public function __construct() {
        parent::__construct('formContrato', ['urlRedireccion' => 'encargoResumen.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $rol = isset($_SESSION['rol']) ? intval($_SESSION['rol']) : null;
        if ($rol !== Usuario::VECINO_ROLE) {
            return "Inicie sesión como vecino para continuar correctamente.";
        }
        
        // Obtener las empresas
        $empresas = Empresa::getEmpresas();
    
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['idEmpresa', 'terminos'], $this->errores, 'span', array('class' => 'error'));

        // Inicia el desplegable de empresas
        $htmlEmpresas = "<select id='empresa' name='empresa'><option value=''>Seleccione una empresa...</option>";
        foreach ($empresas as $empresa) {
            $htmlEmpresas .= "<option value='{$empresa->getId()}'>{$empresa->getNombre()}</option>";
        }
        $htmlEmpresas .= "</select>";

        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        
        $htmlErroresGlobales
        <fieldset>
            <legend>Detalles del contrato</legend>
            <div>
                <label for="empresa">Empresa:</label>
                $htmlEmpresas
                {$erroresCampos['idEmpresa']}
            </div>
            <div>
                <label for="terminos">Términos:</label>
                <textarea id="terminos" name="terminos"></textarea>
                {$erroresCampos['terminos']}
            </div>
            <div>
                <button type="submit" name="registrarEncargo">Registrar Encargo</button>
            </div>
        </fieldset>
        
    EOF;
        return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        // Obtener los datos del formulario
        $idVecino = $_SESSION['id'] ?? '';
        $idEmpresa = $datos['empresa'] ?? '';
        $terminos = $datos['terminos'] ?? '';

        // Validar los campos
        if (empty($idVecino)) {
            $this->errores['idVecino'] = 'No se ha obtenido bien la id del usuario';
        }

        if (empty($idEmpresa)) {
            $this->errores['idEmpresa'] = 'El campo empresa es obligatorio';
        }

        if (empty($terminos)) {
            $this->errores['terminos'] = 'El campo términos es obligatorio';
        }

        // Si no hay errores, registrar el contrato
        if (count($this->errores) === 0) {
            $resultado = Encargo::inserta($idVecino, $idEmpresa, $terminos);

            if ($resultado) {
                $this->exito = true; // Indicar que el proceso fue exitoso
            } else {
                $this->errores[] = 'Error al registrar el encargo';
            }
        }
    }

}
?>
