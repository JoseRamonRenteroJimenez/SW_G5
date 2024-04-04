<?php

namespace es\ucm\fdi\aw;

class Anuncio
{
    private $id;
    private $titulo;
    private $descripcion;
    private $usuarioId; // Asume que cada anuncio está asociado con un usuario

    public function __construct($titulo, $descripcion, $usuarioId, $id = null)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->usuarioId = $usuarioId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    // Métodos para insertar, actualizar y eliminar anuncios

    public static function obtenerPorUsuarioId($usuarioId)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM anuncios WHERE usuario_id = %d", $usuarioId);
        $result = $conn->query($query);
        $anuncios = [];
        
        if ($result) {
            while ($fila = $result->fetch_assoc()) {
                $anuncios[] = new Anuncio($fila['titulo'], $fila['descripcion'], $fila['usuario_id'], $fila['id']);
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $anuncios;
    }

}

?>
