<?php
session_start();
// DATOS A TENER EN CUENTA SOBRE ESTOS DOS COMANDOS

// $_REQUEST["username"] -> Recuperamos el dato que se ha enviado por el formulario 
// strip_tags -> Elimina cualquier HTML de la entrada para evitar ataques de inyección de código
// trim -> Elimina espacios en blanco 
// tmlspecialchars -> Convierte caracteres especiales en HTML
$username = htmlspecialchars(trim(strip_tags($_REQUEST["username"])));
$password = htmlspecialchars(trim(strip_tags($_REQUEST["password"])));

if ($username == "pueblo" && $password == "pueblopass") {
    $_SESSION["login"] = true;
    $_SESSION["nombre"] = "Pueblo";
    }
    else ($username == "empresa" && $password == "empresapass") {
        $_SESSION["login"] = true;
        $_SESSION["nombre"] = "Empresa";
    }
    else if ($username == "admin" && $password == "adminpass") {
    $_SESSION["login"] = true;
    $_SESSION["nombre"] = "Administrador";
    $_SESSION["esAdmin"] = true;
    }
?>

<!DOCTYPE html>
<html>

<body>
<div id="contenedor">

<main id="contenido">

<?php
if (!isset($_SESSION["login"]))
{ //Usuario incorrecto
    echo "<h1>ERROR</h1>";
    echo "<p>El usuario o contraseña no son válidos.</p>";
}

else
{ //Usuario registrado
    echo "<h1>Bienvenido {$_SESSION['nombre']}</h1>";
    echo "<p>Utiliza el menú de la izquierda para navegar.</p>";
}

?>
</main>

</div> <!-- Fin del contenedor -->
</body></html>
