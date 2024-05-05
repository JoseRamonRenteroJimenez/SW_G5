<?php

namespace es\ucm\fdi\aw;

require_once 'Formulario.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once __DIR__.'/../../includes/clases/Pueblo.php';
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Comunidad.php';
require_once __DIR__.'/../../includes/clases/Ambito.php';
require_once __DIR__.'/../../includes/clases/Vecino.php';
require_once __DIR__.'/../../includes/clases/Imagen.php';
require_once __DIR__.'/../../includes/formularios/FormularioRegistro.php';

class FormularioRegistroRol
{
    public function muestraSelector()
    {
        // Asegurarse de que se incluye dentro del 'main' o similar en la página que llama a esta función.
        $html = <<<EOF
        <div class="contenedor-registro">
            <h2>Registro de Usuario</h2>
            <fieldset>
                <legend>Selecciona el tipo de registro</legend>
                <form action="" method="POST">
                    <div class="boton-registro">
                        <button type="submit" name="rol" value="admin">Registro Administrador</button>
                    </div>
                    <div class="boton-registro">
                        <button type="submit" name="rol" value="pueblo">Registro Pueblo</button>
                    </div>
                    <div class="boton-registro">
                        <button type="submit" name="rol" value="empresa">Registro Empresa</button>
                    </div>
                    <div class="boton-registro">
                        <button type="submit" name="rol" value="vecino">Registro Vecino</button>
                    </div>
                </form>
            </fieldset>
        </div>
        EOF;
        echo $html;
    }

    public function gestiona() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->redireccionaSegunRol();
        }
        return $this->muestraSelector();
    }

    public function redireccionaSegunRol()
    {
        if (isset($_POST['rol'])) {
            $rol = $_POST['rol'];
            switch ($rol) {
                case 'admin':
                    header('Location: /SW_G5/Practica4/includes/formularios/FormularioRegistroAdmin.php');
                    exit;
                case 'pueblo':
                    header('Location: /SW_G5/Practica4/includes/formularios/FormularioRegistroPueblo.php');
                    exit;
                case 'empresa':
                    header('Location: /SW_G5/Practica4/includes/formularios/FormularioRegistroEmpresa.php');
                    exit;
                case 'vecino':
                    header('Location: /SW_G5/Practica4/includes/formularios/FormularioRegistroVecino.php');
                    exit;
                default:
                    echo "Rol no reconocido";
                    exit;
            }
        }
    }
}

?>