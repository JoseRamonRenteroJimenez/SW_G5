<?php

namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/config.php';
require_once 'Formulario.php'; 

class FormularioBorrarAnuncio extends Formulario {
    private $idAnuncio;

    public function __construct($idAnuncio) {
        parent::__construct('formBorrarAnuncio', [
            'urlRedireccion' => '../index.php'
        ]);
        $this->idAnuncio = $idAnuncio;
    }

    protected function generaCamposFormulario(&$datos) {
        // Utiliza heredoc syntax para el HTML
        $html = <<<EOF
        <fieldset>
            <legend>Confirmar borrado</legend>
            <p>¿Estás seguro de que deseas borrar este anuncio?</p>
            <input type="hidden" name="idAnuncio" value="{$this->idAnuncio}" />
            <div>
                <button type="submit" name="submit">Borrar Anuncio</button>
            </div>
        </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $idAnuncio = $datos['idAnuncio'] ?? null;
        if (!$idAnuncio) {
            $this->errores[] = "El anuncio a borrar no fue especificado.";
            return;
        }
        if (Anuncio::borrarPorId($idAnuncio)) {
            // Borrado exitoso; la redirección se maneja por la clase base.
        } else {
            // Hubo un problema al borrar el anuncio.
            $this->errores[] = "Hubo un error al borrar el anuncio.";
        }
    }
}

// $formularioBorrar = new FormularioBorrarAnuncio($idAnuncio);
// $formularioBorrar->gestiona();
