<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 
class FormularioAnuncios extends Formulario
{
    public function __construct() {
        parent::__construct('formAnuncio', ['urlRedireccion' => '../index.php']);
    }

    protected function generaCamposFormulario(&$datos) {
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Publicar Noticia</legend>
            <div>
                <label for="titulo">Título de la noticia:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div>
                <label for="descripcion">Contenido de la noticia:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
            </div>
            <div>
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria">
                    <option value="pueblo">Pueblo</option>
                    <option value="empresa">Empresa</option>
                </select>
            </div>
            <div>
                <label for="contacto">Contacto:</label>
                <input type="text" id="contacto" name="contacto">
            </div>
            <div>
                <button type="submit" name="submitAnuncio">Publicar</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];
        $titulo = trim($datos['titulo'] ?? '');
        $descripcion = trim($datos['descripcion'] ?? '');
        $categoria = trim($datos['categoria'] ?? '');
        $contacto = trim($datos['contacto'] ?? '');

      // Validaciones
    if (empty($titulo)) {
        $this->errores['titulo'] = "El título no puede estar vacío.";
    } elseif (strlen($titulo) < 5) {
        $this->errores['titulo'] = "El título debe tener al menos 5 caracteres.";
    }

    if (empty($descripcion)) {
        $this->errores['descripcion'] = "La descripción no puede estar vacía.";
    } elseif (strlen($descripcion) < 10) {
        $this->errores['descripcion'] = "La descripción debe tener al menos 10 caracteres.";
    }
        
        if (count($this->errores) === 0) {
            $app = Aplicacion::getInstance();
            $conn = $app->getConexionBd();
            $query = "INSERT INTO anuncios (titulo, descripcion, categoria, contacto) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                $this->errores[] = "Error de preparación de la inserción en la base de datos.";
                return;
            }
            $stmt->bind_param("ssss", $titulo, $descripcion, $categoria, $contacto);
            if (!$stmt->execute()) {
                $this->errores[] = "Error al insertar los datos en la base de datos: " . $stmt->error;
            }
            $stmt->close();

            if (count($this->errores) === 0) {
                header("Location: {$this->urlRedireccion}");
                exit();
            }
        }
    }
}

