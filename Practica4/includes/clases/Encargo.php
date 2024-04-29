<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../Aplicacion.php';

class Encargo {
    // Estados de un Encargo
    public const ACTIVO_ESTADO = 1;
    public const FINALIZADO_ESTADO = 2;
    public const CANCELADO_ESTADO = 3;
    public const ESPERA_ESTADO = 4 ;

    // Tipos de notificación
    public const NOTIFICA_CREACION = 1;  // Asumamos que 1 es para la creación de un contrato pendiente de aprobación
    public const NOTIFICA_APROBACION = 2;  // Asumamos que 2 es cuando un contrato es aprobado
    public const NOTIFICA_RECHAZO = 3;  // Asumamos que 3 es cuando un contrato es rechazado


    private $id;
    private $idVecino;
    private $idEmpresa;
    private $descripcion;
    private $fecha;
    private $estado;

    public function __construct($idVecino, $idEmpresa, $descripcion, $fecha, $estado, $id = null) {
        $this->id = $id;
        $this->idVecino = $idVecino;
        $this->idEmpresa = $idEmpresa;
        $this->descripcion = $descripcion;
        $this->fecha = $fecha;
        $this->estado = $estado;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getIdVecino() {
        return $this->idVecino;
    }

    public function getIdEmpresa() {
        return $this->idEmpresa;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getEstado() {
        return $this->estado;
    }

    public static function inserta($idVecino, $idEmpresa, $descripcion) {
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $idVecino = $conn->real_escape_string($idVecino);
        $idEmpresa = $conn->real_escape_string($idEmpresa);
        $descripcion = $conn->real_escape_string($descripcion);
        
        $fecha = date('Y-m-d'); // Obtener la fecha actual en el formato 'YYYY-MM-DD'

    
        $query = sprintf("INSERT INTO encargos (idVecino, idEmpresa, descripcion, fecha, estado) VALUES (%d, %d, '%s', '%s', %d)",
            $idVecino, $idEmpresa, $descripcion, $fecha, self::ESPERA_ESTADO);
        
        if ($conn->query($query)) {
            $encargoId = $conn->insert_id;
            // Insertar notificación de creación de encargo pendiente
            self::insertarNotificacion($encargoId, $idVecino, $idEmpresa, self::NOTIFICA_CREACION);
            return $encargoId;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
    

    // Método para obtener un encargo por su ID
    public static function buscaCEncargoPorId($idEncargo) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM encargos WHERE id=%d", $idEncargo);
        $rs = $conn->query($query);
        if ($rs) {
            if ($fila = $rs->fetch_assoc()) {
                return new Encargo($fila['idVecino'], $fila['idEmpresa'], $fila['fecha'], $fila['descripcion'], $fila['estado'], $fila['id']);
            }
            $rs->free();
        }
        return null;
    }

    // Método para confirmar o rechazar un encargo
    public static function confirmarEncargo($idEncargo, $confirmacion) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        
        // Actualizar el estado del encargo según la confirmación
        $estado = $confirmacion ? self::ACTIVO_ESTADO : self::CANCELADO_ESTADO;
        $query = "UPDATE encargos SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ii", $estado, $idEncargo);
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Éxito al actualizar el estado del encargo
            } else {
                $stmt->close();
                error_log("Error al actualizar el estado del encargo: {$conn->errno} - {$conn->error}");
                return false; // Error al ejecutar la consulta de actualización
            }
        } else {
            error_log("Error al preparar la consulta de actualización del estado del encargo: {$conn->errno} - {$conn->error}");
            return false; // Error al preparar la consulta
        }
    }

    public static function eliminarEncargosVecino($idVecino)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM encargos WHERE idVecino = ?";
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $idVecino);
            if ($stmt->execute()) {
                return true; // Eliminación exitosa
            } else {
                error_log("Error al eliminar los encargos del vecino ({$stmt->errno}): {$stmt->error}");
                return false; // Error al ejecutar la eliminación
            }
            $stmt->close();
        } else {
            error_log("Error al preparar la consulta de eliminación ({$conn->errno}): {$conn->error}");
            return false; // Error al preparar la consulta
        }
    }

    public static function eliminarEncargosEmpresa($idEmpresa)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM encargos WHERE idEmpresa = ?";
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $idEmpresa);
            if ($stmt->execute()) {
                return true; // Eliminación exitosa
            } else {
                error_log("Error al eliminar los encargos de la empresa ({$stmt->errno}): {$stmt->error}");
                return false; // Error al ejecutar la eliminación
            }
            $stmt->close();
        } else {
            error_log("Error al preparar la consulta de eliminación ({$conn->errno}): {$conn->error}");
            return false; // Error al preparar la consulta
        }
    }

    public static function eliminarEncargoPorId($idEncargo)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM encargos WHERE id = %d", $idEncargo);
        
        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function buscaEncargosPorEmpresa($idEmpresa)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM encargos WHERE idEmpresa=%d", $idEmpresa);
        $rs = $conn->query($query);
        $encargos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $encargos[] = new Encargo($fila['idVecino'], $fila['idEmpresa'], $fila['descripcion'], $fila['fecha'], $fila['estado'], $fila['id']);
            }
            $rs->free();
        }
        return $encargos;
    }

    public static function buscaEncargosPorVecino($idVecino)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM encargos WHERE idVecino=%d", $idVecino);
        $rs = $conn->query($query);
        $encargos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $encargos[] = new Encargo($fila['idVecino'], $fila['idEmpresa'], $fila['descripcion'], $fila['fecha'], $fila['estado'], $fila['id']);
            }
            $rs->free();
        }
        return $encargos;
    }


}
?>
