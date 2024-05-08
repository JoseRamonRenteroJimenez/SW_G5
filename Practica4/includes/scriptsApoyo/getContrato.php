<?php
namespace es\ucm\fdi\aw;

require_once  __DIR__ .'includes/config.php';
require_once  __DIR__ .'includes/clases/Contrato.php';  // Make sure paths are correct

$contratoId = $_GET['id'] ?? null;

// Ensure that an ID is passed
if (!$contratoId) {
    die('Contrato no especificado.');
}

$contrato = Contrato::buscaContratoPorId($contratoId);

if (!$contrato) {
    die('Contrato no encontrado.');
}

// Here, you would typically include some HTML to show the contract details
// and depending on the role, show buttons or links to accept, deny, or modify.
echo "Detalles del Contrato:";
echo "<pre>" . print_r($contrato, true) . "</pre>";

// Example of a link back to the list or further actions
echo "<a href='contratoListado.php'>Volver al listado</a>";
?>
