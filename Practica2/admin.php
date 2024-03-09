<?php
require_once __DIR__.'/includes/config.php'; // Necesario para trabajar con las variables globales 

$tituloPagina = 'Administrador';
$contenidoPrincipal='';
// ESTAS DOS PROXIMAS LINEAS HAY QUE MODIFICARLAS.
if ($app->esAdmin(RUTA_APP.'/includes/src/administradores/memoria/Administrador.php')) { // Necesario modificarlo 
  $contenidoPrincipal=<<<EOS
    <h1>Consola de administración</h1>
    <p>Mas adelante la funcionalidad del administrador será implementada</p>
  EOS;
} else {
  $contenidoPrincipal=<<<EOS
  <h1>Acceso Denegado!</h1>
  <p>No tienes permisos suficientes para administrar la web.</p>
  EOS;
}
// TODO ESTO HAY QUE REVISARLO
$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);

// ES NECESARIO CAMBIAR LAS RUTAS Y ADAPTARLAS A LA NUESTRA, PERO UNA VEZ QUE ESTÉ FUNCIONANDO
// TAMBIEN HAY QUE PASARLE BIEN LA PLANTILLA PARA QUE PILLE UNA VISTA ADECUADA Y SE VEA BIEN  