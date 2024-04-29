<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php';

class FormularioAnuncioEliminar extends Formulario
{
    public function __construct() {
        parent::__construct('formEliminarAnuncio', ['urlRedireccion' => 'anuncioModificadoResumen.php']);
    }

    protected function generaCamposFormulario(&$datos) {
        // Verificar si el usuario está logeado
        if (!isset($_SESSION['login']) || !$_SESSION['login']) {
            return "Inicie sesión para poder eliminar sus anuncios.";
        }

        // Obtener información del usuario
        $anuncios = Anuncio::getAnunciosByUserId($_SESSION['id']);

        // Mostrar los anuncios del usuario en un formulario para eliminar
        $html = '<fieldset><legend>Eliminar Anuncios</legend>';
        foreach ($anuncios as $anuncio) {
            $html .= '<div>';
            $html .= '<input type="checkbox" id="eliminar_' . $anuncio->getId() . '" name="eliminar_' . $anuncio->getId() . '">';
            $html .= '<label for="eliminar_' . $anuncio->getId() . '">' . $anuncio->getTitulo() . '</label>';
            $html .= '</div>';
        }
        $html .= '<button type="submit" name="eliminar">Eliminar</button>';
        $html .= '</fieldset>';

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        // Procesar la eliminación de los anuncios
        foreach ($datos as $key => $value) {
            if (strpos($key, 'eliminar_') === 0) {
                $idAnuncio = substr($key, strlen('eliminar_'));
                
                // Eliminar el anuncio utilizando el método estático de la clase Anuncio
                if (!Anuncio::borrarPorId($idAnuncio)) {
                    return "Error al eliminar el anuncio.";
                }
            }
        }
        
        return true; // Eliminación exitosa
    }
}
