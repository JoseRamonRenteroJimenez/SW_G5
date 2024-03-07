<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioRegistro extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Mantenemos los campos existentes
        $nombre = $datos['nombre'] ?? '';
        $email = $datos['email'] ?? '';
        $ambito = $datos['ambito'] ?? '';
        $numTrabajadores = $datos['numTrabajadores'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre', 'email', 'ambito', 'numTrabajadores', 'password', 'password2'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Datos para el registro</legend>

            <!-- Campos existentes -->

            <div>
                <label for="ambito">Ámbito:</label>
                <input id="ambito" type="text" name="ambito" value="$ambito" />
                {$erroresCampos['ambito']}
            </div>
            <div>
                <label for="numTrabajadores">Número de Trabajadores:</label>
                <input id="numTrabajadores" type="number" name="numTrabajadores" value="$numTrabajadores" />
                {$erroresCampos['numTrabajadores']}
            </div>

            <!-- Botón de registro existente -->
        </fieldset>
        EOF;
        return $html;
    }
    

    protected function procesaFormulario(&$datos)
    {
        // Procesamiento de los campos existentes
        
        // Procesamiento de los nuevos campos
        $ambito = trim($datos['ambito'] ?? '');
        $numTrabajadores = trim($datos['numTrabajadores'] ?? '');

        // Validaciones específicas para los nuevos campos
        if (!$ambito) {
            $this->errores['ambito'] = 'El ámbito no puede estar vacío.';
        }
        if (!is_numeric($numTrabajadores) || $numTrabajadores < 1) {
            $this->errores['numTrabajadores'] = 'El número de trabajadores debe ser un número positivo.';
        }

        // Continuación del procesamiento si no hay errores
        if (count($this->errores) === 0) {
            // Creación o actualización del usuario/empresa en la base de datos
            // Asegúrate de incluir el manejo de los nuevos campos (ambito, numTrabajadores) aquí
        }
    }
}
