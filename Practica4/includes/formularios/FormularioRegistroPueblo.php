<?php
namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php'; 
require_once __DIR__.'/../../includes/clases/Comunidad.php'; 
require_once __DIR__.'/../../includes/clases/Pueblo.php';

class FormularioRegistroPueblo extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistroPueblo', ['urlRedireccion' => 'index.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $comunidadesAutonomas = Comunidad::getComunidades();
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $html = <<<EOF
    <fieldset>
        <legend>Registro Pueblo</legend>
        <div>
            <label for="nombreUsuario">Nombre de usuario:</label>
            <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" required />
        </div>
        <div>
            <label for="nombre">Nombre:</label>
            <input id="nombre" type="text" name="nombre" required />
        </div>
        <div>
            <label for="cif">CIF:</label>
            <input id="cif" type="text" name="cif" required />
        </div>
        <div>
            <label for="comunidad">Comunidad:</label>
            <select id="comunidad" name="comunidad" required>
EOF;
        foreach ($comunidadesAutonomas as $comunidad) {
            $html .= "<option value=\"{$comunidad->getId()}\">{$comunidad->getNombre()}</option>";
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
        $cif = trim($datos['cif'] ?? '');
        $comunidad = trim($datos['comunidad'] ?? '');

        if (empty($nombreUsuario)) {
            $errores['nombreUsuario'] = "El nombre de usuario no puede estar vacío";
        }

        if (empty($nombre)) {
            $errores['nombre'] = "El nombre no puede estar vacío";
        }

        if (empty($cif)) {
            $errores['cif'] = "El CIF no puede estar vacío";
        }

        if (empty($comunidad)) {
            $errores['comunidad'] = "Debe seleccionar una comunidad";
        }

        if (count($errores) === 0) {
            $usuario = Usuario::crea($nombreUsuario, '', $nombre, 2); // Rol '2' para pueblo
            if ($usuario === null) {
                $errores['general'] = "Error al crear el usuario para el pueblo";
            } else {
                $pueblo = new Pueblo($usuario->getId(), $cif, $comunidad);
                if (!Pueblo::registrar($pueblo)) {
                    $errores['general'] = "Error al registrar el pueblo";
                }
            }
        }

        return $errores;
    }
}
?>
