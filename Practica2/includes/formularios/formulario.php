<?php

namespace SW_G5\includes\formularios;

/**
 * Clase base abstracta para la gestión de formularios.
 * Define la estructura y lógica base para crear, procesar y validar formularios.
 */
abstract class Formulario
{
    protected $formId;
    protected $method;
    protected $action;
    protected $classAtt;
    protected $enctype;
    protected $urlRedireccion;
    protected $errores = [];

/**
     * Constructor de la clase. Configura las opciones iniciales del formulario.
     *
     * @param string $formId Identificador único del formulario.
     * @param array $opciones Configuraciones opcionales como método, acción, clase, etc.
     */

    public function __construct(
        string $formId,
        array $opciones = []
    ) {
        // Asignaciones iniciales basadas en parámetros y opciones predeterminadas. 
        $this->formId = $formId;

        // Fusión de opciones por defecto con las proporcionadas.
        $opciones = array_merge([
            'action' => htmlspecialchars($_SERVER['REQUEST_URI']),
            'method' => 'POST',
            'class' => null,
            'enctype' => null,
            'urlRedireccion' => null
        ], $opciones);

        $this->action = $opciones['action'];
        $this->method = $opciones['method'];
        $this->classAtt = $opciones['class'];
        $this->enctype = $opciones['enctype'];
        $this->urlRedireccion = $opciones['urlRedireccion'];
    }

/**
     * Orquesta el manejo del formulario, incluyendo su presentación y procesamiento.
     *
     * @return string HTML generado para el formulario.
     */

    public function gestiona(): string
    {
        $datos = $this->method === 'GET' ? $_GET : $_POST;

        if (!$this->formularioEnviado($datos)) {
            return $this->generaFormulario($datos);
        }

        $resultadoProcesamiento = $this->procesaFormulario($datos);

        if (is_array($resultadoProcesamiento)) {
            $this->errores = $resultadoProcesamiento;
            return $this->generaFormulario($datos);
        }

        if ($this->urlRedireccion) {
            header("Location: {$this->urlRedireccion}");
            exit();
        }

        return '';
    }

/**
     * Verifica si el formulario ha sido enviado, basado en la presencia del 'formId'.
     *
     * @param array $datos Datos enviados por el usuario.
     * @return bool Verdadero si el formulario ha sido enviado.
     */

    protected function formularioEnviado(array &$datos): bool
    {
        return isset($datos['formId']) && $datos['formId'] == $this->formId;
    }

/**
     * Genera el HTML del formulario, incluyendo campos y configuración especificada.
     *
     * @param array $datos Datos previamente enviados para rellenar el formulario.
     * @return string HTML del formulario.
     */

    protected function generaFormulario(array &$datos = []): string
    {
        $camposFormulario = $this->generaCamposFormulario($datos);
        $classAtt = $this->classAtt ? "class=\"{$this->classAtt}\"" : '';
        $enctypeAtt = $this->enctype ? "enctype=\"{$this->enctype}\"" : '';

        return <<<HTML
        <form method="{$this->method}" action="{$this->action}" id="{$this->formId}" {$classAtt} {$enctypeAtt}>
            <input type="hidden" name="formId" value="{$this->formId}" />
            {$camposFormulario}
        </form>
        HTML;
    }

    /**
     * Debe ser implementada por clases derivadas para generar los campos específicos del formulario.
     *
     * @param array &$datos Datos para generar los campos del formulario.
     * @return string HTML de los campos del formulario.
     */
    
    abstract protected function generaCamposFormulario(array &$datos): string;

/**
     * Debe ser implementada por clases derivadas para procesar los datos del formulario.
     * Debe devolver un array con errores de validación o procesamiento, o cualquier otro valor para indicar éxito.
     *
     * @param array &$datos Datos enviados por el usuario.
     * @return mixed Resultado del procesamiento del formulario.
     */

    abstract protected function procesaFormulario(array &$datos);

    /**
     * Función auxiliar para crear mensajes de error asociados a campos específicos del formulario.
     * 
     * @param array $errores Errores de validación o procesamiento del formulario.
     * @param string $idError Identificador del error a mostrar.
     * @param string $htmlElement Elemento HTML para encerrar el mensaje de error.
     * @param array $atts Atributos adicionales para el elemento HTML del mensaje de error.
     * @return string HTML del mensaje de error.
     */
    protected static function createMensajeError(array $errores = [], string $idError = '', string $htmlElement = 'span', array $atts = []): string
    {
        if (!isset($errores[$idError])) {
            return '';
        }

        $attributes = implode(' ', array_map(fn($key, $value) => "$key=\"$value\"", array_keys($atts), $atts));
        return "<$htmlElement $attributes>{$errores[$idError]}</$htmlElement>";
    }
}
