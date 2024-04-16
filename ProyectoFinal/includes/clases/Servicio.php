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

    public static function registrar(Servicio $servicio)
    {
        // Guardar el servicio en la base de datos
        if ($servicio->inserta()) {
            return true; // Devolver true para indicar éxito
        } else {
            return false; // Error al registrar el servicio
        }
    }

    protected function inserta()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        
        // Verificar si ya existe una entrada con el mismo idPueblo e idAmbito
        $query_check = "SELECT id, cantidad FROM servicios WHERE idPueblo = ? AND idAmbito = ?";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bind_param('ii', $this->idPueblo, $this->idAmbito);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Ya existe una entrada, actualizar la cantidad
            $row = $result_check->fetch_assoc();
            $existing_id = $row['id'];
            $existing_cantidad = $row['cantidad'];
            $new_cantidad = $existing_cantidad + $this->cantidad;

            // Invocar el método actualiza
            return Servicio::actualiza($existing_id, $this->idPueblo, $this->idAmbito, $new_cantidad);
        } else {
            // No existe una entrada, insertar una nueva
            $query_insert = "INSERT INTO servicios (idPueblo, idAmbito, cantidad) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bind_param('iii', $this->idPueblo, $this->idAmbito, $this->cantidad);
            
            if ($stmt_insert->execute()) {
                $this->id = $conn->insert_id;
                return true; // Se insertó la nueva entrada correctamente
            } else {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
        }
    }

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

    public static function disminuirServiciosPorEmpresaYPueblo($ambitoEmpresa, $idPueblo)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "UPDATE servicios SET cantidad = cantidad - 1 WHERE idAmbito = $ambitoEmpresa AND idPueblo = $idPueblo";

        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
}
?>
