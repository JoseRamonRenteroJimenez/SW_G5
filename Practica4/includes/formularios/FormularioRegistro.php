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

class FormularioRegistro extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => 'index.php','enctype'=>'multipart/form-data']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Se reutiliza el nombre de usuario introducido previamente o se deja en blanco
        $nombreUsuario = $datos['nombreUsuario'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'password', 'password2', 'rol', 'nTrabajadores', 'ambito', 'cif', 'comunidad', 'img'], $this->errores, 'span', array('class' => 'error'));

        // Obtener lista de comunidades autónomas desde la base de datos
        $comunidadesAutonomas = Comunidad::getComunidades();

        //Obtener lista de Pueblos
        $listaProcedencias = Pueblo::getPueblos();
        
        // Obtener lista de ámbitos desde la base de datos
        $ambitos = Ambito::getAmbitos();

        // Generar HTML para el formulario
        $html = <<<EOF
    $htmlErroresGlobales
    <fieldset>
        <legend>Datos para el registro</legend>
        <div>
            <label for="nombreUsuario">Nombre de usuario:</label>
            <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" />
            {$erroresCampos['nombreUsuario']}
        </div>
        <div>
            <label for="nombre">Nombre:</label>
            <input id="nombre" type="text" name="nombre"/>
            {$erroresCampos['nombre']}
        </div>
        <div>
            <label for="password">Password:</label>
            <input id="password" type="password" name="password" />
            {$erroresCampos['password']}
        </div>
        <div>
            <label for="password2">Reintroduce el password:</label>
            <input id="password2" type="password" name="password2" />
            {$erroresCampos['password2']}
        </div>
        <div>
            <label for="rol">Rol:</label>
            <select id="rol" name="rol">
              <option value="1">Administrador</option>
              <option value="3">Pueblo</option>
              <option value="2">Empresa</option>
              <option value="4">Vecino</option>
            </select>
            {$erroresCampos['rol']}
        </div>
        <div id="infoAdicionalPueblo">
            <label for="cif">CIF:</label>
            <input id="cif" type="text" name="cif"/>
            {$erroresCampos['cif']}
            <label for="comunidad">Comunidad:</label>
            <select id="comunidad" name="comunidad">
    EOF;

            // Agregar opciones de comunidades autónomas al menú desplegable
            foreach ($comunidadesAutonomas as $comunidad) {
                $html .= "<option value=\"{$comunidad->getId()}\">{$comunidad->getNombre()}</option>";
            }

            $html .= <<<EOF
            </select>
            {$erroresCampos['comunidad']}
        </div>
        <div id="infoAdicionalEmpresa">
            <label for="nTrabajadores">Número de trabajadores:</label>
            <input id="nTrabajadores" type="text" name="nTrabajadores"/>
            {$erroresCampos['nTrabajadores']}
            <label for="ambito">Ámbito:</label>
            <select id="ambito" name="ambito">
                <option value="-">-</option>
        </div>
    EOF;

            // Agregar opciones de ámbitos al menú desplegable
            foreach ($ambitos as $ambito) {
                $html .= "<option value=\"{$ambito->getId()}\">{$ambito->getNombre()}</option>";
            }

            $html .= <<<EOF
            </select>
            <input id="ambito_manual" type="text" name="ambito_manual" placeholder="Ingrese el ámbito">
            {$erroresCampos['ambito']}
        </div>
        <div id="puebloProcedencia">
            <label for="puebloProcedencia">Pueblo de procedencia:</label>
            <select id="procedencia" name="procedencia">
                <option value="-">-</option>
    EOF;
            // Agregar opciones de ámbitos al menú desplegable
            foreach ($listaProcedencias as $procedencia) {
                $html .= "<option value=\"{$procedencia->getId()}\">{$procedencia->getNombre()}</option>";
            }
    
            $html .= <<<EOF
                    </select>
                </div>
                <div>
                    <label for="fotoPerfil">Foto de perfil:</label>
                    <input id="fotoPerfil" type="file" name="fotoPerfil" accept="image/*"/>
                </div>
                <div>
                    <button type="submit" name="registro">Registrar</button>
                </div>
            </fieldset>
    EOF;
    
            return $html;
    EOF;

        return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
    
        // Recuperar datos del formulario
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $password = trim($datos['password'] ?? '');
        $nombre = trim($datos['nombre'] ?? '');
        $rol = intval($datos['rol'] ?? ''); // Asegúrate de que el rol se convierte a entero
        $rutaImagen = "uploads/sample.png"; // Ruta predeterminada si no se carga una imagen
    
        // Validaciones básicas
        if (empty($nombreUsuario)) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario no puede estar vacío';
        }
        if (empty($nombre)) {
            $this->errores['nombre'] = 'El nombre no puede estar vacío';
        }
        if (empty($password)) {
            $this->errores['password'] = 'El password no puede estar vacío';
        }
        if ($rol <= 0 || $rol > 4) {
            $this->errores['rol'] = 'El rol seleccionado no es válido';
        }
    
        // Si hay errores, termina la validación
        if (count($this->errores) > 0) {
            return;
        }
    
        // Manejo de la carga de imágenes
        if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == UPLOAD_ERR_OK) {
            $imagenSubida = $_FILES['fotoPerfil'];
            $rutaImagen = $this->manejaCargaDeImagen($imagenSubida);
        }
    
        // Crear el usuario según el rol
        $usuario = Usuario::crea($nombreUsuario, $password, $nombre, $rutaImagen, $rol);
    
        if ($usuario === 0) {
            $this->errores['general'] = 'El usuario ya existe';
            return;
        }
    
        if ($usuario != null) {
            // Establecer datos comunes en la sesión
            $_SESSION['login'] = true;
            $_SESSION['nombre'] = $usuario->getNombre();
            $_SESSION['id'] = $usuario->getId();
            $_SESSION['rol'] = $usuario->getRol();
    
            switch ($rol) {
                case Usuario::ADMIN_ROLE:
                    $_SESSION['esAdmin'] = true;
                    break;
                case Usuario::EMPRESA_ROLE:
                    // Procesar datos adicionales para empresa
                    $nTrabajadores = trim($datos['nTrabajadores'] ?? '');
                    $ambito = trim($datos['ambito'] ?? '');
                    $ambito_manual = trim($datos['ambito_manual'] ?? '');
    
                    // Crear y registrar la empresa
                    break;
                case Usuario::PUEBLO_ROLE:
                    // Procesar datos adicionales para pueblo
                    $cif = trim($datos['cif'] ?? '');
                    $comunidad = trim($datos['comunidad'] ?? '');
    
                    // Crear y registrar el pueblo
                    break;
                case Usuario::VECINO_ROLE:
                    // Procesar datos adicionales para vecino
                    $procedencia = $datos['procedencia'] ?? '';
    
                    // Crear y registrar el vecino
                    break;
                default:
                    // Manejar caso no válido
                    break;
            }
            header('Location: registroResumen.php'); // Redirigir a una página de resumen
            exit();
        } else {
            $this->errores['general'] = 'Error al crear el usuario';
        }
    }
    
    // Función para manejar la carga de imágenes
    private function manejaCargaDeImagen($imagen)
    {
        $directorioDestino = "uploads/";
        $extPermitidas = ['jpg', 'jpeg', 'png', 'gif']; // Extensiones permitidas
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif']; // Mime types permitidos
        $maxTam = 5 * 1024 * 1024; // Tamaño máximo de 5 MB
    
        $nombreArchivo = basename($imagen['name']);
        $tipoArchivo = $imagen['type'];
        $tamArchivo = $imagen['size'];
        $temporal = $imagen['tmp_name'];
    
        $extArchivo = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    
        // Validación de tipo de archivo
        if (!in_array($tipoArchivo, $tiposPermitidos) || !in_array($extArchivo, $extPermitidas)) {
            $this->errores['fotoPerfil'] = 'Formato de imagen no permitido';
            return null; // Devuelve null en caso de error
        }
    
        // Validación de tamaño de archivo
        if ($tamArchivo > $maxTam) {
            $this->errores['fotoPerfil'] = 'El archivo es demasiado grande';
            return null; // Devuelve null en caso de error
        }
    
        // Validación de contenido real de la imagen
        if (!@getimagesize($temporal)) {
            $this->errores['fotoPerfil'] = 'El archivo no es una imagen válida.';
            return null; // Devuelve null en caso de error
        }
    
        // Sanitización del nombre del archivo
        $nombreUnico = uniqid() . '.' . $extArchivo;
        $rutaDestino = $directorioDestino . $nombreUnico;
    
        // Mover el archivo subido al directorio de destino
        if (move_uploaded_file($temporal, $rutaDestino)) {
            return $rutaDestino; // Retornar la ruta relativa del directorio 'uploads' con el nombre del archivo subido
        } else {
            $this->errores['fotoPerfil'] = 'Error al subir la imagen';
            return null; // Devuelve null en caso de error
        }
    }
}
?>