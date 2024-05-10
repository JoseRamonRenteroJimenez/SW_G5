<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Portada';

$introSection = <<<EOS
<section class="intro-section">
  <div class="container margin-auto flex paddx-5 paddy-24 items-center">
    <div class="text-content flex flex-col items-center text-center">
      <h1 class="title-font mb-0">Explora las Oportunidades en tu Pueblo.</h1>
      <p class="mb-8">Descubre cómo PuebloInnova puede transformar tu experiencia como Empresa, Vecino o Pueblo.</p>
    </div>
  </div>
</section>
EOS;

$contenidoPrincipal = <<<EOS
<h1>Página principal</h1>
<p>Bienvenidos a la web de PuebloInnova... A continuación te explicamos cómo puedes hacer que tu experiencia como Empresa/Vecino/Pueblo mejore mucho gracias a nosotros!</p>
<strong><p>Pueblos:</p></strong>
<ul>
  <li>Podrás aceptar a las empresas que quieran trabajar en tu pueblo se instalen allí, todo ello conllevará mucha más riqueza y vecinos nuevos.</li>
  <li>Tienes la opción de poder publicar anuncios y que todos los usuarios registrados en la web lo vean y de esta forma os puedan conocer.</li>
  <li>Seréis un pueblo mucho más atractivo para turistas ya que vuestros recursos aumentarán considerablemente con la llegada de nuevos vecinos e inversores.</li>
</ul>
<strong><p>Empresas:</p></strong>
<ul>
  <li>¿Cansado de trabajar en una gran ciudad? Estos pueblos estan deseando recibir gente nueva y por supuesto negocios que les mejoren su calidad de vida.</li>
  <li>Puedes publicar anuncios en el tablón. Seguro que mucha mas gente os quiere conocer y de esta forma mejorar vuestro negocio.</li>
  <li>Podeis establecer contratos en un entorno seguro y gratuito dentro de la web. Suponiendo mejoras para el pueblo y para vosotros.</li>
  <li>Conocereis gente nueva y seguro que trabajais en un entorno mucho mas idílico y tranquilo sin los agobios que supone una ciudad.</li>
</ul>
<strong><p>Vecino:</p></strong>
<ul>
  <li>Tienes la opción de poder hacer encargos con las empresas que se hayan inscrito a tu pueblo.</li>
  <li>¿Eres un excelente cocinero que hace un "ajo arriero manchego buenísimo"? Seguro que encuentras una empresa que quiera comercializarlo.</li>
  <li>¿Tu pueblo no pertenece a nuestra web? Adelante! Haz que los dirigentes conozcan la web y puedas disfrutar de los beneficios que conlleva estar registrado.</li>
  <li>Conoceras gente nueva que se instale en el pueblo y de esta forma fomentaras que tu pueblo crezca y sea un lugar mucho mejor donde vivir.</li>
</ul>
<strong><p>¿Has llegado hasta aqui ?</p></strong>
<ul>
  <li>Pincha en la parte superior derecha en "Registro" y empieza a disfrutar de esta maravillosa experiencia</li>
</ul>
EOS;

$contenidoPrincipal = $introSection . $contenidoPrincipal;

$cssIntro = 'intro.css';

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
?>
