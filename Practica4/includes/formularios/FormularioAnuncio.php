<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/clases/Usuario.php'; // Ruta correcta hacia Usuario.php
require_once 'Formulario.php'; 

class FormularioAnuncios extends Formulario
{
    public function __construct() {
        parent::__construct('formAnuncio', ['urlRedireccion' => 'anuncioResumen.php', 'enctype' => 'multipart/form-data']);
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
            <label for="fotoAnuncio">Foto del anuncio:</label>
            <input id="fotoAnuncio" type="file" name="fotoAnuncio" accept="image/*">
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
        $contacto = trim($datos['contacto'] ?? '');
        $usuarioId = $_SESSION['id']; // El ID del usuario se obtiene de la sesión.

        // Manejo de la carga de la imagen
    if (isset($_FILES['fotoAnuncio']) && $_FILES['fotoAnuncio']['error'] == UPLOAD_ERR_OK) {
        $rutaImagen = $this->manejaCargaDeImagen($_FILES['fotoAnuncio']);
    } else {
        $rutaImagen = "imagenes/anunciodefault.png"; 
    }

    $idAnuncio = Anuncio::insertar($titulo, $descripcion, $usuarioId, $contacto, $rutaImagen); // Intenta insertar el anuncio en la base de datos con la imagen.
        if ($idAnuncio === false) {
            $this->errores[] = "Error al insertar el anuncio. Verifique los datos e intente nuevamente.";
        } else {
            // Si todo va bien, redirecciona.
            header("Location: {$this->urlRedireccion}");
            exit();
        }
    }

    private function manejaCargaDeImagen($imagen)
    {
        $directorioDestino = "uploads/";
        $extPermitidas = ['jpg', 'jpeg', 'png', 'gif']; // Extensiones permitidas
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif']; // Mime types permitidos
        $maxTam = 5 * 1024 * 1024; // Tamaño máximo de 5 MB
    
        $nombreArchivo = basename($imagen['name']);
        $tipoArchivo = $imagen['type'];
        $tamArchivo = $imagen['size'];
        $temporal = $imagen['tmp_name'];
    
        $extArchivo = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    
        // Validación de tipo de archivo
        if (!in_array($tipoArchivo, $tiposPermitidos) || !in_array($extArchivo, $extPermitidas)) {
            $this->errores['fotoAnuncio'] = 'Formato de imagen no permitido';
            return null; // Devuelve null en caso de error
        }
    
        // Validación de tamaño de archivo
        if ($tamArchivo > $maxTam) {
            $this->errores['fotoAnuncio'] = 'El archivo es demasiado grande';
            return null; // Devuelve null en caso de error
        }
    
        // Validación de contenido real de la imagen
        if (!@getimagesize($temporal)) {
            $this->errores['fotoAnuncio'] = 'El archivo no es una imagen válida.';
            return null; // Devuelve null en caso de error
        }
    
        // Sanitización del nombre del archivo
        $nombreUnico = uniqid() . '.' . $extArchivo;
        $rutaDestino = $directorioDestino . $nombreUnico;
    
        // Mover el archivo subido al directorio de destino
        if (move_uploaded_file($temporal, $rutaDestino)) {
            return $rutaDestino; // Retornar la ruta relativa del directorio 'uploads' con el nombre del archivo subido
        } else {
            $this->errores['fotoAnuncio'] = 'Error al subir la imagen';
            return null; // Devuelve null en caso de error
        }
    }
}