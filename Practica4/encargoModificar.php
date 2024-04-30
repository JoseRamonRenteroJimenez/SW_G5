<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/formularios/FormularioEncargoModificar.php'; // Cambiamos la ruta hacia el formulario de modificación de encargos
require_once __DIR__.'/includes/formularios/FormularioEncargoEliminar.php'; // Cambiamos la ruta hacia el formulario de eliminación de encargos

use es\ucm\fdi\aw\FormularioEncargoModificar; // Cambiamos el uso de la clase FormularioContratoModificar por FormularioEncargoModificar
use es\ucm\fdi\aw\FormularioEncargoEliminar; // Cambiamos el uso de la clase FormularioContratoEliminar por FormularioEncargoEliminar

// Instancia la clase FormularioEncargoModificar
$formModificarEncargos = new FormularioEncargoModificar();
$htmlFormModificarEncargos = $formModificarEncargos->gestiona(); // Obtiene el HTML generado por el formulario de modificación de encargos

// Instancia la clase FormularioEncargoEliminar
$formEliminarEncargos = new FormularioEncargoEliminar();
$htmlFormEliminarEncargos = $formEliminarEncargos->gestiona(); // Obtiene el HTML generado por el formulario de eliminación de encargos

$tituloPagina = 'Modificar Encargo'; // Cambiamos el título de la página a "Modificar Encargo"

// Actualiza el contenido principal para incluir ambos formularios
$contenidoPrincipal = <<<EOS
<h1>Modificar Encargo</h1>
$htmlFormModificarEncargos
<h2>Eliminar Encargo</h2>
$htmlFormEliminarEncargos
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
