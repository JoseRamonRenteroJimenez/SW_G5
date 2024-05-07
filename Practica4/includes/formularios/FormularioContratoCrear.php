<?php

namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php';  
require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Comunidad.php'; 
require_once __DIR__.'/../../includes/clases/Ambito.php'; 
require_once __DIR__.'/../../includes/clases/Contrato.php';
require_once __DIR__.'/../../includes/clases/Servicio.php';

class FormularioContratoCrear extends Formulario
{
    private $exito = false;

    public function __construct() {
        parent::__construct('formContrato', ['urlRedireccion' => 'contratoResumen.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $rol = isset($_SESSION['rol']) ? intval($_SESSION['rol']) : null;
        if ($rol !== Usuario::EMPRESA_ROLE) {
            return "Inicie sesión como empresa para continuar correctamente.";
        }
        
        $comunidades = Comunidad::getComunidades();
        $pueblos = Pueblo::getPueblos();
    
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['idPueblo', 'idComunidad', 'fechaInicio', 'fechaFinal', 'terminos'], $this->errores, 'span', array('class' => 'error'));

        $htmlComunidades = "<select id='comunidad' name='comunidad'>";
        foreach ($comunidades as $comunidad) {
            $htmlComunidades .= "<option value='{$comunidad->getId()}'>{$comunidad->getNombre()}</option>";
        }
        $htmlComunidades .= "</select>";
    
        $htmlPueblos = "<select id='pueblo' name='pueblo'><option value=''>Seleccione un pueblo...</option>";
        foreach ($pueblos as $pueblo) {
            $htmlPueblos .= "<option value='{$pueblo->getId()}'>{$pueblo->getNombre()}</option>";
        }
        $htmlPueblos .= "</select>";

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
                <label for="fechaInicio">Fecha de inicio:</label>
                <input id="fechaInicio" type="date" name="fechaInicio" value="" />
                {$erroresCampos['fechaInicio']}
            </div>
            <div>
                <label for="fechaFinal">Fecha final:</label>
                <input id="fechaFinal" type="date" name="fechaFinal" value="" />
                {$erroresCampos['fechaFinal']}
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

    $idEmpresa = $_SESSION['id'] ?? '';
    $idPueblo = $datos['pueblo'] ?? '';
    $fechaInicio = $datos['fechaInicio'] ?? '';
    $fechaFinal = $datos['fechaFinal'] ?? '';
    $terminos = $datos['terminos'] ?? '';

    if (empty($idEmpresa)) {
        $this->errores['idEmpresa'] = 'El campo empresa es obligatorio';
    }

    if (empty($idPueblo)) {
        $this->errores['idPueblo'] = 'El campo pueblo es obligatorio';
    }

    if (empty($fechaInicio)) {
        $this->errores['fechaInicio'] = 'El campo fecha de inicio es obligatorio';
    }

    if (empty($fechaFinal)) {
        $this->errores['fechaFinal'] = 'El campo fecha final es obligatorio';
    } elseif ($fechaInicio >= $fechaFinal) {
        $this->errores['fechaFinal'] = 'La fecha final debe ser posterior a la fecha de inicio';
    }

    if (empty($terminos)) {
        $this->errores['terminos'] = 'El campo términos es obligatorio';
    }

    if (count($this->errores) === 0) {
        $resultado = Contrato::inserta($idEmpresa, $idPueblo, $fechaInicio, $fechaFinal, $terminos);

        if ($resultado) {
            $this->exito = true;
            // Redirigir al usuario a la página de resumen del contrato o donde puedan ver el estado del contrato
            header('Location: contratoResumen.php?idContrato=' . $resultado);
            exit();
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