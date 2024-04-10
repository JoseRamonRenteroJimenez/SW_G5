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
        if (isset($_SESSION['rol'])) {
            $rol = intval($_SESSION['rol']);
            if ($rol === Usuario::EMPRESA_ROLE) {
                // Si el usuario es empresa, mostrar solo sus anuncios
                $anuncios = Anuncio::buscaAnuncioPorEmpresa($_SESSION['id']);
                $html .= '<h2>Tus Anuncios:</h2>';
            }elseif($rol === Usuario::PUEBLO_ROLE){
                // Si el usuario es empresa, mostrar solo sus contratos
                $anuncios = Anuncio::buscaAnuncioPorPueblo($_SESSION['id']);
                $html .= '<h2>Tus Anuncios:</h2>';
            } elseif ($rol === Usuario::ADMIN_ROLE) {
                // Si el usuario es admin, mostrar todos los contratos
                $anuncios = Anuncio::obtenerPorUsuarioId($_SESSION['id']); // ESTE SESSION NOSE SI ESTA AQUI BIEN 
                $html .= '<h2>Anuncios:</h2>';
            } else {
                // Otros roles no tienen acceso a esta funcionalidad
                $html .= '<p>No tienes permiso para acceder a esta página.</p>';
                return $html;
            }

            // Mostrar contratos
            if (!empty($anuncios)) {
                $html .= '<table>';
                $html .= '<tr><th>ID Anuncio</th><th>Título</th><th>ID Empresa</th><th>Descripcion</th><th>ID Pueblo</th><th>Nombre Pueblo</th><th>Duración (días)</th></tr>';
                foreach ($anuncios as $anuncio) {
                    $idAnuncio = $anuncio->getId();
                    $titulo = $anuncio->getTitulo();
                    $descripcion = $anuncio->getDescripcion(); 
                    $usuarioId = $anuncio->getUsuarioId(); // NOSE SI REALMENTE ASI ESTÁ COGIENDO BIEN EL USUARIO.
                    $html .= "<tr><td>$idAnuncio</td><td>$titulo</td><td>$descripcion</td><td>$usuarioId</td></tr>";
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
