<?php
namespace es\ucm\fdi\aw;
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/clases/Contrato.php'; // Posiblemente no haga falta pero por si acaso
require_once __DIR__.'/includes/formularios/FormularioContratoListado.php'; // Ruta correcta hacia formulariocontrato.php
require_once __DIR__.'/includes/clases/Usuario.php';

use es\ucm\fdi\aw\FormularioContratoListado;

$form = new FormularioContratoListado();
$htmlFormContrato = $form->gestiona();

if(isset($_SESSION['rol']) && $_SESSION['rol'] === Usuario::ADMIN_ROLE){
    $tituloPagina = 'Contratos';
} else {
    $tituloPagina = 'Tus contratos';
}

$contenidoPrincipal = <<<EOS
<h1>Listado de contratos</h1>
$htmlFormContrato
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
