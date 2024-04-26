<?php
namespace es\ucm\fdi\aw\clases;

class Ambito
{
    private $id;
    private $nombre;

    public function __construct($id, $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public static function getAmbitos()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM ambitos";
        $rs = $conn->query($query);
        $ambitos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $ambito = new Ambito($fila['id'], $fila['nombre']);
                $ambitos[] = $ambito;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $ambitos;
    }

    public static function guardarAmbitoManualmente($nombre)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $nombre = $conn->real_escape_string($nombre);
        $query = "INSERT INTO ambitos (nombre) VALUES ('$nombre')";
        if ($conn->query($query)) {
            return $conn->insert_id; // Devuelve el ID del ámbito insertado
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
}
?>
