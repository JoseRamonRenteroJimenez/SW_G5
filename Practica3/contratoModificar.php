<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/formularios/FormularioContratoModificar.php';
require_once __DIR__.'/includes/formularios/FormularioContratoEliminar.php';

use es\ucm\fdi\aw\FormularioContratoModificar;
use es\ucm\fdi\aw\FormularioContratoEliminar;

// Instancia la clase FormularioContratoModificar
$formModificarContratos = new FormularioContratoModificar();
$htmlFormModificarContratos = $formModificarContratos->gestiona(); // Obtiene el HTML generado por el formulario de modificación de contratos

// Instancia la clase FormularioContratoEliminar
$formEliminarContratos = new FormularioContratoEliminar();
$htmlFormEliminarContratos = $formEliminarContratos->gestiona(); // Obtiene el HTML generado por el formulario de eliminación de contratos

$tituloPagina = 'Modificar Contrato'; // Título de la página

// Actualiza el contenido principal para incluir ambos formularios
$contenidoPrincipal = <<<EOS
<h1>Modificar Contrato</h1>
$htmlFormModificarContratos
<h2>Eliminar Contrato</h2>
$htmlFormEliminarContratos
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
