<?php
//NOTA IMPORTANTE//ÚNICAMENTE GET. No se debe cambiar el estado del servidor
namespace es\ucm\fdi\aw;
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Aplicacion.php';
require_once __DIR__ . '/../clases/Usuario.php';
require_once __DIR__ . '/../clases/Pueblo.php';
require_once __DIR__ . '/../clases/Comunidad.php';

use es\ucm\fdi\aw\Pueblo;
use es\ucm\fdi\aw\Comunidad;

header('Content-Type: application/json');

if (!isset($_GET['comunidadId']) || empty($_GET['comunidadId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or empty comunidadId parameter']);
    exit;
}

if (isset($_GET['comunidadId'])) {
    $comunidadId = intval($_GET['comunidadId']);
    $pueblos = Pueblo::getPueblosDeComunidad($comunidadId);

    $response = [];
    foreach ($pueblos as $pueblo) {
        $response[] = [
            'id' => $pueblo->getId(),
            'nombre' => $pueblo->getNombre()
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
}
