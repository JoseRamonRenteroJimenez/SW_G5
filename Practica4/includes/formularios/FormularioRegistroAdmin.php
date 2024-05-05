<?php
namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php';

class FormularioRegistroAdmin extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistroAdmin', ['urlRedireccion' => 'index.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $html = <<<EOF
    <fieldset>
        <legend>Registro Administrador</legend>
        <div>
            <label for="nombreUsuario">Nombre de usuario:</label>
            <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" required />
        </div>
        <div>
            <label for="nombre">Nombre:</label>
            <input id="nombre" type="text" name="nombre" required />
        </div>
        <div>
            <label for="password">Password:</label>
            <input id="password" type="password" name="password" required />
        </div>
        <div>
            <label for="password2">Reintroduce el password:</label>
            <input id="password2" type="password" name="password2" required />
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
        $password = trim($datos['password'] ?? '');
        $password2 = trim($datos['password2'] ?? '');

        if (empty($nombreUsuario)) {
            $errores['nombreUsuario'] = "El nombre de usuario no puede estar vacío";
        }

        if (empty($nombre)) {
            $errores['nombre'] = "El nombre no puede estar vacío";
        }

        if (empty($password) || ($password !== $password2)) {
            $errores['password'] = "Las contraseñas no coinciden o están vacías";
        }

        if (count($errores) === 0) {
            $usuario = Usuario::crea($nombreUsuario, $password, $nombre, 1); // Rol '1' para administrador
            if ($usuario === null) {
                $errores['general'] = "Error al crear el usuario administrador";
            } else {
                // Usuario creado correctamente, iniciar sesión o acciones posteriores
            }
        }

        return $errores;
    }
}
?>
