<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 
require_once __DIR__.'/../../includes/clases/Usuario.php';  //Usuario debe estar antes que Pueblo y Empresa
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
require_once __DIR__.'/../../includes/clases/Comunidad.php'; 
require_once __DIR__.'/../../includes/clases/Ambito.php'; 
require_once __DIR__.'/../../includes/clases/Contrato.php';
require_once __DIR__.'/../../includes/clases/Servicio.php';

class FormularioContratoListado extends Formulario
{
    public function __construct() {
        parent::__construct('formContratoListado', ['urlRedireccion' => '']);
    }
    
    protected function generaCamposFormulario(&$datos) {
        if (!isset($_SESSION['rol'])) {
            return '<p>Debes iniciar sesión para acceder a esta funcionalidad.</p>';
        }

        $html = '<h2>Listado de Contratos</h2>';

        if ($_SESSION['rol'] == Usuario::PUEBLO_ROLE) {
            $contratos = Contrato::buscaContratosPorPueblo($_SESSION['id']);
        } elseif ($_SESSION['rol'] == Usuario::EMPRESA_ROLE) {
            $contratos = Contrato::buscaContratosPorEmpresa($_SESSION['id']);
        } elseif ($_SESSION['rol'] == Usuario::ADMIN_ROLE) {
            $contratos = Contrato::getContratos();
        } else {
            return $html .= '<p>Acceso no autorizado.</p>';
        }

        if (empty($contratos)) {
            return $html .= '<p>No se encontraron contratos.</p>';
        }

        $estados = [
            Contrato::ACTIVO_ESTADO => 'Activos',
            Contrato::FINALIZADO_ESTADO => 'Finalizados',
            Contrato::CANCELADO_ESTADO => 'Cancelados',
            Contrato::ESPERA_ESTADO => 'En Espera'
        ];

        foreach ($estados as $estado => $nombreEstado) {
            $html .= "<h3>$nombreEstado</h3>";
            $filteredContracts = array_filter($contratos, function ($contrato) use ($estado) {
                return $contrato->getEstado() == $estado;
            });
    
            if (empty($filteredContracts)) {
                $html .= "<p>No hay contratos $nombreEstado.</p>";
                continue;
            }
    
            $html .= '<table border="1">';
            $html .= '<tr><th>ID</th><th>Empresa</th><th>Pueblo</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Términos</th><th>Estado</th><th>Detalle</th></tr>';
            foreach ($filteredContracts as $contrato) {
                $link = $_SESSION['rol'] == Usuario::PUEBLO_ROLE && $contrato->getEstado() == Contrato::ESPERA_ESTADO ?
                        "<a href='../scriptsApoyo/contratoDetalle.php?id={$contrato->getId()}'>Ver/Accionar</a>" :
                        "<a href='../scriptsApoyo/contratoDetalle.php?id={$contrato->getId()}'>Ver</a>";
    
                $html .= sprintf(
                    '<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                    $contrato->getId(),
                    Empresa::buscaNombreEmpresa($contrato->getIdEmpresa()),
                    Pueblo::buscaNombrePueblo($contrato->getIdPueblo()),
                    $contrato->getFechaInicial(),
                    $contrato->getFechaFinal(),
                    htmlspecialchars($contrato->getTerminos()),
                    $nombreEstado,
                    $link
                );
            }
            $html .= '</table>';
        }
    
        return $html;
    }
    
    protected function procesaFormulario(&$datos) {
        if (isset($datos['aceptar'])) {
            Contrato::confirmarContrato($datos['aceptar'], true);
        } elseif (isset($datos['denegar'])) {
            Contrato::confirmarContrato($datos['denegar'], false);
        }
        return true; // Redirect to the list again to see the updates
    }
}
?>
