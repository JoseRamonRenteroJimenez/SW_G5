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

