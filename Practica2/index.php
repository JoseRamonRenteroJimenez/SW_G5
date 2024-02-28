<?php session_start() ?>

<?php require_once __DIR__.'/includes/scripts/config.php';?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css"/>
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
		<title>Pantalla_inicio</title>
	</head>


    <body>

    <div id="contenedor">

    <!-- He planteado dividir la pagina inicial en 3 partes.
    1a cabecera
    2a cuerpo
    3a pie de pagina -->
        
        <?php require_once __DIR__.'/includes/scripts/header.php';?> 

        <section id="body">
            <article>
                <h1>¿Por qué escoger Pueblo Innova?</h1>
            </article>
            <article>
                <h1>Busca pueblos de España</h1>
            </article>
            <article>
                <h1>Descubre noticias</h1>
            </article>
            <article>
                <h1>Nuestros servicios</h1>
            </article> 
         
        </section>

	    <?php require_once __DIR__.'/includes/scripts/footer.php';?>
     </div>
    </body>
</html>