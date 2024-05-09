<?php
namespace es\ucm\fdi\aw;

require_once __DIR__.'/../Aplicacion.php';
require_once __DIR__.'/../../includes/clases/Notificacion.php';

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
    private $terminos;
    private $fecha;
    private $estado;

    public function __construct($idVecino, $idEmpresa, $terminos, $fecha, $estado, $id = null) {
        $this->id = $id;
        $this->idVecino = $idVecino;
        $this->idEmpresa = $idEmpresa;
        $this->terminos = $terminos;
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

    public function getTerminos() {
        return $this->terminos;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getEstado() {
        return $this->estado;
    }

    public static function registrar(Contrato $contrato)
    {
        if ($encargo->inserta()) {
            return true; // Devolver true para indicar éxito
        } else {
            return false; // Error al registrar el pueblo
        }
    }

    public static function actualizaEstado($idEncargo, $nuevoEstado)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "UPDATE encargos SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Error preparing statement: " . $conn->error);
            return false;
        }
        $stmt->bind_param("ii", $nuevoEstado, $idEncargo);
        if ($stmt->execute()) {
            $stmt->close();
            // Send notification after updating the encargo status
            return self::enviarNotificacionEstadoEncargo($idEncargo, $nuevoEstado);
        } else {
            error_log("Error updating encargo status ({$stmt->errno}): {$stmt->error}");
            $stmt->close();
            return false;
        }
    }

    private static function enviarNotificacionEstadoEncargo($idEncargo, $nuevoEstado)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT idVecino, idEmpresa FROM encargos WHERE id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Error preparing select statement: " . $conn->error);
            return false;
        }
        $stmt->bind_param("i", $idEncargo);
        if (!$stmt->execute()) {
            error_log("Error executing select statement ({$stmt->errno}): {$conn->error}");
            $stmt->close();
            return false;
        }
        $stmt->bind_result($idVecino, $idEmpresa);
        if (!$stmt->fetch()) {
            error_log("No encargo found with ID: $idEncargo");
            $stmt->close();
            return false;
        }
        $stmt->close();

        // Prepare the notification message
        $message = "Encargo Actualizado";
        switch ($nuevoEstado) {
            case self::ACTIVO_ESTADO:
                $message = "Encargo Aceptado";
                break;
            case self::FINALIZADO_ESTADO:
                $message = "Encargo Completado";
                break;
            case self::CANCELADO_ESTADO:
                $message = "Encargo Cancelado";
                break;
            case self::ESPERA_ESTADO:
                $message = "Encargo Pendiente de Confirmación";
                break;
        }

        // Notify both Vecino and Empresa about the encargo state change
        $notificacionVecino = new Notificacion($idEncargo, Notificacion::ENCARGO_TIPO, Notificacion::NO_VISTO_ESTADO, $idEmpresa, $idVecino, $message);
        $notificacionEmpresa = new Notificacion($idEncargo, Notificacion::ENCARGO_TIPO, Notificacion::NO_VISTO_ESTADO, $idVecino, $idEmpresa, $message);
        Notificacion::insertarNotificacion($notificacionVecino);
        Notificacion::insertarNotificacion($notificacionEmpresa);

        return true;
    }

    public static function inserta($idVecino, $idEmpresa, $terminos, $fecha = null) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $fecha = $fecha ?? date('Y-m-d');
        $query = sprintf("INSERT INTO encargos (idVecino, idEmpresa, terminos, fecha, estado) VALUES (%d, %d, '%s', '%s', %d)",
            $idVecino, $idEmpresa, $terminos, $fecha, self::ESPERA_ESTADO);
        if ($conn->query($query)) {
            $encargoId = $conn->insert_id;
            $notificacion = new Notificacion($encargoId, Notificacion::ENCARGO_TIPO, Notificacion::NO_VISTO_ESTADO, $idVecino, $idEmpresa, "Nuevo Encargo Pendiente");
            Notificacion::insertarNotificacion($notificacion);
            return $encargoId;
        }
        error_log("Error BD ({$conn->errno}): {$conn->error}");
        return false;
    }
    

    // Método para obtener un encargo por su ID
    public static function buscaEncargoPorId($idEncargo) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM encargos WHERE id=%d", $idEncargo);
        $rs = $conn->query($query);
        if ($rs && $fila = $rs->fetch_assoc()) {
            return new Encargo($fila['idVecino'], $fila['idEmpresa'], $fila['terminos'], $fila['fecha'], $fila['estado'], $fila['id']);
        }
        return null;
    }

    // Método para confirmar o rechazar un encargo

    public static function confirmarEncargo($idEncargo, $confirmacion) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $estado = $confirmacion ? self::ACTIVO_ESTADO : self::CANCELADO_ESTADO;
        $query = "UPDATE encargos SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ii", $estado, $idEncargo);
            if ($stmt->execute()) {
                $stmt->close();
                Notificacion::insertarNotificacion(new Notificacion($idEncargo, Notificacion::ENCARGO_TIPO, Notificacion::VISTO_ESTADO, $idEmpresa, $idVecino, $confirmacion ? "Encargo Aprobado" : "Encargo Rechazado"));
                return true;
            }
            $stmt->close();
        }
        error_log("Error al actualizar encargo: {$conn->errno} - {$conn->error}");
        return false;
    }

    public static function actualiza($idEncargo, $idVecino, $idEmpresa, $terminos, $fecha, $estado) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE encargos SET idVecino=%d, idEmpresa=%d, terminos='%s', fecha='%s', estado=%d WHERE id=%d",
            $idVecino, $idEmpresa, $terminos, $fecha, $estado, $idEncargo);
        if ($conn->query($query)) {
            Notificacion::insertarNotificacion(new Notificacion($idEncargo, Notificacion::ENCARGO_TIPO, Notificacion::VISTO_ESTADO, $idEmpresa, $idVecino, "Encargo Actualizado"));
            return true;
        }
        error_log("Error al actualizar encargo: {$conn->errno} - {$conn->error}");
        return false;
    }

    public function translateEstado() {
        switch ($this->estado) {
            case self::ACTIVO_ESTADO:
                return 'Activo';
            case self::FINALIZADO_ESTADO:
                return 'Finalizado';
            case self::CANCELADO_ESTADO:
                return 'Cancelado';
            case self::ESPERA_ESTADO:
                return 'En espera';
            default:
                return 'Desconocido';
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

    public static function eliminaEncargoPorId($idEncargo) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM encargos WHERE id = %d", $idEncargo);
        if ($conn->query($query)) {
            Notificacion::insertarNotificacion(new Notificacion($idEncargo, Notificacion::ENCARGO_TIPO, Notificacion::VISTO_ESTADO, $idEmpresa, $idVecino, "Encargo Eliminado"));
            return true;
        }
        error_log("Error al eliminar encargo: {$conn->errno} - {$conn->error}");
        return false;
    }

    public static function buscaEncargosPorEmpresa($idEmpresa)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM encargos WHERE idEmpresa=%d", $idEmpresa);
        $rs = $conn->query($query);
        $encargos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $encargos[] = new Encargo($fila['idVecino'], $fila['idEmpresa'], $fila['terminos'], $fila['fecha'], $fila['estado'], $fila['id']);
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
                $encargos[] = new Encargo($fila['idVecino'], $fila['idEmpresa'], $fila['terminos'], $fila['fecha'], $fila['estado'], $fila['id']);
            }
            $rs->free();
        }
        return $encargos;
    }

    public static function getEncargos()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM encargos";
        $rs = $conn->query($query);
        $encargos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $encargo = new Encargo($fila['idVecino'], $fila['idEmpresa'], $fila['terminos'], $fila['fecha'], $fila['estado'], $fila['id']);
                $encargos[] = $encargo;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $encargos;
    }


}
?>
