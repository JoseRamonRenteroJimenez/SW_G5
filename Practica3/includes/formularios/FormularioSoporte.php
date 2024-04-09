<?php

namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 

class FormularioSoporte extends Formulario {
    public function __construct() {
        parent::__construct('formSoporte', [
            'urlRedireccion' => '/index.php' // Ajusta esta ruta según sea necesario.
        ]);
    }

    protected function generaCamposFormulario(&$datos) {
        // Utiliza heredoc syntax para generar el HTML del formulario.
        $html = <<<EOF
        <fieldset>
            <legend>Soporte o Ayuda</legend>
            <p>Si tienes algún problema o pregunta, por favor llena el siguiente formulario y te responderemos lo antes posible.</p>
            <div>
                <label for="email">Tu email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" required></textarea>
            </div>
            <div>
                <button type="submit">Enviar</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $errores = [];
        $email = filter_var($datos['email'] ?? null, FILTER_SANITIZE_EMAIL);
        $mensaje = filter_var($datos['mensaje'] ?? null, FILTER_SANITIZE_STRING);
        
        // Validación básica de los campos
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email proporcionado no es válido.";
        }
        if (empty($mensaje)) {
            $errores[] = "El mensaje no puede estar vacío.";
        }
    
        if (count($errores) === 0) {
            // Aquí implementarías el envío del correo a soporte@puebloinnova.com
            $para = 'soporte@puebloinnova.com'; // Dirección de correo de soporte
            $asunto = 'Solicitud de Soporte';
            $cabeceras = "From: $email" . "\r\n" .
                         "Reply-To: $email" . "\r\n" .
                         "X-Mailer: PHP/" . phpversion();
    
            if(mail($para, $asunto, $mensaje, $cabeceras)) {
                // Si el correo se envía exitosamente, 
            } else {
                // Si el correo no se puede enviar
                $errores[] = "Hubo un problema enviando tu mensaje de soporte. Por favor intenta más tarde.";
            }
        }
    
        return $errores;
    }
    
}
