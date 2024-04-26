<?php
namespace es\ucm\fdi\aw\formularios;

require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php';  //Usuario debe estar antes que Pueblo y Empresa
require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Comunidad.php'; 
require_once __DIR__.'/../../includes/clases/Ambito.php'; 
require_once __DIR__.'/../../includes/clases/Contrato.php';
require_once __DIR__.'/../../includes/clases/Servicio.php';

class FormularioContratoListado extends Formulario
{
    public function __construct() {
        parent::__construct('formContratoListado', ['urlRedireccion' => '']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $html = '';

        // Verificar si el usuario está logueado
        if (isset($_SESSION['rol'])) {
            $rol = intval($_SESSION['rol']);
            if ($rol === Usuario::EMPRESA_ROLE) {
                // Si el usuario es empresa, mostrar solo sus contratos
                $contratos = Contrato::buscaContratosPorEmpresa($_SESSION['id']);
                $html .= '<h2>Tus Contratos:</h2>';
            }elseif($rol === Usuario::PUEBLO_ROLE){
                // Si el usuario es empresa, mostrar solo sus contratos
                $contratos = Contrato::buscaContratosPorPueblo($_SESSION['id']);
                $html .= '<h2>Tus Contratos:</h2>';
            } elseif ($rol === Usuario::ADMIN_ROLE) {
                // Si el usuario es admin, mostrar todos los contratos
                $contratos = Contrato::getContratos();
                $html .= '<h2>Contratos:</h2>';
            } else {
                // Otros roles no tienen acceso a esta funcionalidad
                $html .= '<p>No tienes permiso para acceder a esta página.</p>';
                return $html;
            }

            // Mostrar contratos
            if (!empty($contratos)) {
                $html .= '<table>';
                $html .= '<tr><th>ID Contrato</th><th>Términos</th><th>ID Empresa</th><th>Nombre Empresa</th><th>ID Pueblo</th><th>Nombre Pueblo</th><th>Duración (días)</th></tr>';
                foreach ($contratos as $contrato) {
                    $idContrato = $contrato->getId();
                    $terminos = $contrato->getTerminos();
                    $idEmpresa = $contrato->getIdEmpresa();
                    $nombreEmpresa = Empresa::buscaNombreEmpresa($idEmpresa);
                    $idPueblo = $contrato->getIdPueblo();
                    $nombrePueblo = Pueblo::buscaNombrePueblo($idPueblo);
                    $duracion = $contrato->getDuracion();

                    $html .= "<tr><td>$idContrato</td><td>$terminos</td><td>$idEmpresa</td><td>$nombreEmpresa</td><td>$idPueblo</td><td>$nombrePueblo</td><td>$duracion</td></tr>";
                }
                $html .= '</table>';
            } else {
                $html .= '<p>No se encontraron contratos.</p>';
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