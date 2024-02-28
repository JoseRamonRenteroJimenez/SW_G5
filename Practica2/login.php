<?php session_start() ?>

<!DOCTYPE html>
<html>
<body>

<div id="contenedor"> <!-- Inicio del contenedor -->

    <main>
        <article>
            <h1> Iniciar Sesión </h1>
            <form action="procesarLogin.php" method="post"> <!-- Los datos del formulario se van a enviar a ese php, "post" es para pasar datos sensibles -->
            <div class="Nombre de usuario:">
                <label for="username"> Usuario:</label> <!-- Campo usuario para la identificacion del usuario  -->
                <input type="text" id="username" name="username" required> <!-- Required se pone porque esos huecos de completar texto no pueden quedar vacios -->
            </div>
            <div class="Contraseña">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <input type ="submit" value="Enviar">
        <input type="reset" value="Reset">
            </form>
        </article>
    </main>
    
</div> <!-- Fin del contenedor -->

</body>
</html>