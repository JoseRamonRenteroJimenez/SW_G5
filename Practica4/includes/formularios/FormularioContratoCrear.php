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
    
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['comunidad', 'pueblo', 'fechaInicio', 'fechaFinal', 'terminos'], $this->errores, 'span', array('class' => 'error'));
        
        $htmlComunidades = "<select id='comunidad' name='comunidad' onchange='updatePueblos()'>";
        $htmlComunidades .= "<option value=''>Seleccione una comunidad...</option>";
        foreach ($comunidades as $comunidad) {
            $htmlComunidades .= "<option value='{$comunidad->getId()}'>{$comunidad->getNombre()}</option>";
        }
        $htmlComunidades .= "</select>";
    
        $htmlPueblos = "<select id='pueblo' name='pueblo'><option value=''>Seleccione un pueblo...</option></select>";

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

    public static function getPueblosPorComunidad($comunidadId) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT id, nombre FROM pueblos WHERE comunidad_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $comunidadId);
        $stmt->execute();
        $result = $stmt->get_result();
        $pueblos = [];
        while ($row = $result->fetch_assoc()) {
            $pueblos[] = ['id' => $row['id'], 'nombre' => $row['nombre']];
        }
        $stmt->close();
        return $pueblos;
    }
    
}
?>

<script>
function updatePueblos() {
    const comunidadId = document.getElementById('comunidad').value;
    const puebloSelect = document.getElementById('pueblo');
    puebloSelect.innerHTML = '<option>Loading...</option>';

    fetch('includes/scriptsApoyo/getPueblos.php?comunidadId=' + comunidadId)
        .then(response => response.json())
        .then(data => {
            puebloSelect.innerHTML = '<option value="">Seleccione un pueblo...</option>';
            data.forEach(pueblo => {
                puebloSelect.innerHTML += `<option value="${pueblo.id}">${pueblo.nombre}</option>`;
            });
        })
        .catch(error => {
            console.error('Error loading the pueblos:', error);
            puebloSelect.innerHTML = '<option value="">Error loading pueblos</option>';
        });
}
</script>
