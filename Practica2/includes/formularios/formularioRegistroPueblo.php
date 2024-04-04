<?php
namespace SW_G5\includes\formularios;

require_once RUTA_FORM . '/formulario.php';
require_once __DIR__.'/usuario.php'; 

//use SW_G5/includes/Usuario; 

class FormularioRegistroPueblo extends Formulario {
    public function __construct() {
        parent::__construct('formRegistroPueblo', [
            'action' => '',
            'method' => 'POST',
            'class' => 'formularioRegistroPueblo',
            'urlRedireccion' => 'index.php'
        ]);
    }
    
    protected function generaCamposFormulario(&$datos = []) {
        $nombre = $datos['nombre'] ?? '';
        $comunidadAutonoma = $datos['comunidadAutonoma'] ?? '';
        $servicios = $datos['servicios'] ?? '';
        $password = $datos['password'] ?? ''; 

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre', 'comunidadAutonoma', 'servicios'], $this->errores);

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Registrar Nuevo Pueblo</legend>
            <div>
                <label for="nombre">Nombre del pueblo:</label>
                <input id="nombre" name="nombre" type="text" value="$nombre" required>
                {$erroresCampos['nombre']}
            </div>
            <div>
                <label for="comunidadAutonoma">Comunidad Autónoma:</label>
                <input id="comunidadAutonoma" name="comunidadAutonoma" type="text" value="$comunidadAutonoma" required>
                {$erroresCampos['comunidadAutonoma']}
            </div>
            <div>
                <label for="servicios">Servicios (separados por comas):</label>
                <input id="servicios" name="servicios" type="text" value="$servicios" required>
                {$erroresCampos['servicios']}
            </div>
            <div>
            <label for="password">Contraseña:</label>
            <input id="password" name="password" type="password" value="$password" required>
            {$erroresCampos['password']}
             </div>
            <div>
                <button type="submit">Registrar Pueblo</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $errores = [];
        $nombre = trim($datos['nombre'] ?? '');
        $comunidadAutonoma = trim($datos['comunidadAutonoma'] ?? '');
        $servicios = trim($datos['servicios'] ?? '');
    
        // Validaciones para $nombre
        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre del pueblo no puede estar vacío.';
        } elseif (strlen($nombre) < 3) {
            $errores['nombre'] = 'El nombre del pueblo debe tener al menos 3 caracteres.';
        }
    
        // Validaciones para $comunidadAutonoma
        if (empty($comunidadAutonoma)) {
            $errores['comunidadAutonoma'] = 'La comunidad autónoma no puede estar vacía.';
        }
    
        // Validaciones para $servicios
        if (empty($servicios)) {
            $errores['servicios'] = 'Debe especificar al menos un servicio.';
        }
        // Validaciones para $password
        if (empty($password)) {
            $errores['password'] = 'La contraseña no puede estar vacía.';
        } elseif (strlen($password) < 6) {
            $errores['password'] = 'La contraseña debe tener al menos 6 caracteres.';
        }
    
    
        if (count($errores) === 0) {
            // Convertir los servicios a JSON antes de enviarlos al método crea
            // Esto depende de cómo estés manejando los servicios en el formulario
            // Suponiendo que $servicios ya es un array o un objeto que puede ser convertido directamente a JSON
            $serviciosJson = json_encode($servicios);
    
            // Llama al método crea de la clase Pueblo
            $pueblo = Pueblo::crea($comunidadAutonoma, $nombre, $serviciosJson, $password);
            
            if (!$pueblo) {
                // Si no se pudo crear el pueblo, añade un error al array
                $errores['general'] = "No se pudo registrar el pueblo correctamente.";
            } else {
                // Si el pueblo se creó correctamente, redirige a la página deseada
                header("Location: {$this->urlRedireccion}");
                exit();
            }
        }
    
        return $errores;
    }
    
}

