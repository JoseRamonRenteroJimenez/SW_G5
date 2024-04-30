<?php
namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php'; 
require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Comunidad.php'; 
require_once __DIR__.'/../../includes/clases/Ambito.php'; 
require_once __DIR__.'/../../includes/clases/Vecino.php';

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

        //Obtener lista de Pueblos
        $listaProcedencias = Pueblo::getPueblos();
        
        // Obtener lista de ámbitos desde la base de datos
        $ambitos = Ambito::getAmbitos();

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
                <option value="vecino">Vecino</option>
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
            <select id="ambito" name="ambito">
                <option value="-">-</option>
        </div>
    EOF;

            // Agregar opciones de ámbitos al menú desplegable
            foreach ($ambitos as $ambito) {
                $html .= "<option value=\"{$ambito->getId()}\">{$ambito->getNombre()}</option>";
            }

            $html .= <<<EOF
            </select>
            <input id="ambito_manual" type="text" name="ambito_manual" placeholder="Ingrese el ámbito">
            {$erroresCampos['ambito']}
        </div>
        <div id="puebloProcedencia">
            <label for="puebloProcedencia">Pueblo de procedencia:</label>
            <select id="procedencia" name="procedencia">
                <option value="-">-</option>
    EOF;
            // Agregar opciones de ámbitos al menú desplegable
            foreach ($listaProcedencias as $procedencia) {
                $html .= "<option value=\"{$procedencia->getId()}\">{$procedencia->getNombre()}</option>";
            }
            $html .= <<<EOF
            </select>
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

        // Validar nombre de usuario, nombre, password y rol
        if (empty($nombreUsuario)) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario no puede estar vacío';
        }
        if (empty($nombre)) {
            $this->errores['nombre'] = 'El nombre no puede estar vacío';
        }
        if (empty($password)) {
            $this->errores['password'] = 'El password no puede estar vacío';
        }

        // Si hay errores, termina la validación
        if (count($this->errores) > 0) {
            return;
        }

        // Procesar registro según el rol
        switch ($rol) {
            case 'admin':
            case 'empresa':
            case 'pueblo':
            case 'vecino':
                // Procesar registro de usuario
                $usuario = Usuario::crea($nombreUsuario, $password, $nombre, ($rol == 'admin' ? 1 : ($rol == 'empresa' ? 2 : ($rol == 'vecino' ? 4 : 3))));

                if($usuario === 0){
                    $_SESSION['error'] = 0;
                    header('Location: controlErrores.php');
                    exit();
                    break;
                }

                if ($usuario != null) {
                    // Añadir datos únicos a la sesión según el rol
                    if ($rol == 'admin') {
                        $_SESSION['esAdmin'] = true;
                    } elseif ($rol == 'empresa') {
                        $nTrabajadores = trim($datos['nTrabajadores'] ?? '');
                        $ambito = trim($datos['ambito'] ?? '');
                        $ambito_manual = trim($datos['ambito_manual'] ?? '');

                        // Si se seleccionó la opción adicional (-), guardar el ámbito manualmente
                        if ($ambito == '-') {
                            $idAmbito = Ambito::guardarAmbitoManualmente($ambito_manual);
                        } else {
                            $idAmbito = $ambito;
                        }

                        $empresa = new Empresa($usuario->getId(), $nTrabajadores, $idAmbito);
                        if (Empresa::registrar($empresa)) {
                            // Registro exitoso, redirigir o realizar acciones necesarias
                        } else {
                            // Manejar el error de registro
                        }
                    } elseif ($rol == 'pueblo') {
                        $cif = trim($datos['cif'] ?? '');
                        $comunidad = trim($datos['comunidad'] ?? '');

                        $pueblo = new Pueblo($usuario->getId(), $cif, $comunidad);
                        if (Pueblo::registrar($pueblo)) {
                            // Registro exitoso, redirigir o realizar acciones necesarias
                        } else {
                            // Manejar el error de registro
                        }
                    } elseif ($rol == 'vecino') {
                        $procedencia = $datos['procedencia'] ?? '';

                        $vecino = new Vecino($usuario->getId(), $procedencia);
                        if (Vecino::registrar($vecino)) {
                            // Registro exitoso, redirigir o realizar acciones necesarias
                        } else {
                            // Manejar el error de registro
                        }
                    }
                    
                    // Establecer datos comunes en la sesión
                    $_SESSION['login'] = true;
                    $_SESSION['nombre'] = $usuario->getNombre();
                    $_SESSION['id'] = $usuario->getId();
                    $_SESSION['rol'] = $usuario->getRol();
                } else {
                    // Manejar el error de creación de usuario
                }
                
                header('Location: registroResumen.php');
                exit();
                break;
            default:
                // Manejar caso no válido
                break;
        }
    }

}
?>
