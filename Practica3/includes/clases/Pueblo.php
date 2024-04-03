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

    public static function getPueblos()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM pueblos";
        $resultado = $conn->query($query);

        $pueblos = []; // Array para almacenar los objetos Pueblo

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $pueblos[] = new Pueblo($fila['id'], $fila['cif'], $fila['comunidad']);
            }
            $resultado->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $pueblos;
    }

     public static function getPueblosdeComunidad($idComunidad)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT p.* FROM pueblos p JOIN comunidades c ON p.comunidad = c.id WHERE c.id = ?";
        
        $pueblos = [];
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $idComunidad);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($fila = $result->fetch_assoc()) {
                $pueblo = new Pueblo($fila['id'], $fila['cif'], $fila['comunidad']);
                $pueblos[] = $pueblo;
            }
            $stmt->close();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $pueblos;
    }

    public static function registrar(Pueblo $pueblo)
    {
        // Verificar si ya existe un usuario con el mismo nombreUsuario
        if (self::buscaCif($pueblo->cif) != false) {
            error_log("Usuario ya existe");
            return null; // Retorna null para indicar que el usuario ya existe
        }
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
            $this->id,
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

    public static function buscaCif($cif)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM pueblos WHERE cif='%s'", $conn->real_escape_string($cif));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Pueblo($fila['id'], $fila['cif'], $fila['comunidad']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
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
