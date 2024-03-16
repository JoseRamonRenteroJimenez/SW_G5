<?php
namespace SW_G5\includes\formularios;
require_once 'formulario.php';

class formularioLogin extends formulario
{
    public function __construct()
    {
        parent::__construct('formLogin', ['urlRedireccion' => 'index.php']);
    }

    protected function generaCamposFormulario(array &$datos): string
    {
        // Se reutiliza el nombre del pueblo introducido previamente o se deja en blanco
        $nombrePueblo = $datos['nombrePueblo'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombrePueblo', 'password'], $this->errores, 'span', array('class' => 'error'));

        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Nombre del pueblo y contraseña</legend>
            <div>
                <label for="nombrePueblo">Nombre del pueblo:</label>
                <input id="nombrePueblo" type="text" name="nombrePueblo" value="$nombrePueblo" />
                {$erroresCampos['nombrePueblo']}
            </div>
            <div>
                <label for="password">Contraseña:</label>
                <input id="password" type="password" name="password" />
                {$erroresCampos['password']}
            </div>
            <div>
                <button type="submit" name="loginPueblo">Entrar como pueblo</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
        $nombrePueblo = trim($datos['nombrePueblo'] ?? '');
        $nombrePueblo = filter_var($nombrePueblo, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$nombrePueblo || empty($nombrePueblo)) {
            $this->errores['nombrePueblo'] = 'El nombre del pueblo no puede estar vacío';
        }

        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$password || empty($password)) {
            $this->errores['password'] = 'La contraseña no puede estar vacía';
        }

        if (count($this->errores) === 0) {
            // Agrega aquí la lógica de autenticación específica para pueblos según tus requisitos.
            // Puedes usar la clase User o cualquier otra que gestione la autenticación.
            $pueblo = PUEBLO::loginPueblo($nombrePueblo, $password);

            if (!$pueblo) {
                $this->errores[] = "El nombre del pueblo o la contraseña no coinciden";
            } else {
                $_SESSION['login'] = true;
                $_SESSION['nombrePueblo'] = $pueblo->getNombrePueblo();
                // Puedes agregar más información de sesión según sea necesario.
            }
        }
    }
}
