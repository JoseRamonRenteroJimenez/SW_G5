<?php
namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Comunidad.php'; 
require_once __DIR__.'/../../includes/clases/Ambito.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once __DIR__.'/../../includes/clases/Contrato.php';  

class FormularioContrato extends Formulario
{
    public function __construct() {
        parent::__construct('formContrato', ['urlRedireccion' => 'index.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $comunidades = Comunidad::getComunidades(); // Asume que este método devuelve todas las comunidades
    
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['idempresa', 'duracion', 'terminos'], $this->errores, 'span', array('class' => 'error'));
    
        // Inicia el desplegable de comunidades
        $htmlComunidades = "<select id='comunidad' name='comunidad'>";
        foreach ($comunidades as $comunidad) {
            $htmlComunidades .= "<option value='{$comunidad->getId()}'>{$comunidad->getNombre()}</option>";
        }
        $htmlComunidades .= "</select>";
    
        // Inicia el desplegable de pueblos (inicialmente vacío, se llenará con JavaScript)
        $htmlPueblos = "<select id='pueblo' name='pueblo'><option value=''>Seleccione un pueblo...</option></select>";
    
        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        
        $htmlErroresGlobales
        <fieldset>
            <legend>Detalles del contrato</legend>
            <div>
                <label for="comunidad">Comunidad Autónoma:</label>
                $htmlComunidades
                {$erroresCampos['comunidad']}
            </div>
            <div>
                <label for="pueblo">Pueblo:</label>
                $htmlPueblos
                {$erroresCampos['pueblo']}
            </div>
            <div>
                <label for="duracion">Duración (días):</label>
                <input id="duracion" type="text" name="duracion" value="" />
                {$erroresCampos['duracion']}
            </div>
            <div>
                <label for="terminos">Términos:</label>
                <textarea id="terminos" name="terminos"></textarea>
                {$erroresCampos['terminos']}
            </div>
            <div>
                <button type="submit" name="registrarContrato">Registrar Contrato</button>
            </div>
        </fieldset>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectComunidad = document.getElementById('comunidad');
            var selectPueblo = document.getElementById('pueblo');
    
            selectComunidad.addEventListener('change', function() {
                var comunidadId = this.value;
                fetch('ruta/a/tu/servidor/que/devuelve/pueblos?comunidadId=' + comunidadId)
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(pueblos) {
                        selectPueblo.innerHTML = '<option value="">Seleccione un pueblo...</option>';
                        pueblos.forEach(function(pueblo) {
                            var option = new Option(pueblo.nombre, pueblo.id);
                            selectPueblo.add(option);
                        });
                    })
                    .catch(function(error) {
                        console.error('Error: ', error);
                        selectPueblo.innerHTML = '<option value="">Error al cargar pueblos</option>';
                    });
            });
        });
        </script>
    EOF;
        return $html;
    }
    
    
    
    
    protected function procesaFormulario(&$datos)
    {

    }
 
    
}

?>