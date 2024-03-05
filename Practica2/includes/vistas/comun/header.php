<?php
function mostrarSaludo() {
    if(isset($_SESSION['login'])) // Verificamos si existe una variable llamada 'login' 
        echo "Bienvenido {$_SESSION['nombre']} <a href=\"logout.php\"> (exit) </a>"; // La variable existe y no es Null y ahora tenemos la opcion de exit
    else 
        echo '<a href = "login.php"> Iniciar sesion </a>';
        echo '<a href = "registro.php"> Registrarse</a>';
}

echo '<header>';
    echo '<h1>Pueblo Innova</h1>';
    echo '<input type ="submit" value="Enviar">';
echo '</header>';

// SOY CONSCIENTE DE QUE ESTO AHORA MISMO NO FUNCIONA
/*
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pueblo Innova</title>
    <!-- Aquí van los enlaces a CSS y los metadatos necesarios -->
</head>
<body>
    <header>
        <!-- Aquí va el contenido de la cabecera (logo, menú de navegación, botones de inicio de sesión) -->
        <!-- Por ejemplo: -->
        <nav>
            <ul>
                <li><a href="descubre-pueblos.php">Descubre pueblos</a></li>
                <li><a href="explora.php">Explora</a></li>
                <li><a href="acerca-de.php">Acerca de</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
            <div class="botones">
                <button onclick="location.href='login.php'">Iniciar sesión</button>
                <button onclick="location.href='registrarse.php'">Registrarse</button>
            </div>
        </nav>
    </header>
</html>*/