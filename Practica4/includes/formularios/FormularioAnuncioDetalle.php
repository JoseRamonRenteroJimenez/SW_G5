<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Anuncio.php';

class FormularioAnuncioDetalle extends Formulario
{
    private $idAnuncio;

    public function __construct($idAnuncio) {
        parent::__construct('formAnuncioDetalle', ['urlRedireccion' => RUTA_APP.'/anuncioListado.php']); // Assuming you want to redirect to the announcement list after processing
        $this->idAnuncio = $idAnuncio;
    }
    
    protected function generaCamposFormulario(&$datos) {
        $anuncio = Anuncio::buscarPorId($this->idAnuncio);
        if (!$anuncio) {
            return "<p>Error: El anuncio no existe.</p>";
        }
        
        $html = '<h2>Detalles del Anuncio</h2>';
        $html .= "<p>ID: {$anuncio->getId()}</p>";
        $html .= "<p>Título: {$anuncio->getTitulo()}</p>";
        $html .= "<p>Descripción: {$anuncio->getDescripcion()}</p>";
        $html .= "<p>Contacto: {$anuncio->getContacto()}</p>";
        $html .= $this->generateImageHtml($anuncio->getAnuncioImg());

        return $html;
    }

    private function generateImageHtml($imageUrl) {
        if (empty($imageUrl)) {
            return "<p>Este anuncio no tiene imagen.</p>";
        }
        return "<p><img src='{$imageUrl}' alt='Imagen del anuncio' style='max-width: 500px;'></p>";
    }

    protected function procesaFormulario(&$datos) {
        // No processing needed as the form is non-interactive
    }
}
?>
