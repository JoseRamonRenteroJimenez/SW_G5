<?php
namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php'; 
require_once __DIR__.'/../../includes/clases/Pueblo.php';

class FormularioRegistroPueblo extends Formulario
{
    private $idUsuario;

    public function __construct($idUsuario) {
        parent::__construct('formRegistroPueblo', ['urlRedireccion' => 'index.php']);
        $this->idUsuario = $idUsuario;
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Obtener las opciones de provincia desde la base de datos
        $provincias = Pueblo::getProvincias();

        // Generar las opciones de provincia para el campo de selección
        $opcionesProvincias = '';
        foreach ($provincias as $provincia) {
            $opcionesProvincias .= "<option value='{$provincia}'>{$provincia}</option>";
        }

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['provincia', 'cif'], $this->errores, 'span', array('class' => 'error'));

        // Generar HTML para el formulario
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Datos adicionales para el registro de Pueblo</legend>
            <div>
                <label for="provincia">Provincia:</label>
                <select id="provincia" name="provincia">
                    $opcionesProvincias
                </select>
                {$erroresCampos['provincia']}
            </div>
            <div>
                <label for="cif">CIF:</label>
                <input id="cif" type="text" name="cif"/>
                {$erroresCampos['cif']}
            </div>
            <input type="hidden" name="idUsuario" value="{$this->idUsuario}">
            <div>
                <button type="submit" name="registroPueblo">Registrar</button>
            </div>
        </fieldset>
        EOF;

        return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $provincia = $datos['provincia'] ?? '';
        $cif = $datos['cif'] ?? '';

        // Validar provincia
        if (empty($provincia)) {
            $this->errores['provincia'] = 'La provincia no puede estar vacía';
        }

        // Validar CIF
        if (empty($cif)) {
            $this->errores['cif'] = 'El CIF no puede estar vacío';
        }

        // Si hay errores, termina la validación
        if (count($this->errores) > 0) {
            return;
        }

        // Crear objeto Pueblo y agregarlo a la base de datos
        Pueblo::crea($provincia, $cif, $datos['idUsuario']);

        // Redirigir a alguna página de éxito
        header('Location: registro_exitoso.php');
        exit;
    }
}
?>
