<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css"> <!-- Asegúrate de que la ruta al archivo CSS sea correcta -->
</head>
<body>

    <header>
        <!-- La cabecera puede tener otros elementos como enlaces o botones si es necesario -->
        <nav>
            <p>¿ No tienes cuenta ? <a href="registro.php">Crear cuenta</a></p>
        </nav>
    </header>

    <div class="contenedor"> <!-- Inicio del contenedor -->

        <aside>
            <!-- Aquí irá el logotipo del pueblo en el futuro -->
            <p><img src="logo.png"  width="300" height="200"></p>
            <div class="color-seccion-blanco">
                <!-- TODO: Insertar logotipo aquí -->
            </div>
            <div class="color-seccion-celeste"></div> <!-- Colores basados en tu CSS -->
            <div class="color-seccion-verde"></div>
        </aside>

        <main>
            <!-- Contenido principal con el formulario de inicio de sesión -->
            <article>
                <h1>Acceso al sistema</h1>
                <form action="ProcesarLogin.php" method="post">
                    <div class="campo">
                        <label for="username">Usuario:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="campo">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="campo">
                        <label for="factor">Segundo factor autenticación:</label>
                        <input type="text" id="factor" name="factor" required>
                    </div>
                    <div class="campo">
                        <input type="submit" value="Iniciar sesión">
                    </div>
                    <!-- Añade aquí más elementos si son necesarios, como reCAPTCHA -->
                </form>
                <p class="recaptcha-notice">Protected by reCAPTCHA and Google <a href="#">Privacy Policy & Terms of Service</a> </p>
            </article>
        </main>

    </div> <!-- Fin del contenedor -->

</body>
</html>
