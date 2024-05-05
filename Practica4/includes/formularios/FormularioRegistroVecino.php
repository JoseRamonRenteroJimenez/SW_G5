<?php
namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php'; 
require_once __DIR__.'/../../includes/clases/Vecino.php';
require_once __DIR__.'/../../includes/clases/Pueblo.php';

class FormularioRegistroVecino extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistroVecino', ['urlRedireccion' => 'index.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $pueblos = Pueblo::getPueblos();
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $html = <<<EOF
    <fieldset>
        <legend>Registro Vecino</legend>
        <div>
            <label for="nombreUsuario">Nombre de usuario:</label>
            <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" required />
        </div>
        <div>
            <label for="nombre">Nombre:</label>
            <input id="nombre" type="text" name="nombre" required />
        </div>
        <div>
            <label for="puebloProcedencia">Pueblo de procedencia:</label>
            <select id="puebloProcedencia" name="puebloProcedencia" required>
EOF;
        foreach ($pueblos as $pueblo) {
            $html .= "<option value=\"{$pueblo->getId()}\">{$pueblo->getNombre()}</option>";
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
        $puebloProcedencia = trim($datos['puebloProcedencia'] ?? '');

        if (empty($nombreUsuario)) {
            $errores['nombreUsuario'] = "El nombre de usuario no puede estar vacío";
        }

        if (empty($nombre)) {
            $errores['nombre'] = "El nombre no puede estar vacío";
        }

        if (empty($puebloProcedencia)) {
            $errores['puebloProcedencia'] = "Debe seleccionar un pueblo de procedencia";
        }

        if (count($errores) === 0) {
            $usuario = Usuario::crea($nombreUsuario, '', $nombre, 4); // Rol '4' para vecino
            if ($usuario === null) {
                $errores['general'] = "Error al crear el usuario para el vecino";
            } else {
                $vecino = new Vecino($usuario->getId(), $puebloProcedencia);
                if (!Vecino::registrar($vecino)) {
                    $errores['general'] = "Error al registrar al vecino";
                }
            }
        }

        return $errores;
    }
}
?>
