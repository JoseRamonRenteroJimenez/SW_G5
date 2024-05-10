<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw;

require_once __DIR__.'/../../includes/clases/Usuario.php';  //Usuario debe estar antes que Pueblo y Empresa
require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
require_once __DIR__.'/../../includes/clases/Empresa.php';
require_once __DIR__.'/../../includes/clases/Ambito.php'; 
require_once __DIR__.'/../../includes/clases/Servicio.php';
require_once __DIR__.'/../../includes/clases/Notificacion.php';

class Contrato
{
    // Estados de un contrato
    public const ACTIVO_ESTADO = 1;
    public const FINALIZADO_ESTADO = 2;
    public const CANCELADO_ESTADO = 3;
    public const ESPERA_ESTADO = 4 ;
    public const ALTERADO_ESTADO = 5;

    private $id;
    private $idEmpresa;
    private $idPueblo;
    private $fechaInicial;
    private $fechaFinal;
    private $terminos;
    private $estado;

    public function __construct($idEmpresa, $idPueblo, $fechaInicial, $fechaFinal, $terminos, $estado = self::ESPERA_ESTADO, $id = null)
    {
        $this->id = $id;
        $this->idEmpresa = $idEmpresa;
        $this->idPueblo = $idPueblo;
        $this->fechaInicial = $fechaInicial;
        $this->fechaFinal = $fechaFinal;
        $this->terminos = $terminos;
        $this->estado = $estado;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }

    public function getIdPueblo()
    {
        return $this->idPueblo;
    }

    public function getTerminos()
    {
        return $this->terminos;
    }

    public function getEstado()
    {
        return $this->estado;
    }
    
    public function getFechaInicial()
    {
        return $this->fechaInicial;
    }

    public function getFechaFinal()
    {
        return $this->fechaFinal;
    }
    

    public static function getContratos()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM contratos";
        $rs = $conn->query($query);
        $contratos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $contrato = new Contrato($fila['idEmpresa'], $fila['idPueblo'], $fila['fechaInicial'], $fila['fechaFinal'], $fila['terminos'], $fila['estado'], $fila['id']);
                $contratos[] = $contrato;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $contratos;
    }

    public static function buscaContratoPorId($idContrato)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM contratos WHERE id=%d", $idContrato);
        $rs = $conn->query($query);
        if ($rs) {
            if ($fila = $rs->fetch_assoc()) {
                return new Contrato($fila['idEmpresa'], $fila['idPueblo'], $fila['fechaInicial'], $fila['fechaFinal'], $fila['terminos'], $fila['estado'], $fila['id']);
            }
            $rs->free();
        }
        return null;
    }

    public static function buscaContratosPorEmpresa($idEmpresa)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM contratos WHERE idEmpresa=%d", $idEmpresa);
        $rs = $conn->query($query);
        $contratos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $contratos[] = new Contrato($fila['idEmpresa'], $fila['idPueblo'], $fila['fechaInicial'], $fila['fechaFinal'], $fila['terminos'], $fila['estado'], $fila['id']);
            }
            $rs->free();
        }
        return $contratos;
    }

    public static function buscaContratosPorPueblo($idPueblo)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM contratos WHERE idPueblo=%d", $idPueblo);
        $rs = $conn->query($query);
        $contratos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $contratos[] = new Contrato($fila['idEmpresa'], $fila['idPueblo'], $fila['fechaInicial'], $fila['fechaFinal'], $fila['terminos'], $fila['estado'], $fila['id']);
            }
            $rs->free();
        }
        return $contratos;
    }
    
    public static function registrar(Contrato $contrato)
    {
        // Guardar la empresa en la base de datos
        if ($contrato->inserta()) {
            return true; // Devolver true para indicar éxito
        } else {
            return false; // Error al registrar el pueblo
        }
    }

    public static function inserta($idEmpresa, $idPueblo, $fechaInicial, $fechaFinal, $terminos)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $idEmpresa = $conn->real_escape_string($idEmpresa);
        $idPueblo = $conn->real_escape_string($idPueblo);
        $fechaInicial = $conn->real_escape_string($fechaInicial);
        $fechaFinal = $conn->real_escape_string($fechaFinal);
        $terminos = $conn->real_escape_string($terminos);

        $query = sprintf("INSERT INTO contratos (idEmpresa, idPueblo, fechaInicial, fechaFinal, terminos, estado) VALUES (%d, %d, '%s', '%s', '%s', %d)",
            $idEmpresa, $idPueblo, $fechaInicial, $fechaFinal, $terminos, self::ESPERA_ESTADO);
        
        if ($conn->query($query)) {
            $contratoId = $conn->insert_id;
            // Inserta notificación de creación de contrato pendiente
            $notificacion = new Notificacion($contratoId, Notificacion::CONTRATO_TIPO, Notificacion::NO_VISTO_ESTADO, $idEmpresa, $idPueblo, "Nuevo Contrato Pendiente de Aprobación");
            Notificacion::insertarNotificacion($notificacion);
            return $contratoId;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function actualizarEstadoContrato($idContrato, $nuevoEstado)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "UPDATE contratos SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Error preparing statement: " . $conn->error);
            return false;
        }

        $stmt->bind_param("ii", $nuevoEstado, $idContrato);
        if (!$stmt->execute()) {
            error_log("Error updating contract status ({$conn->errno}): {$conn->error}");
            $stmt->close();
            return false;
        }
        $stmt->close();

        
        return self::enviarNotificacionEstadoContrato($idContrato, $nuevoEstado);
    }

    private static function enviarNotificacionEstadoContrato($idContrato, $estado)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT idEmpresa, idPueblo FROM contratos WHERE id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Error preparing select statement: " . $conn->error);
            return false;
        }

        $stmt->bind_param("i", $idContrato);
        if (!$stmt->execute()) {
            error_log("Error executing select statement ({$conn->errno}): {$conn->error}");
            $stmt->close();
            return false;
        }
        $stmt->bind_result($idEmpresa, $idPueblo);
        if (!$stmt->fetch()) {
            error_log("No contract found with ID: $idContrato");
            $stmt->close();
            return false;
        }
        $stmt->close();

        // Inserta notificación de cambio de estado
        $notificacionEmpresa = null;
        $notificacionPueblo = null;
        switch ($estado) {
            case self::ACTIVO_ESTADO:
                $message = "Contrato Aprobado";
                $notificacionEmpresa = new Notificacion($idContrato, Notificacion::CONTRATO_TIPO, Notificacion::NO_VISTO_ESTADO, $idPueblo, $idEmpresa, $message);
                break;
            case self::FINALIZADO_ESTADO:
                $message = "Contrato Finalizado";
                $notificacionEmpresa = new Notificacion($idContrato, Notificacion::CONTRATO_TIPO, Notificacion::NO_VISTO_ESTADO, $idEmpresa, $idPueblo, $message);
                $notificacionPueblo = new Notificacion($idContrato, Notificacion::CONTRATO_TIPO, Notificacion::NO_VISTO_ESTADO, $idPueblo, $idEmpresa, $message);
                break;
            case self::CANCELADO_ESTADO:
                $message = "Contrato Cancelado";
                $notificacionEmpresa = new Notificacion($idContrato, Notificacion::CONTRATO_TIPO, Notificacion::NO_VISTO_ESTADO, $idEmpresa, $idPueblo, $message);
                $notificacionPueblo = new Notificacion($idContrato, Notificacion::CONTRATO_TIPO, Notificacion::NO_VISTO_ESTADO, $idPueblo, $idEmpresa, $message);
                break;
            case self::ESPERA_ESTADO:
                $message = "Contrato en Espera";
                $notificacionPueblo = new Notificacion($idContrato, Notificacion::CONTRATO_TIPO, Notificacion::NO_VISTO_ESTADO, $idEmpresa, $idPueblo, $message);  
            case self::ALTERADO_ESTADO:
                $message = "Contrato Modificado";
                $notificacionPueblo = new Notificacion($idContrato, Notificacion::CONTRATO_TIPO, Notificacion::NO_VISTO_ESTADO, $idEmpresa, $idPueblo, $message);                
                break;
            default:
                $message = "Actualización de Contrato";
                break;
        }
        if($notificacionEmpresa != null) Notificacion::insertarNotificacion($notificacionEmpresa);
        if($notificacionPueblo != null) Notificacion::insertarNotificacion($notificacionPueblo);
        return true;
    }


    // Esto debería actualizar solo un contrato
    public static function actualiza($id, $fechaInicial, $fechaFinal, $terminos, $nuevoEstado)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $fechaInicial = $conn->real_escape_string($fechaInicial ?? '');  // Default to empty string if null
        $fechaFinal = $conn->real_escape_string($fechaFinal ?? '');
        $terminos = $conn->real_escape_string($terminos ?? '');

        $query = sprintf("UPDATE contratos SET fechaInicial='%s', fechaFinal='%s', terminos='%s', estado=%d WHERE id=%d",
            $fechaInicial, $fechaFinal, $terminos, $nuevoEstado, $id);

        if ($conn->query($query)) {
          // Inserta notificación de cambio de estado
            $notificationType = null;
            $notificationTitle = "";

            switch ($nuevoEstado) {
                case self::ACTIVO_ESTADO:
                    $notificationType = Notificacion::NOTIFICA_APROBACION;
                    $notificationTitle = "Contrato Aprobado";
                    break;
                case self::CANCELADO_ESTADO:
                    $notificationType = Notificacion::NOTIFICA_RECHAZO;
                    $notificationTitle = "Contrato Cancelado";
                    break;
                case self::ESPERA_ESTADO:
                    $notificationType = Notificacion::NOTIFICA_CREACION;
                    $notificationTitle = "Contrato Pendiente de Aprobación";
                    break;
            }
            
            if ($notificationType !== null) {
                $query = "SELECT idEmpresa, idPueblo FROM contratos WHERE id = $id";
                $result = $conn->query($query);
                if ($row = $result->fetch_assoc()) {
                    $notificacion = new Notificacion($id, Notificacion::CONTRATO_TIPO, Notificacion::NO_VISTO_ESTADO, $row['idEmpresa'], $row['idPueblo'], $notificationTitle);
                    Notificacion::insertarNotificacion($notificacion);
                }
            }

            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }


    public static function eliminaContratoPorId($idContrato)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM contratos WHERE id = %d", $idContrato);
        
        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function eliminarContratosPueblo($idPueblo)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM contratos WHERE idPueblo = ?";
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $idPueblo);
            if ($stmt->execute()) {
                return true; // Eliminación exitosa
            } else {
                error_log("Error al eliminar los contratos del pueblo ({$stmt->errno}): {$stmt->error}");
                return false; // Error al ejecutar la eliminación
            }
            $stmt->close();
        } else {
            error_log("Error al preparar la consulta de eliminación ({$conn->errno}): {$conn->error}");
            return false; // Error al preparar la consulta
        }
    }

    public static function eliminarContratosEmpresa($idEmpresa)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM contratos WHERE idEmpresa = ?";
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $idEmpresa);
            if ($stmt->execute()) {
                return true; // Eliminación exitosa
            } else {
                error_log("Error al eliminar los contratos de la empresa ({$stmt->errno}): {$stmt->error}");
                return false; // Error al ejecutar la eliminación
            }
            $stmt->close();
        } else {
            error_log("Error al preparar la consulta de eliminación ({$conn->errno}): {$conn->error}");
            return false; // Error al preparar la consulta
        }
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
            case self::ALTERADO_ESTADO:
                return 'Modificado en espera';
            default:
                return 'Desconocido';
        }
    }
}
?>