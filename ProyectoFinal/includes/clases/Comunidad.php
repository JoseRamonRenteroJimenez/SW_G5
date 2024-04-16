<?php
namespace es\ucm\fdi\aw;

class Comunidad
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

    public static function getComunidades()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM comunidades";
        $rs = $conn->query($query);
        $comunidades = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $comunidad = new Comunidad($fila['id'], $fila['nombre']);
                $comunidades[] = $comunidad;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $comunidades;
    }
}
?>
