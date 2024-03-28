<?php
namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php'; 
require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Comunidad.php'; 

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
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'password', 'password2', 'rol', 'nTrabajadores', 'ambito', 'cif', 'comunidad'], $this->errores, 'span', array('class' => 'error'));

        // Obtener lista de comunidades autónomas desde la base de datos
        $comunidadesAutonomas = Comunidad::getComunidades();

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
                {$erroresCampos['rol']}
            </div>
            <div id="infoAdicionalPueblo">
                <label for="cif">CIF:</label>
                <input id="cif" type="text" name="cif"/>
                {$erroresCampos['cif']}
                <label for="comunidad">Comunidad:</label>
                <select id="comunidad" name="comunidad">
    EOF;

        // Agregar opciones de comunidades autónomas al menú desplegable
        foreach ($comunidadesAutonomas as $comunidad) {
            $html .= "<option value=\"{$comunidad->getId()}\">{$comunidad->getNombre()}</option>";
        }

        $html .= <<<EOF
                </select>
                {$erroresCampos['comunidad']}
            </div>
            <div id="infoAdicionalEmpresa">
                <label for="nTrabajadores">Número de trabajadores:</label>
                <input id="nTrabajadores" type="text" name="nTrabajadores"/>
                {$erroresCampos['nTrabajadores']}
                <label for="ambito">Ámbito:</label>
                <input id="ambito" type="text" name="ambito"/>
                {$erroresCampos['ambito']}
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

        // Validar rol
        if (!in_array($rol, ['admin', 'pueblo', 'empresa'])) {
            $this->errores['rol'] = 'Selecciona un rol válido';
        }

        // Si hay errores, termina la validación
        if (count($this->errores) > 0) {
            return;
        }

        // Procesar registro según el rol
        switch ($rol) {
            case 'admin':
                // Redirigir al formulario de registro de administrador
                // header('Location: formulario_registro_admin.php');
                break;
            case 'pueblo':
                case 'pueblo':
                    $cif = trim($datos['cif'] ?? '');
                    $comunidad = trim($datos['comunidad'] ?? '');
                
                    // Validar información adicional para el registro de pueblo
                    if (empty($cif)) {
                        $this->errores['cif'] = 'El CIF no puede estar vacío';
                    }
                    if (empty($comunidad)) {
                        $this->errores['comunidad'] = 'La comunidad no puede estar vacía';
                    }
                
                    // Si hay errores, termina la validación
                    if (count($this->errores) > 0) {
                        return;
                    }
                
                    // Procesar registro de pueblo
                    $usuario = Usuario::crea($nombreUsuario, $password, $nombre, $rol);
                    if ($usuario) {
                        $newId = $datos->idUsuario;
                        $pueblo = new Pueblo($newId, $cif, $comunidad);
                        // Resto del código
                    } else {
                        // Manejar el error de creación de usuario
                    }
                    if (Pueblo::registrar($pueblo)) {
                        // Registro exitoso, redirigir o realizar acciones necesarias
                    } else {
                        // Manejar el error de registro
                    }
                    break;                
            case 'empresa':
                $nTrabajadores = trim($datos['nTrabajadores'] ?? '');
                $ambito = trim($datos['ambito'] ?? '');

                // Validar información adicional para el registro de empresa
                if (empty($nTrabajadores) || !is_numeric($nTrabajadores)) {
                    $this->errores['nTrabajadores'] = 'Introduce un número válido de trabajadores';
                }
                if (empty($ambito)) {
                    $this->errores['ambito'] = 'El ámbito no puede estar vacío';
                }

                // Si hay errores, termina la validación
                if (count($this->errores) > 0) {
                    return;
                }

                // Procesar registro de empresa
                $usuario = Usuario::crea($nombreUsuario, $password, $nombre, $rol);
                $empresa = Empresa::crea($usuario->getId(), $nTrabajadores, $ambito);
                break;
            default:
                // Manejar caso no válido
                break;
        }
    }
}
?>
