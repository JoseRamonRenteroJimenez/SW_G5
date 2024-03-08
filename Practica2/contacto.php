<?php

// Inclusión de archivos necesarios
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/Formulario.php';

// Definición del título de la página y del contenido principal
$tituloPagina = 'Contacto con Pueblo Innova';
$contenidoPrincipal = obtenerContenidoPrincipal();

// Función para generar el contenido principal
function obtenerContenidoPrincipal() {
    $telefonoContacto = '999999999'; // Número de teléfono para contacto
    $emailContacto = 'contacto@puebloinnova.com'; // Email de contacto

    // Contenido principal con la información de contacto
    $contenido = <<<HTML
  <div class="contacto-pueblo-innova">
    <h2>Conexión entre Empresas y Pueblos</h2>
    <div id="info">
      <h3>Información sobre Pueblo Innova</h3>
      <p>
        Pueblo Innova es una plataforma dedicada a revitalizar pueblos a través de la innovación y el emprendimiento, 
        facilitando el contacto entre empresas que buscan nuevas oportunidades y comunidades rurales acogedoras.
        ¡Contacta con nosotros para más información!
      </p>
    </div>

    <div id="contacto">
      <h4>Teléfono</h4>
      <p>$telefonoContacto</p>
    </div>

    <div id="contacto">
      <h4>Formulario de Contacto</h4>
      <p>
        Faltaria el formulario en caso de tenerlo
      </p>
    </div>

    <div id="contacto">
      <h4>Email</h4>
      <p>$emailContacto</p>
    </div>
  </div>
HTML;

    return $contenido;
}

// Inclusión de la plantilla principal
require __DIR__ . '/includes/vistas/plantillas/plantilla.php';
