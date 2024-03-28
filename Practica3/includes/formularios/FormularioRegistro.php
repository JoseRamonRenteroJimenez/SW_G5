<?php
namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php'; 

class FormularioRegistro extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => 'index.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Se reutiliza el nombre de usuario introducido previamente o se deja en blanco
        $nombreUsuario = $datos['nombreUsuario'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'password', 'password2'], $this->errores, 'span', array('class' => 'error'));

        // Generar HTML para el formulario
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Datos para el registro</legend>
            <div>
                <label for="nombreUsuario">Nombre de usuario:</label>
                <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" />
                {$erroresCampos['nombreUsuario']}
            </div>
            <div>
                <label for="nombre">Nombre:</label>
                <input id="nombre" type="text" name="nombre"/>
                {$erroresCampos['nombre']}
            </div>
            <div>
                <label for="password">Password:</label>
                <input id="password" type="password" name="password" />
                {$erroresCampos['password']}
            </div>
            <div>
                <label for="password2">Reintroduce el password:</label>
                <input id="password2" type="password" name="password2" />
                {$erroresCampos['password2']}
            </div>
            <div>
                <label for="rol">Rol:</label>
                <select id="rol" name="rol">
                    <option value="admin">Administrador</option>
                    <option value="pueblo">Pueblo</option>
                    <option value="empresa">Empresa</option>
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
        $this->errores = [];
        
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $password = trim($datos['password'] ?? '');
        $nombre = trim($datos['nombre'] ?? '');
        $rol = $datos['rol'] ?? '';

        // Validar nombre de usuario
        if (empty($nombreUsuario)) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario no puede estar vacío';
        }

        // Validar nombre
        if (empty($nombre)) {
            $this->errores['nombre'] = 'El nombre no puede estar vacío';
        }

        // Validar password
        if (empty($password)) {
            $this->errores['password'] = 'El password no puede estar vacío';
        }

        // Validar que las contraseñas coincidan
        $password2 = trim($datos['password2'] ?? '');
        if ($password !== $password2) {
            $this->errores['password2'] = 'Las contraseñas no coinciden';
        }

        // Si hay errores, termina la validación
        if (count($this->errores) > 0) {
            return;
        }

        // Crear el usuario con el rol seleccionado
        $usuario = Usuario::crea($nombreUsuario, $password, $nombre, $rol);

        // Dependiendo del rol, redirigir al formulario correspondiente
        switch ($rol) {
            case 'admin':
                // Redirigir al formulario de registro de administrador
                // header('Location: formulario_registro_admin.php');
                break;
            case 'pueblo':
                if ($usuario !== false) {
                    // Obtener el ID del usuario creado
                    $idUsuario = $usuario->getId();
                    
                    // Redirigir a la página de formulario de registro de pueblo con el ID del usuario
                    header('Location: FormularioRegistroPueblo.php?id='.$idUsuario);
                    exit;
                }
                break;
            case 'empresa':
                // Redirigir al formulario de registro de empresa
                // header('Location: formulario_registro_empresa.php');
                break;
            default:
                // Manejar caso no válido
                break;
        }
    }
}
?>
