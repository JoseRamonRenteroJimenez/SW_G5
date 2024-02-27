<?php
function mostrarSaludo() {
    if(isset($_SESSION['login'])) // Verificamos si existe una variable llamada 'login' 
        echo "Bienvenido {$_SESSION['nombre']} <a href=\"logout.php\"> (exit) </a>"; // La variable existe y no es Null y ahora tenemos la opcion de exit
    else 
        echo '<a href = "login.php"> Iniciar sesion </a>';
        echo '<a href = "registro.php"> Registrarse</a>';
}

?>

<?php

    echo '<header>';
        echo '<h1>Mi gran página web</h1>';
        echo '<div class="saludo">'; // Se emplea el uso de div, pero podria utilizarse tambien <p> Da igual usar uno u otro.
        mostrarSaludo();
        echo '</div>';
    echo '</header>';
?>
