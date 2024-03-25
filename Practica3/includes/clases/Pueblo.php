<?php
namespace es\ucm\fdi\aw;

class Pueblo extends Usuario
{
    private $cif;
    private $comunidad;

    public function __construct($nombreUsuario, $password, $nombre, $rol, $cif, $comunidad, $id = null)
    {
        parent::__construct($nombreUsuario, $password, $nombre, $id, $rol);
        $this->cif = $cif;
        $this->comunidad = $comunidad;
    }

    // Inserta o actualiza un pueblo en la base de datos.
    public function guarda()
    {
        if ($this->id === null) {
            return $this->inserta(); // Si es un nuevo pueblo, inserta.
        } else {
            return $this->actualiza(); // Si el pueblo ya existe, actualiza.
        }
    }

    protected function inserta()
    {
        // Llamada al método guarda de la clase padre para manejar la inserción en la tabla usuarios.
        if (parent::guarda()) {
            $conn = Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("INSERT INTO pueblos (id, cif, comunidad) VALUES (%d, '%s', %d)",
                $this->getId(),
                $conn->real_escape_string($this->cif),
                $this->comunidad
            );
            if ($conn->query($query)) {
                return true;
            } else {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
        }
        return false;
    }

    protected function actualiza()
    {
        // Llamada al método guarda de la clase padre para manejar la actualización en la tabla usuarios.
        if (parent::guarda()) {
            $conn = Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("UPDATE pueblos SET cif='%s', comunidad=%d WHERE id=%d",
                $conn->real_escape_string($this->cif),
                $this->comunidad,
                $this->getId()
            );
            if ($conn->query($query)) {
                return true;
            } else {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
        }
        return false;
    }

    // Getters y setters para los nuevos atributos.
    public function getCif()
    {
        return $this->cif;
    }

    public function setCif($cif)
    {
        $this->cif = $cif;
    }

    public function getComunidad()
    {
        return $this->comunidad;
    }

    public function setComunidad($comunidad)
    {
        $this->comunidad = $comunidad;
    }
}