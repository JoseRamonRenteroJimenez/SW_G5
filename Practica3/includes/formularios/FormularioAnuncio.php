<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/clases/Usuario.php'; // Ruta correcta hacia Usuario.php
require_once __DIR__.'/../../includes/clases/Anuncio.php';
require_once 'Formulario.php'; 

class FormularioAnuncios extends Formulario
{
    public function __construct() {
        parent::__construct('formAnuncio', ['urlRedireccion' => 'anuncioResumen.php']);
    }

    protected function generaCamposFormulario(&$datos) {

        // Verificar si el usuario está logeado
        if (!isset($_SESSION['login'])) {
            return "Inicie sesión para poder publicar y ver anuncios.";
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['titulo', 'descripcion', 'contacto'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Publicar Noticia</legend>
            <div>
                <label for="titulo">Título de la noticia:</label>
                <input type="text" id="titulo" name="titulo" required>
                {$erroresCampos['titulo']}
            </div>
            <div>
                <label for="descripcion">Contenido de la noticia:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
                {$erroresCampos['descripcion']}
            </div>
            <div>
                <label for="contacto">Contacto:</label>
                <input type="text" id="contacto" name="contacto">
                {$erroresCampos['contacto']}
            </div>
            <div>
                <button type="submit" name="submitAnuncio">Publicar</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        // Verificación de sesión y rol
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            $this->errores[] = "Usuario no autenticado.";
            return;
        }

        // Asignación y validación de datos
        $titulo = trim($datos['titulo'] ?? '');
        $descripcion = trim($datos['descripcion'] ?? '');
        $usuarioId = $_SESSION['id']; // El ID del usuario se obtiene de la sesión.

        
        $idAnuncio = Anuncio::insertar($titulo, $descripcion, $usuarioId); // Intenta insertar el anuncio en la base de datos.

        if ($idAnuncio === false) {
            $this->errores[] = "Error al insertar el anuncio. Verifique los datos e intente nuevamente.";
        } else {
            // Si todo va bien, establece un mensaje de éxito y redirecciona.
            $_SESSION['mensaje'] = "Anuncio publicado con éxito. ID del anuncio: $idAnuncio";
            header("Location: {$this->urlRedireccion}");
            exit();
        }
    }

}