<?php
namespace es\ucm\fdi\aw\formularios;

require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once __DIR__.'/../../includes/clases/Anuncio.php'; 
require_once __DIR__.'/../../includes/clases/Contrato.php'; 
require_once __DIR__.'/../../includes/clases/Encargo.php';

require_once 'Formulario.php';

class FormularioPerfil extends Formulario
{
    public function __construct() {
        parent::__construct('formPerfil', ['urlRedireccion' => '']);
    }

    protected function generaCamposFormulario(&$datos) {
        // Verificar si el usuario está logeado
        if (!isset($_SESSION['login']) || !$_SESSION['login']) {
            return "Inicie sesión para poder ver su perfil.";
        }

        // Obtener información del usuario
        $usuario = Usuario::buscaPorId($_SESSION['id']);
        if (!$usuario) {
            return "Usuario no encontrado.";
        }

        $nombreUsuario = $usuario->getNombreUsuario();
        $nombre = $usuario->getNombre();
        $rol = $this->nombreRol($usuario->getRol());

        // Obtener los anuncios del usuario
        $anuncios = Anuncio::getAnunciosByUserId($_SESSION['id']);

        // Obtener los contratos del usuario
        $contratos = [];
        switch ($usuario->getRol()) {
            case Usuario::EMPRESA_ROLE:
                $contratos = Contrato::buscaContratosPorEmpresa($_SESSION['id']);
                break;
            case Usuario::PUEBLO_ROLE:
                $contratos = Contrato::buscaContratosPorPueblo($_SESSION['id']);
                break; 
        }

        // Obtener los encargos del usuario
        $encargos = [];
        switch ($usuario->getRol()) {
            case Usuario::EMPRESA_ROLE:
                $encargos = Encargo::buscaEncargosPorEmpresa($_SESSION['id']);
                break;
            case Usuario::VECINO_ROLE:
                $encargos = Encargo::buscaEncargosPorVecino($_SESSION['id']);
                break; 
        }

        // Mostrar la información del perfil sin campos de entrada
        $html = <<<EOF
        <fieldset>
            <legend>Información del Perfil</legend>
            <div>
                <p><strong>Nombre de usuario:</strong> $nombreUsuario</p>
            </div>
            <div>
                <p><strong>Nombre:</strong> $nombre</p>
            </div>
            <div>
                <p><strong>Rol:</strong> $rol</p>
            </div>
        </fieldset>
        EOF;

        // Mostrar los anuncios del usuario
        if (!empty($anuncios)) {
            $html .= '<fieldset><legend>Anuncios del Usuario</legend>';
            foreach ($anuncios as $anuncio) {
                $html .= '<div>';
                $html .= '<p><strong>Título:</strong> ' . $anuncio->getTitulo() . '</p>';
                $html .= '<p><strong>Descripción:</strong> ' . $anuncio->getDescripcion() . '</p>';
                $html .= '</div>';
            }
            $html .= '</fieldset>';
        }

        // Mostrar los contratos del usuario
        if (!empty($contratos)) {
            $html .= '<fieldset><legend>Contratos del Usuario</legend>';
            foreach ($contratos as $contrato) {
                $html .= '<div>';
                $html .= '<p><strong>Pueblo:</strong> ' . $contrato->getIdPueblo() . '</p>';
                $html .= '<p><strong>Duración:</strong> ' . $contrato->getDuracion() . '</p>';
                $html .= '<p><strong>Terminos:</strong> ' . $contrato->getTerminos() . '</p>';
                $html .= '</div>';
            }
            $html .= '</fieldset>';
        }

        // Mostrar los encargos del usuario
        if (!empty($encargos)) {
            $html .= '<fieldset><legend>Encargos del Usuario</legend>';
            foreach ($encargos as $encargo) {
                $html .= '<div>';
                $html .= '<p><strong>Descripción:</strong> ' . $encargo->getDescripcion() . '</p>';
                $html .= '<p><strong>Fecha:</strong> ' . $encargo->getFecha() . '</p>';
                $html .= '</div>';
            }
            $html .= '</fieldset>';
        }

        // Añadir botones para modificar perfil, anuncios y contratos
        $html .= <<<EOF
        <fieldset>
            <legend>Modificar</legend>
            <div>
                <a href="perfilModificar.php"><button type="button">Modificar Perfil</button></a>
                <a href="anuncioModificar.php"><button type="button">Modificar Anuncios</button></a>
                <a href="contratoModificar.php"><button type="button">Modificar Contratos</button></a>
                <a href="encargoModificar.php"><button type="button">Modificar Encargos</button></a>
            </div>
        </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        // Ya que solo mostramos información, no necesitamos procesar el formulario.
    }

    private function nombreRol($rolNum) {
        switch ($rolNum) {
            case Usuario::ADMIN_ROLE:
                return 'Administrador';
            case Usuario::EMPRESA_ROLE:
                return 'Empresa';
            case Usuario::PUEBLO_ROLE:
                return 'Pueblo';
            case Usuario::VECINO_ROLE:
                return 'Vecino';    
            default:
                return 'Desconocido';
        }
    } 

} 
