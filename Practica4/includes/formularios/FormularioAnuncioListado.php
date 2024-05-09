<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Anuncio.php'; 

class FormularioAnuncioListado extends Formulario
{
    public function __construct() {
        parent::__construct('formAnuncioListado', ['urlRedireccion' => '']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $html = '';
    
        // Verificar si el usuario está logueado
        if (isset($_SESSION['login'])) {
            
            $html .= '<h2>Anuncios:</h2>';
    
            $anuncios = Anuncio::getAllAnuncios();
            // Mostrar anuncios
            if (!empty($anuncios)) {
                $html .= $this->generateAnunciosTableHtml($anuncios);
                return $html;
            } else {
                $html .= '<p>No se encontraron anuncios.</p>';
            }
            
        } else {
            $html .= '<p>Debes iniciar sesión para acceder a esta funcionalidad.</p>';
        }
    
        return $html;
    }

    private function generateAnunciosTableHtml($anuncios) {
        $html = '<table border="1">';
        $html .= '<tr><th>Image</th><th>ID anuncio</th><th>Título</th><th>Descripción</th><th>Contacto</th><th>Detalles</th></tr>';

        foreach ($anuncios as $anuncio) {
            $html .= sprintf(
                '<tr><td><img src="%s" alt="Ad Image" style="width:100px; height:auto;"></td><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td><a href="%s">View Details</a></td></tr>',
                htmlspecialchars($anuncio->getAnuncioImg()), // Assuming there is a getImagen method
                $anuncio->getId(),
                htmlspecialchars($anuncio->getTitulo()),
                htmlspecialchars($anuncio->getDescripcion()),
                htmlspecialchars($anuncio->getContacto()),
                RUTA_APP . "/anuncioDetallado.php?id=" . $anuncio->getId() // Assuming there is a detail page
            );
        }

        $html .= '</table>';
        return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        // Este formulario no procesa ningún dato
        return true;
    }
}
?>
