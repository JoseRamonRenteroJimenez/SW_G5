<?php

namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php';
require_once __DIR__.'/../../includes/clases/Usuario.php';
require_once __DIR__.'/../../includes/clases/Pueblo.php';
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Comunidad.php';
require_once __DIR__.'/../../includes/clases/Ambito.php';
require_once __DIR__.'/../../includes/clases/Vecino.php';
require_once __DIR__.'/../../includes/clases/Imagen.php';

class FormularioRegistroRol extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistro', [
            'urlRedireccion' => 'registroResumen.php',
            'enctype' => 'multipart/form-data'
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        // Load communities and ambits from the database
        $comunidades = Comunidad::getComunidades();
        $ambitos = Ambito::getAmbitos();

        $comunidadOptions = $this->generateSelectOptions($comunidades);
        $ambitoOptions = $this->generateSelectOptions($ambitos, true);  // Include "Otro" option

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        
        $erroresCampos = $this->generaErroresCampos(
            ['nombreUsuario', 'nombre', 'password', 'password2', 'rol', 'nTrabajadores', 'ambito', 'cif', 'comunidad', 'img'],
            $this->errores,
            'span',
            ['class' => 'error']
        );
        
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $nombre = $datos['nombre'] ?? '';
        $password = $datos['password'] ?? '';
        $password2 = $datos['password2'] ?? '';

        $script = $this->generaScript($comunidadOptions, $ambitoOptions);

        $html = <<<EOF
        <script>$script</script>
        <fieldset>
            <legend>Registro de Usuario</legend>
            <label for="roleSelector">Tipo de Usuario:</label>
            <select id="roleSelector" name="rol" onchange="updateFormFields();">
                <option value="">Seleccione tipo</option>
                <option value="admin">Administrador</option>
                <option value="pueblo">Pueblo</option>
                <option value="empresa">Empresa</option>
                <option value="vecino">Vecino</option>
            </select>
            <div>
                <label for="nombreUsuario">Nombre de usuario:</label>
                <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" required />
                {$erroresCampos['nombreUsuario']}
            </div>
            <div>
                <label for="nombre">Nombre completo:</label>
                <input id="nombre" type="text" name="nombre" value="$nombre" required />
                {$erroresCampos['nombre']}
            </div>
            <div>
                <label for="password">Password:</label>
                <input id="password" type="password" name="password" value="$password" required />
                {$erroresCampos['password']}
            </div>
            <div>
                <label for="password2">Confirmar Password:</label>
                <input id="password2" type="password" name="password2" value="$password2" required />
                {$erroresCampos['password2']}
            </div>
            <div id="additionalFields">
                <!-- Additional fields will be loaded here based on the selected role -->
            </div>
            <div>
                <label for="fotoPerfil">Foto de perfil:</label>
                <input id="fotoPerfil" type="file" name="fotoPerfil" accept="image/*"/>
                {$erroresCampos['img']}
            </div>
            <div>
                <button type="submit">Registrar</button>
            </div>
        </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        // Retrieve data from the form
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombre = trim($datos['nombre'] ?? '');
        $password = trim($datos['password'] ?? '');
        $password2 = trim($datos['password2'] ?? '');
        $rolString = $datos['rol'] ?? '';
        $nTrabajadores = trim($datos['nTrabajadores'] ?? '');
        $ambitoId = trim($datos['ambito'] ?? '');
        $ambito_manual = trim($datos['ambito_manual'] ?? '');
        $cif = trim($datos['cif'] ?? '');
        $comunidadId = $datos['comunidad'] ?? '';
        $procedencia = $datos['puebloVecino'] ?? '';
        $rutaImagen = "uploads/default.png"; // Default image path

        // Convert role string to number
        $rol = $this->convertRoleToNumber($rolString);

        // Basic validations
        if (empty($nombreUsuario)) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario no puede estar vacío';
        }
        if (empty($nombre)) {
            $this->errores['nombre'] = 'El nombre no puede estar vacío';
        }
        if (empty($password) || ($password != $password2)) {
            $this->errores['password'] = 'La contraseña no puede estar vacía y debe coincidir';
        }
        if (!isset($datos['rol']) || $rol <= 0) {
            $this->errores['rol'] = 'El rol seleccionado no es válido';
        }

        // Image upload handling
        if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
            $imagenSubida = $_FILES['fotoPerfil'];
            $rutaImagen = $this->manejaCargaDeImagen($imagenSubida);
        } elseif ($_FILES['fotoPerfil']['error'] !== UPLOAD_ERR_NO_FILE) {
            $this->errores['fotoPerfil'] = 'Error al subir archivo';
        }

        if (!empty($this->errores)) {
            return $this->errores;
        }

        // Create user based on role
        $usuario = Usuario::crea($nombreUsuario, $password, $nombre, $rutaImagen, $rol);

        if (!$usuario) {
            $this->errores['general'] = 'Error al crear el usuario o el usuario ya existe';
            return;
        }

        // Handle additional fields based on role
        switch ($rol) {
            case Usuario::ADMIN_ROLE:
                $_SESSION['esAdmin'] = true;
                break;
            case Usuario::EMPRESA_ROLE:

                if (!empty($ambito_manual)) {
                    $ambitoId = Ambito::guardarAmbitoManualmente($ambito_manual);
                    if (!$ambitoId) {
                        $this->errores['ambito'] = "Error al guardar el nuevo ámbito.";
                        return $this->errores;
                    }
                }

                Empresa::registrar(new Empresa($usuario->getId(), $nTrabajadores, $ambitoId));
                break;
            case Usuario::PUEBLO_ROLE:
                Pueblo::registrar(new Pueblo($usuario->getId(), $cif, $comunidadId));
                break;
            case Usuario::VECINO_ROLE:
                Vecino::registrar(new Vecino($usuario->getId(), $procedencia));
                break;
        }

        // Redirect or handle session setting
        $_SESSION['login'] = true;
        $_SESSION['nombre'] = $usuario->getNombre();
        $_SESSION['id'] = $usuario->getId();
        $_SESSION['rol'] = $usuario->getRol();
        header('Location: ' . $this->urlRedireccion);
        exit();
    }

    private function convertRoleToNumber($roleString) {
        $roleMap = [
            'admin' => Usuario::ADMIN_ROLE,
            'empresa' => Usuario::EMPRESA_ROLE,
            'pueblo' => Usuario::PUEBLO_ROLE,
            'vecino' => Usuario::VECINO_ROLE
        ];
        return $roleMap[strtolower($roleString)] ?? 0; // Default to 0 if role is invalid
    }

    protected function generateSelectOptions($items, $includeOther = false)
    {
        $options = '';
        foreach ($items as $item) {
            $options .= "<option value=\"{$item->getId()}\">{$item->getNombre()}</option>";
        }
        if ($includeOther) {
            $options .= "<option value=\"other\">Otro - Especificar abajo</option>";
        }
        return $options;
    }

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

    protected function generaScript($comunidadOptions, $ambitoOptions) {
        return <<<JS
        function updateFormFields() {
            var role = document.getElementById('roleSelector').value;
            var fieldsContainer = document.getElementById('additionalFields');
            fieldsContainer.innerHTML = '';

            switch(role) {
                case 'admin':
                    break;
                case 'pueblo':
                    fieldsContainer.innerHTML = `
                        <div>
                            <label for="cif">CIF:</label>
                            <input id="cif" type="text" name="cif" required />
                        </div>
                        <div>
                            <label for="comunidad">Comunidades:</label>
                            <select id="comunidad" name="comunidad">$comunidadOptions</select>
                        </div>
                    `;
                    break;
                case 'empresa':
                    fieldsContainer.innerHTML += `
                        <div>
                            <label for="nTrabajadores">Número de trabajadores:</label>
                            <input id="nTrabajadores" type="text" name="nTrabajadores" required />
                        </div>
                        <div>
                            <label for="ambito">Ámbito:</label>
                            <select id="ambito" name="ambito">$ambitoOptions</select>
                        </div>
                        <div>
                            <label for="ambito_manual">Especificar otro Ámbito:</label>
                            <input type="text" id="ambito_manual" name="ambito_manual" placeholder="Ingrese su ámbito">
                        </div>
                    `;
                    break;
                case 'vecino':
                    fieldsContainer.innerHTML = `
                        <div>
                            <label for="comunidadVecino">Comunidad (Vecino):</label>
                            <select id="comunidadVecino" name="comunidadVecino" onchange="updatePueblos('comunidadVecino', 'puebloVecinoSelector');">
                                {$comunidadOptions}
                            </select>
                        </div>
                        <div>
                            <label for="puebloVecinoSelector">Pueblo de Procedencia:</label>
                            <select id="puebloVecinoSelector" name="puebloVecino">
                                <!-- Pueblos will be loaded here -->
                            </select>
                        </div>
                    `;
                    break;
            }
        }

        function updatePueblos(comunidadIdSelector, puebloSelectorId) {
            var comunidadId = document.getElementById(comunidadIdSelector).value;
            var puebloSelect = document.getElementById(puebloSelectorId);
            puebloSelect.innerHTML = ''; // Clear previous options
            fetch('includes/scriptsApoyo/getPueblos.php?comunidadId=' + comunidadId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                console.log("Respuesta: ", response);
                return response.json();
            })
            .then(data => {
                if (!data.length) {
                    puebloSelect.innerHTML = '<option>No hay pueblos disponibles</option>';
                    return;
                }
                data.forEach(function(pueblo) {
                    var option = document.createElement('option');
                    option.value = pueblo.id;
                    option.textContent = pueblo.nombre;
                    puebloSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading the pueblos:', error));
        }
        JS;
    }
}

?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateFormFields() {
        var role = document.getElementById('roleSelector').value;
        var fieldsContainer = document.getElementById('additionalFields');
        fieldsContainer.innerHTML = '';
    }
    
    function updatePueblos() {
        var comunidadId = document.getElementById('comunidadSelector').value;
        var puebloSelect = document.getElementById('puebloSelector');
        puebloSelect.innerHTML = ''; // Clear previous options
        fetch('includes/scriptsApoyo/getPueblos.php?comunidadId=' + comunidadId)
            .then(response => response.json())
            .then(data => {
                if (!data.length) {
                    puebloSelect.innerHTML = '<option>No hay pueblos disponibles</option>';
                    return;
                }
                data.forEach(function(pueblo) {
                    var option = new Option(pueblo.nombre, pueblo.id);
                    puebloSelect.add(option);
                });
            })
            .catch(error => console.error('Error loading the pueblos:', error));
    }
});
</script>
