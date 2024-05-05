<?php
namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Ambito.php';

class FormularioRegistroEmpresa extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistroEmpresa', ['urlRedireccion' => 'index.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $ambitos = Ambito::getAmbitos();
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $html = <<<EOF
    <fieldset>
        <legend>Registro Empresa</legend>
        <div>
            <label for="nombreUsuario">Nombre de usuario:</label>
            <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" required />
        </div>
        <div>
            <label for="nombre">Nombre:</label>
            <input id="nombre" type="text" name="nombre" required />
        </div>
        <div>
            <label for="nTrabajadores">Número de trabajadores:</label>
            <input id="nTrabajadores" type="number" name="nTrabajadores" required />
        </div>
        <div>
            <label for="ambito">Ámbito:</label>
            <select id="ambito" name="ambito" required>
EOF;
        foreach ($ambitos as $ambito) {
            $html .= "<option value=\"{$ambito->getId()}\">{$ambito->getNombre()}</option>";
        }
        $html .= <<<EOF
            </select>
        </div>
        <div>
            <button type="submit" name="registro">Registrar</button>
        </div>
    </fieldset>
EOF;
        return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        $errores = [];
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombre = trim($datos['nombre'] ?? '');
        $nTrabajadores = trim($datos['nTrabajadores'] ?? '');
        $ambito = trim($datos['ambito'] ?? '');

        if (empty($nombreUsuario)) {
            $errores['nombreUsuario'] = "El nombre de usuario no puede estar vacío";
        }

        if (empty($nombre)) {
            $errores['nombre'] = "El nombre no puede estar vacío";
        }

        if (empty($nTrabajadores) || !is_numeric($nTrabajadores)) {
            $errores['nTrabajadores'] = "Número de trabajadores debe ser un valor numérico y no puede estar vacío";
        }

        if (empty($ambito)) {
            $errores['ambito'] = "Debe seleccionar un ámbito";
        }

        if (count($errores) === 0) {
            $usuario = Usuario::crea($nombreUsuario, '', $nombre, 3); // Rol '3' para empresa
            if ($usuario === null) {
                $errores['general'] = "Error al crear el usuario para la empresa";
            } else {
                $empresa = new Empresa($usuario->getId(), $nTrabajadores, $ambito);
                if (!Empresa::registrar($empresa)) {
                    $errores['general'] = "Error al registrar la empresa";
                }
            }
        }

        return $errores;
    }
}
?>
