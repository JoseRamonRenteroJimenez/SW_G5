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

class FormularioRegistroRol extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => 'index.php']);
    }

    protected function generaCamposFormulario(&$datos)
    {
        // Load communities and ambits from the database
        $comunidadesAutonomas = Comunidad::getComunidades();
        $ambitos = Ambito::getAmbitos();

        $comunidadOptions = $this->generateSelectOptions($comunidadesAutonomas);
        $ambitoOptions = $this->generateSelectOptions($ambitos, true);  // Include "Otro" option

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
            </div>
            <div>
                <label for="nombre">Nombre completo:</label>
                <input id="nombre" type="text" name="nombre" value="$nombre" required />
            </div>
            <div>
                <label for="password">Password:</label>
                <input id="password" type="password" name="password" value="$password" required />
            </div>
            <div>
                <label for="password2">Confirmar Password:</label>
                <input id="password2" type="password" name="password2" value="$password2" required />
            </div>
            <div id="additionalFields">
                <!-- Additional fields will be loaded here based on the selected role -->
            </div>
            <div>
                <button type="submit">Registrar</button>
            </div>
        </fieldset>
        EOF;

        return $html;
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
                    fieldsContainer.innerHTML += `
                        <div>
                            <label for="cif">CIF:</label>
                            <input id="cif" type="text" name="cif" required />
                        </div>
                        <div>
                            <label for="comunidad">Comunidad:</label>
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
                    fieldsContainer.innerHTML += `
                    <div>
                        <label for="puebloSelector">Pueblo:</label>
                        <select id="puebloSelector" name="pueblo">
                            <!-- Pueblos will be loaded here -->
                        </select>
                    </div>
                    `;
                    break;
            }
        }

        function updatePueblos() {
            var comunidadId = document.getElementById('comunidadSelector').value;
            var puebloSelect = document.getElementById('puebloSelector');
            puebloSelect.innerHTML = ''; // Clear previous options
            fetch('getPueblos.php?comunidadId=' + comunidadId)
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
        JS;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        // Validations and user creation logic
        // Check fields are filled and passwords match, handle file upload, etc.
        // Store user data, including handling special fields based on user role
    }
}

?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateFormFields() {
        var role = document.getElementById('roleSelector').value;
        var fieldsContainer = document.getElementById('additionalFields');
        fieldsContainer.innerHTML = '';
        // Switch cases as previously defined
    }
    
    function updatePueblos() {
        var comunidadId = document.getElementById('comunidadSelector').value;
        var puebloSelect = document.getElementById('puebloSelector');
        puebloSelect.innerHTML = ''; // Clear previous options
        fetch('getPueblos.php?comunidadId=' + comunidadId)
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
