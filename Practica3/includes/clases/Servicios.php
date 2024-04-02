<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/clases/Pueblo.php';
require_once __DIR__.'/../../includes/clases/Ambito.php'; 

class Servicio
{
    private $id;
    private $idPueblo;
    private $idAmbito;
    private $cantidad;

    public function __construct($idPueblo, $idAmbito, $cantidad, $id = null)
    {
        $this->id = $id;
        $this->idPueblo = $idPueblo;
        $this->idAmbito = $idAmbito;
        $this->cantidad = $cantidad;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdPueblo()
    {
        return $this->idPueblo;
    }

    public function getIdAmbito()
    {
        return $this->idAmbito;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    // Método para obtener todos los servicios
    public static function getServicios()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM servicios";
        $rs = $conn->query($query);
        $servicios = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $servicio = new Servicio($fila['idPueblo'], $fila['idAmbito'], $fila['cantidad'], $fila['id']);
                $servicios[] = $servicio;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $servicios;
    }

    // Método para guardar un nuevo servicio
    public static function guardarServicio($idPueblo, $idAmbito, $cantidad)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "INSERT INTO servicios (idPueblo, idAmbito, cantidad) VALUES (?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iii', $idPueblo, $idAmbito, $cantidad);
        
        if ($stmt->execute()) {
            return $conn->insert_id;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    // Método para buscar un servicio por su ID
    public static function buscaServicioPorId($idServicio)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM servicios WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $idServicio);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($fila = $result->fetch_assoc()) {
                return new Servicio($fila['idPueblo'], $fila['idAmbito'], $fila['cantidad'], $fila['id']);
            }
        }
        return null;
    }

    // Método para actualizar un servicio
    public static function actualiza($id, $idPueblo, $idAmbito, $cantidad)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "UPDATE servicios SET idPueblo = ?, idAmbito = ?, cantidad = ? WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiii', $idPueblo, $idAmbito, $cantidad, $id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    // Método para eliminar un servicio por su ID
    public static function eliminaServicioPorId($idServicio)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM servicios WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $idServicio);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
}
?>
