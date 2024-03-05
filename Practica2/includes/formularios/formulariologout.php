<?php

// Se especifica el espacio de nombres correspondiente a la ubicación de este archivo dentro del proyecto.
namespace CAMBIAR RUTA\aw\usuarios;

// Se importan las clases necesarias de otros espacios de nombres.
use CAMBIAR RUTA\Aplicacion;
use es\CAMBIAR RUTA\aw\Formulario;

// Clase FormularioLogout que extiende de Formulario, diseñada para manejar el cierre de sesión.
class FormularioLogout extends Formulario
{
    // Constructor de la clase, inicializa el formulario con un identificador específico y opciones adicionales.
    public function __construct() {
        // Configura la acción y la URL de redirección después de cerrar sesión.
        parent::__construct('formLogout', [
            'action' =>  Aplicacion::getInstance()->resuelve('/logout.php'), // URL de acción del formulario.
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]); // Cambiado a /inicio.php para diferenciar.
    }

    // Método para generar los campos del formulario. En este caso, un simple botón de cierre de sesión.
    protected function generaCamposFormulario(&$datos)
    {
        // Se define el botón de cierre de sesión utilizando la sintaxis heredoc para el HTML.
        $camposFormulario = <<<EOS
            <button class="btn-logout" type="submit">Salir</button>
        EOS;
        return $camposFormulario;
    }

    /**
     * Procesa los datos del formulario al ser enviado, en este caso, ejecutando la acción de cierre de sesión.
     */
    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance(); // Obtiene la instancia de la aplicación.

        $app->logout(); // Ejecuta la lógica de cierre de sesión.
        $mensajes = ['¡Espereamos verle pronto de nuevo!']; // Mensaje de despedida.
        $app->putAtributoPeticion('mensajes', $mensajes); // Añade el mensaje a la sesión para ser mostrado después del redirect.
        $result = $app->resuelve('/index.php'); // Redirecciona al usuario a la página de inicio tras el logout.

        return $result;
    }
}
