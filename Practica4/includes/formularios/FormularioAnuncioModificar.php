<?php
namespace es\ucm\fdi\aw\formularios;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php';
require_once __DIR__.'/../../includes/clases/Anuncio.php'; 

class FormularioAnuncioModificar extends Formulario
{
    public function __construct() {
        parent::__construct('formModificarAnuncio', ['urlRedireccion' => 'anuncioModificadoResumen.php']);
    }

    protected function generaCamposFormulario(&$datos) {
        // Verificar si el usuario está logeado
        if (!isset($_SESSION['login']) || !$_SESSION['login']) {
            return "Inicie sesión para poder modificar sus anuncios.";
        }

        // Obtener información del usuario
        $anuncios = Anuncio::getAnunciosByUserId($_SESSION['id']);

        // Mostrar los anuncios del usuario en un formulario para modificar
        $html = '<fieldset><legend>Modificar Anuncios</legend>';
        foreach ($anuncios as $anuncio) {
            $html .= '<div>';
            $html .= '<label for="titulo_' . $anuncio->getId() . '">Título:</label>';
            $html .= '<input id="titulo_' . $anuncio->getId() . '" type="text" name="titulo_' . $anuncio->getId() . '" value="' . $anuncio->getTitulo() . '" required>';
            $html .= '<label for="descripcion_' . $anuncio->getId() . '">Descripción:</label>';
            $html .= '<input id="descripcion_' . $anuncio->getId() . '" type="text" name="descripcion_' . $anuncio->getId() . '" value="' . $anuncio->getDescripcion() . '" required>';
            $html .= '<label for="contacto_' . $anuncio->getId() . '">Contacto:</label>';
            $html .= '<input id="contacto_' . $anuncio->getId() . '" type="text" name="contacto_' . $anuncio->getId() . '" value="' . $anuncio->getContacto() . '" required>';
            $html .= '</div>';
        }
        $html .= '<button type="submit" name="update">Actualizar</button>';
        $html .= '</fieldset>';

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        // Procesar la actualización de los anuncios
        foreach ($datos as $key => $value) {
            if (strpos($key, 'titulo_') === 0) {
                $idAnuncio = substr($key, strlen('titulo_'));
                $titulo = $value;
                $descripcion = $datos['descripcion_' . $idAnuncio];
                $contacto = $datos['contacto_' . $idAnuncio];
                
                // Actualizar el anuncio utilizando el método estático de la clase Anuncio
                if (!Anuncio::actualizar($idAnuncio, $titulo, $descripcion, $contacto, $_SESSION['id'])) {
                    return "Error al actualizar el anuncio.";
                }
            }
        }
        
        return true; // Actualización exitosa
    }
}
