<?php
namespace es\ucm\fdi\aw;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php';  //Usuario debe estar antes que Pueblo y Empresa
require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Comunidad.php'; 
require_once __DIR__.'/../../includes/clases/Ambito.php'; 
require_once __DIR__.'/../../includes/clases/Contrato.php';
require_once __DIR__.'/../../includes/clases/Servicio.php';

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
                $html .= '<table>';
                $html .= '<tr><th>Imagen</th><th>ID Anuncio</th><th>Título</th><th>Descripción</th><th>Contacto</th><th>ID autor</th></tr>';
                foreach ($anuncios as $anuncio) {
                    $idAnuncio = $anuncio->getId();
                    $titulo = $anuncio->getTitulo();
                    $descripcion = $anuncio->getDescripcion();
                    $contacto = $anuncio->getContacto();
                    $usuarioId = $anuncio->getUsuarioId();
                    $imagen = $anuncio->getAnuncioImg();
            
                    $html .= "<tr>";
                    $html .= "<td><img src='$imagen' alt='Imagen del anuncio' style='width:100px; height:auto; border-radius: 5%;'></td>";
                    $html .= "<td>$idAnuncio</td><td>$titulo</td><td>$descripcion</td><td>$contacto</td><td>$usuarioId</td>";
                    $html .= "</tr>";
                }
                $html .= '</table>';
            } else {
                $html .= '<p>No se encontraron anuncios.</p>';
            }
            
        } else {
            $html .= '<p>Debes iniciar sesión para acceder a esta funcionalidad.</p>';
        }
    
        return $html;
    }
    
    
    protected function procesaFormulario(&$datos)
    {
        // Este formulario no procesa ningún dato
        return true;
    }
}
?>
