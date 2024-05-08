<?php
//NOTA IMPORTANTE//ÚNICAMENTE GET. No se debe cambiar el estado del servidor
namespace es\ucm\fdi\aw;
require_once __DIR__ . '/../config.php'; // Moves up to 'includes' and then accesses 'config.php'
require_once __DIR__ . '/../Aplicacion.php'; // Same level as 'config.php'
require_once __DIR__ . '/../clases/Usuario.php'; // Moves into 'clases' directory within 'includes'
require_once __DIR__ . '/../clases/Pueblo.php'; // Moves into 'clases' directory within 'includes'
require_once __DIR__ . '/../clases/Comunidad.php'; // Same as above for 'Comunidad.php'

use es\ucm\fdi\aw\Pueblo;
use es\ucm\fdi\aw\Comunidad;

header('Content-Type: application/json');

if (!isset($_GET['comunidadId']) || empty($_GET['comunidadId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or empty comunidadId parameter']);
    exit;
}

// Make sure this script only runs on a proper GET request with expected parameters
if (isset($_GET['comunidadId'])) {
    $comunidadId = intval($_GET['comunidadId']);
    $pueblos = Pueblo::getPueblosDeComunidad($comunidadId);

    $response = [];
    foreach ($pueblos as $pueblo) {
        $response[] = [
            'id' => $pueblo->getId(),
            'nombre' => $pueblo->getNombre() // Assuming there is a method to get the name in Pueblo class
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Respond with an error if the necessary GET parameters are not set
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
}
