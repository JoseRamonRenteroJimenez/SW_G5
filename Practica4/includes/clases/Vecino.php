<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Usuario;

class Vecino extends Usuario
{
    private $id;
    private $idPueblo;

    public function __construct($id, $idPueblo)
    {
        $this->id = $id;
        $this->idPueblo = $idPueblo;
    }

    public static function getVecinos()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM vecinos";
        $resultado = $conn->query($query);

        $vecinos = [];

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $vecinos[] = new Vecino($fila['id'], $fila['idPueblo']);
            }
            $resultado->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $vecinos;
    }

    public static function getVecinosPorPueblo($idPueblo)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM vecinos WHERE idPueblo = ?";
        
        $vecinos = [];
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $idPueblo);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($fila = $result->fetch_assoc()) {
                $vecinos[] = new Vecino($fila['id'], $fila['idPueblo']);
            }
            $stmt->close();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $vecinos;
    }

    // Método para agregar un nuevo vecino a la base de datos
    public function inserta()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO vecinos (id, idPueblo) VALUES (%d, %d)",
            $this->id,
            $this->idPueblo
        );
        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function registrar(Vecino $vecino)
    {
        // Guardar el pueblo en la base de datos
        if ($vecino->inserta()) {
            return true; // Devolver true para indicar éxito
        } else {
            return false; // Error al registrar el pueblo
        }
    }

    // Getters y setters
    public function getId()
    {
        return $this->id;
    }

    public function getIdPueblo()
    {
        return $this->idPueblo;
    }

    public function setIdPueblo($idPueblo)
    {
        $this->idPueblo = $idPueblo;
    }

    public static function buscaNombreVecino($idVecino)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        
        // Query para obtener el nombre de usuario asociado a la ID de vecino
        $query = "SELECT nombreUsuario FROM usuarios WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $idVecino);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($fila = $result->fetch_assoc()) {
                $nombreUsuario = $fila['nombreUsuario'];
                return $nombreUsuario;
            }
        }
        return null;
    }

    public static function eliminarPorId($idEmpresa)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM vecinos WHERE id = ?";
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $idEmpresa);
            if ($stmt->execute()) {
                return true; // Eliminación exitosa
            } else {
                error_log("Error al eliminar el vecino ({$stmt->errno}): {$stmt->error}");
                return false; // Error al ejecutar la eliminación
            }
            $stmt->close();
        } else {
            error_log("Error al preparar la consulta de eliminación ({$conn->errno}): {$conn->error}");
            return false; // Error al preparar la consulta
        }
    }
}
