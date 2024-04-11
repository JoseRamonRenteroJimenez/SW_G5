<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Faqs';

$contenidoPrincipal = <<<EOS
<h2>Respondemos a vuestras preguntas más recurrentes... en caso de no estar aquí la tuya, ¡puedes ponerte en contacto con nosotros!</h2>
<h1>¿Quiénes somos?</h1>
<p> Esta página web está implementada por tres estudiantes de la facultad de informática de la Universidad Complutense de Madrid. </p>
<h1>¿Hay errores en la página?</h1>
<p> Es posible que encuentres errores que nosotros no hemos visto, aunque intentamos que no, ¡somos estudiantes! ¡Estamos aprendiendo! </p>
<h1>¿Puedo contactar con vosotros?</h1>
<p> ¡Claro! Al final de la página encontrarás un botón de "Soporte" donde podrás comentarnos lo que necesites. </p>
<h1>¿A quién va dirigida la página?</h1>
<p> La página está orientada tanto a pueblos con poca población, como a empresas que quieren fomentar un nuevo estilo de vida. </p>
<h1>Tengo problemas en el login y/o registro</h1>
<p> No te preocupes! Escribemos y nos ponemos en contacto contigo cuando antes. </p>
<h1>¿Para qué sirve administrar?</h1>
<p> Es una función restringida únicamente a los administradores de la página. </p>
<h1>La página de chat me aparece en mantenimiento</h1>
<p> Así es, estamos trabajando para poder ofrecer un chat en vivo para tener una mejor comunicación entre empresas y pueblos. </p>
<h1>Soy pueblo... pero no puedo usar contratación</h1>
<p> De momento, es una funcionalidad que solo hemos implementado para las empresas. </p>
<h1>¿Para qué sirven los listados?</h1>
<p> El listado, estando logueado, mostrará un resumen de los contratos o anuncios en los que estés implicado. </p>

EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
