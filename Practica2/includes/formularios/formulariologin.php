<?php
/*
// Definición del espacio de nombres que corresponde a la ubicación de este archivo dentro del proyecto.
namespace es\CMABIAR RUTA\usuarios;

// Uso de clases necesarias desde otros espacios de nombres.
use CAMBIAR RUTA\Aplicacion;
use CAMBIAR RUTA\Formulario;
*/
// Clase FormularioLogin que extiende de Formulario, específica para el login de usuarios.
class FormularioLogin extends Formulario
{
    // Constructor de la clase que inicializa el formulario con un identificador y una URL de redirección.
    public function __construct() {
        parent::__construct('formLogin', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    // Método para generar los campos del formulario, incluidos los mensajes de error y los campos de usuario y contraseña.
    protected function generaCamposFormulario(&$datos)
    {
        // Intenta reutilizar el nombre de usuario previamente ingresado, si no hay, usa cadena vacía.
        $usuario = $datos['nombreUsuario'] ?? '';

        // Genera el HTML para los errores globales y específicos de cada campo, usando métodos de la clase padre.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'password'], $this->errores, 'span', ['class' => 'error']);

        // Crea el HTML del formulario, utilizando heredoc para facilitar la lectura y mantenimiento.
        $html = <<<HTML
        $htmlErroresGlobales
        <fieldset>
            <legend>Acceso al sistema</legend>
            <div>
                <label for="nombreUsuario">Usuario:</label>
                <input id="nombreUsuario" type="text" name="nombreUsuario" value="$usuario" />
                {$erroresCampos['nombreUsuario']}
            </div>
            <div>
                <label for="password">Contraseña:</label>
                <input id="password" type="password" name="password" />
                {$erroresCampos['password']}
            </div>
            <div>
                <button type="submit" name="login">Ingresar</button>
            </div>
        </fieldset>
HTML;
        return $html;
    }

    // Método para procesar el formulario: valida los datos y autentica al usuario.
    protected function procesaFormulario(&$datos)
    {
        // Limpieza inicial de la lista de errores.
        $this->errores = [];
        // Sanitización y validación del nombre de usuario.
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( !$nombreUsuario ) {
            $this->errores['nombreUsuario'] = 'Es necesario un nombre de usuario';
        }
        
        // Sanitización y validación de la contraseña.
        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( !$password ) {
            $this->errores['password'] = 'La contraseña no debe estar vacía';
        }
        
        // Si no hay errores, intenta el login.
        if (count($this->errores) === 0) {
            $usuario = Usuario::login($nombreUsuario, $password);
            if (!$usuario) {
                $this->errores[] = "Datos de acceso incorrectos";
            } else {
                // En caso de éxito, inicia sesión con el usuario.
                $app = Aplicacion::getInstance();
                $app->login($usuario);
            }
        }
    }
}


// SERIA NECESARIO HACER LAS MODIFICACIONES PARA QUE NOS LLEVE AL PERFIL DE LA EMPRESA O DEL PUEBLO O DEL ADMIN
// DEPENDIENDO DEL TIPO DE USUARIO QUE HAYA INICIADO EL SISTEMA