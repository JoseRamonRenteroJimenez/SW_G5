<?php
namespace es\ucm\fdi\aw;

class Pueblo extends Usuario
{
    private $id;
    private $cif;
    private $comunidad;

    public function __construct($id, $cif, $comunidad)
    {
        $this->id = $id;
        $this->cif = $cif;
        $this->comunidad = $comunidad;
    }

    public static function registrar(Pueblo $pueblo)
    {
        // Verificar si ya existe un pueblo con el mismo cif antes de intentar registrar
        // Aquí deberías añadir una función que busque pueblos por su CIF para asegurarte de que no haya duplicados
        // Por simplicidad, asumiré que esa comprobación se realiza en otro lugar o se añade aquí más tarde
        
        // Guardar el pueblo en la base de datos
        if ($pueblo->inserta()) {
            return true; // Devolver true para indicar éxito
        } else {
            return false; // Error al registrar el pueblo
        }
    }
    
    protected function inserta()
    {
        // Asumiendo que el ID ya está establecido correctamente desde Usuario::crea()
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
