<?php
namespace es\ucm\fdi\aw\pueblos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioRegistroPueblo extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistroPueblo', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Inicialización de variables para mantener el formulario persistente
        $nombre = $datos['nombre'] ?? '';
        $comunidadAutonoma = $datos['comunidadAutonoma'] ?? '';
        $servicios = $datos['servicios'] ?? '';
        
        // Generación de mensajes de error, si existen
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre', 'comunidadAutonoma', 'servicios'], $this->errores, 'span', array('class' => 'error'));

        // Formulario HTML
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Datos del pueblo para el registro</legend>
            <div>
                <label for="nombre">Nombre del pueblo:</label>
                <input id="nombre" type="text" name="nombre" value="$nombre" />
                {$erroresCampos['nombre']}
            </div>
            <div>
                <label for="comunidadAutonoma">Comunidad Autónoma:</label>
                <input id="comunidadAutonoma" type="text" name="comunidadAutonoma" value="$comunidadAutonoma" />
                {$erroresCampos['comunidadAutonoma']}
            </div>
            <div>
                <label for="servicios">Servicios (separados por comas):</label>
                <input id="servicios" type="text" name="servicios" value="$servicios" />
                {$erroresCampos['servicios']}
            </div>
            <div>
                <button type="submit" name="registro">Registrar Pueblo</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
        
        // Procesamiento y validación de los datos enviados por el usuario
        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombre || mb_strlen($nombre) < 3) {
            $this->errores['nombre'] = 'El nombre del pueblo debe tener al menos 3 caracteres.';
        }

        $comunidadAutonoma = trim($datos['comunidadAutonoma'] ?? '');
        $comunidadAutonoma = filter_var($comunidadAutonoma, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$comunidadAutonoma) {
            $this->errores['comunidadAutonoma'] = 'Es necesario especificar la Comunidad Autónoma.';
        }
        
        $servicios = trim($datos['servicios'] ?? '');
        $servicios = filter_var($servicios, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$servicios) {
            $this->errores['servicios'] = 'Debe indicar al menos un servicio.';
        }

        // Si no hay errores, procesar el registro
        if (count($this->errores) === 0) {
            // Aquí iría la lógica para crear el registro del pueblo en la base de datos
            // Por ejemplo: Pueblo::crea($nombre, $comunidadAutonoma, $servicios);
            // Redireccionar al usuario o manejar la post-creación según sea necesario
        }
    }
}
