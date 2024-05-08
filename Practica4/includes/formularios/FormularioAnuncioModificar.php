<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php';
require_once __DIR__.'/../../includes/clases/Anuncio.php'; 

use es\ucm\fdi\aw\Anuncio;

class FormularioAnuncioModificar extends Formulario
{
    public function __construct() {
        parent::__construct('formModificarAnuncio', [
            'urlRedireccion' => 'anuncioModificadoResumen.php',
            'enctype' => 'multipart/form-data'  // Permitir la carga de archivos
        ]);
    }

    protected function generaCamposFormulario(&$datos) {
        if (!isset($_SESSION['login']) || !$_SESSION['login']) {
            return "Inicie sesión para poder modificar sus anuncios.";
        }

        $anuncios = Anuncio::getAnunciosByUserId($_SESSION['id']);
        $html = '<fieldset><legend>Modificar Anuncios</legend>';
        foreach ($anuncios as $anuncio) {
            $html .= '<div>';
            $html .= '<label for="titulo_' . $anuncio->getId() . '">Título:</label>';
            $html .= '<input id="titulo_' . $anuncio->getId() . '" type="text" name="titulo_' . $anuncio->getId() . '" value="' . $anuncio->getTitulo() . '" required>';
            $html .= '<label for="descripcion_' . $anuncio->getId() . '">Descripción:</label>';
            $html .= '<input id="descripcion_' . $anuncio->getId() . '" type="text" name="descripcion_' . $anuncio->getId() . '" value="' . $anuncio->getDescripcion() . '" required>';
            $html .= '<label for="contacto_' . $anuncio->getId() . '">Contacto:</label>';
            $html .= '<input id="contacto_' . $anuncio->getId() . '" type="text" name="contacto_' . $anuncio->getId() . '" value="' . $anuncio->getContacto() . '" required>';
            $html .= '<label for="imagen_' . $anuncio->getId() . '">Imagen:</label>';
            $html .= '<input type="file" id="imagen_' . $anuncio->getId() . '" name="imagen_' . $anuncio->getId() . '">';
            $html .= '<img src="' . $anuncio->getAnuncioImg() . '" style="width: 100px;">';  // Muestra la imagen actual
            $html .= '</div>';
        }
        $html .= '<button type="submit" name="update">Actualizar</button>';
        $html .= '</fieldset>';
        return $html;
    }

    protected function procesaFormulario(&$datos) {
        foreach ($datos as $key => $value) {
            if (strpos($key, 'titulo_') === 0) {
                $idAnuncio = substr($key, strlen('titulo_'));
                $titulo = $value;
                $descripcion = $datos['descripcion_' . $idAnuncio];
                $contacto = $datos['contacto_' . $idAnuncio];
                $imagen = $this->manejaCargaDeImagen($_FILES['imagen_' . $idAnuncio], $idAnuncio);

                if (!Anuncio::actualizar($idAnuncio, $titulo, $descripcion, $contacto, $_SESSION['id'], $imagen)) {
                    return "Error al actualizar el anuncio.";
                }
            }
        }
        return true; // Actualización exitosa
    }

    private function manejaCargaDeImagen($imagen, $rutaImagenActual = null) {
        if ($rutaImagenActual && file_exists($rutaImagenActual)) {
            unlink($rutaImagenActual);  // Consider adding logic to ensure this only happens if a new image is successfully uploaded
        }
    
        $directorioDestino = "uploads/";
        $nombreArchivo = basename($imagen['name']);
        $rutaDestino = $directorioDestino . $nombreArchivo;
    
        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
            return $rutaDestino;
        } else {
            $this->errores['fotoPerfil'] = 'Error al subir la imagen';
            return null;
        }
    }
}
?>
