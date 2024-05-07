<?php
function mostrarSaludo() {
    $rutaApp = RUTA_APP;
    $html = '';
    if (isset($_SESSION["login"]) && ($_SESSION["login"] === true)) {
        return "Bienvenido, {$_SESSION['nombre']} <a href='{$rutaApp}/logout.php'>(salir)</a>";
    } else {
        return "Usuario desconocido. <a href='{$rutaApp}/login.php'>Login</a> <a href='{$rutaApp}/registro.php'>Registro</a>";
    }
    return $html;
}

?>
<header>
    <div class="logo">
    <img src="<?= RUTA_APP?>/imagenes/logo.png" alt="Logotipo de PuebloInnova" style="width:100px;height:100px;" />
    </div>
    <div class="saludo">
        <?= mostrarSaludo() ?>
    </div>
</header>