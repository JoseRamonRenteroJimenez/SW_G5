<?php
namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php';  //Usuario debe estar antes que Pueblo y Empresa
require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Comunidad.php'; 
require_once __DIR__.'/../../includes/clases/Ambito.php'; 
require_once __DIR__.'/../../includes/clases/Contrato.php';
require_once __DIR__.'/../../includes/clases/Servicios.php';


class FormularioContrato extends Formulario
{
    private $exito = false; // Si el contrato es generado correctamente pasa a true

    public function __construct() {
        parent::__construct('formContrato', ['urlRedireccion' => 'contratoResumen.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Verificar si el usuario tiene el rol adecuado
        $rol = $_SESSION['rol'] ?? null;
        if ($rol !== Usuario::EMPRESA_ROLE) {
            return "Inicie sesión como empresa para continuar correctamente.";
        }
        
        // Obtener las comunidades y los pueblos
        $comunidades = Comunidad::getComunidades();
        $pueblos = Pueblo::getPueblos();
    
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['idPueblo', 'idComunidad', 'duracion', 'terminos'], $this->errores, 'span', array('class' => 'error'));

        // Inicia el desplegable de comunidades
        $htmlComunidades = "<select id='comunidad' name='comunidad'>";
        foreach ($comunidades as $comunidad) {
            $htmlComunidades .= "<option value='{$comunidad->getId()}'>{$comunidad->getNombre()}</option>";
        }
        $htmlComunidades .= "</select>";
    
        //Nota -> Posteriormente esto debe sacar solo los pueblos que se encuentren en esa comunidad
        // Inicia el desplegable de pueblos (inicialmente vacío, se llenará con JavaScript)
        $htmlPueblos = "<select id='pueblo' name='pueblo'><option value=''>Seleccione un pueblo...</option>";
        foreach ($pueblos as $pueblo) {
            $htmlPueblos .= "<option value='{$pueblo->getId()}'>{$pueblo->getNombre()}</option>";
        }
        $htmlPueblos .= "</select>";


        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        
        $htmlErroresGlobales
        <fieldset>
            <legend>Detalles del contrato</legend>
            <div>
                <label for="comunidad">Comunidad Autónoma:</label>
                $htmlComunidades
                {$erroresCampos['idComunidad']}
            </div>
            <div>
                <label for="pueblo">Pueblo:</label>
                $htmlPueblos
                {$erroresCampos['idPueblo']}
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
        
    EOF;
        return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        // Obtener los datos del formulario
        $idEmpresa = $_SESSION['id'] ?? '';
        $idPueblo = $datos['pueblo'] ?? '';
        $duracion = $datos['duracion'] ?? '';
        $terminos = $datos['terminos'] ?? '';

        // Validar los campos
        if (empty($idEmpresa)) {
            $this->errores['idEmpresa'] = 'El campo empresa es obligatorio';
        }

        if (empty($idPueblo)) {
            $this->errores['idPueblo'] = 'El campo pueblo es obligatorio';
        }

        if (empty($duracion)) {
            $this->errores['duracion'] = 'El campo duración es obligatorio';
        } elseif (!ctype_digit($duracion) || $duracion <= 0) {
            $this->errores['duracion'] = 'La duración debe ser un número entero positivo';
        }

        if (empty($terminos)) {
            $this->errores['terminos'] = 'El campo términos es obligatorio';
        }

        // Si no hay errores, registrar el contrato
        if (count($this->errores) === 0) {
            $resultado = Contrato::inserta($idEmpresa, $idPueblo, $duracion, $terminos);

            if ($resultado) {
                // Contrato registrado correctamente
                $this->exito = true; // Indicar que el proceso fue exitoso
                // Puedes redirigir a otra página o mostrar un mensaje de éxito
                // Añadir un servicio al pueblo correspondiente
                $empresa = new Empresa($_SESSION['id'], null, null); // Crea una instancia de Empresa
                $ambitoEmpresa = $empresa->getAmbitoEmpresa($idEmpresa); // Obtener el ámbito de la empresa
                Servicio::registrar(new Servicio($idPueblo, $ambitoEmpresa, 1)); // Registrar el servicio en el pueblo
                
            } else {
                $this->errores[] = 'Error al registrar el contrato';
            }
        }
    }

}

?>

<!--

Este script debe también presentar en una lista los servicios que tiene cubiertos el pueblo y cuantas empresas lo ofrecen

<script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectComunidad = document.getElementById('idComunidad'); // Change idComunidad to comunidad
            var selectPueblo = document.getElementById('idPueblo');
        
            selectComunidad.addEventListener('change', function() {
                var comunidadId = this.value;
                fetch('es/ucm/fdi/aw/FormularioContrato?comunidadId=' + comunidadId)
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
 -->