<?php
namespace es\ucm\fdi\aw\clases;

class Contrato
{
    // Estados de un contrato
    public const ACTIVO_ESTADO = 1;
    public const FINALIZADO_ESTADO = 2;
    public const CANCELADO_ESTADO = 3;

    // Tipos de notificación
    public const NOTIFICA_CREACION = 1;  // Asumamos que 1 es para la creación de un contrato pendiente de aprobación
    public const NOTIFICA_APROBACION = 2;  // Asumamos que 2 es cuando un contrato es aprobado
    public const NOTIFICA_RECHAZO = 3;  // Asumamos que 3 es cuando un contrato es rechazado

    private $id;
    private $idEmpresa;
    private $idPueblo;
    private $duracion; // Días
    private $terminos;

    public function __construct($idEmpresa, $idPueblo, $duracion, $terminos, $id = null)
    {
        $this->id = $id;
        $this->idEmpresa = $idEmpresa;
        $this->idPueblo = $idPueblo;
        $this->duracion = $duracion;
        $this->terminos = $terminos;
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

    public function getDuracion()
    {
        return $this->duracion;
    }

    public function getTerminos()
    {
        return $this->terminos;
    }

    public static function getContratos()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM contratos";
        $rs = $conn->query($query);
        $contratos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $contrato = new Contrato($fila['idEmpresa'], $fila['idPueblo'], $fila['duracion'], $fila['terminos'], $fila['id']);
                $contratos[] = $contrato;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $contratos;
    }
    // Puede ser muy similar a inserta, hay que revisarlo porque puede estar duplicado
    public static function guarda($idEmpresa, $idPueblo, $duracion, $terminos)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $idEmpresa = $conn->real_escape_string($idEmpresa);
        $idPueblo = $conn->real_escape_string($idPueblo);
        $duracion = $conn->real_escape_string($duracion);
        $terminos = $conn->real_escape_string($terminos);

        $query = "INSERT INTO contratos (idEmpresa, idPueblo, duracion, terminos) VALUES ('$idEmpresa', '$idPueblo', '$duracion', '$terminos')";
        if ($conn->query($query)) {
            return $conn->insert_id; // Devuelve el ID del contrato insertado
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function buscaContratoPorId($idContrato)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM contratos WHERE id=%d", $idContrato);
        $rs = $conn->query($query);
        if ($rs) {
            if ($fila = $rs->fetch_assoc()) {
                return new Contrato($fila['idEmpresa'], $fila['idPueblo'], $fila['duracion'], $fila['terminos'], $fila['id']);
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
                $contratos[] = new Contrato($fila['idEmpresa'], $fila['idPueblo'], $fila['duracion'], $fila['terminos'], $fila['id']);
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
                $contratos[] = new Contrato($fila['idEmpresa'], $fila['idPueblo'], $fila['duracion'], $fila['terminos'], $fila['id']);
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
    public static function inserta($idEmpresa, $idPueblo, $duracion, $terminos)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO contratos (idEmpresa, idPueblo, duracion, terminos) VALUES (%d, %d, %d, '%s')",
            $idEmpresa, $idPueblo, $duracion, $conn->real_escape_string($terminos));
        
        if ($conn->query($query)) {
            $contratoId = $conn->insert_id;
            // Inserta notificación de creación de contrato pendiente
            self::insertarNotificacion($contratoId, $idEmpresa, $idPueblo, self::NOTIFICA_CREACION);
            return $contratoId;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    private static function insertarNotificacion($idReferencia, $idEmisor, $idReceptor, $tipoNotificacion)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $titulo = "Nuevo Contrato Pendiente de Aprobación";
        if ($tipoNotificacion == self::NOTIFICA_APROBACION) {
            $titulo = "Contrato Aprobado";
        }
        $estado = 0; // Estado no leído

        $query = "INSERT INTO notificaciones (idReferencia, tipo, fecha, estado, idEmisor, idReceptor, titulo) VALUES (?, ?, NOW(), ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("iiiiis", $idReferencia, $tipoNotificacion, $estado, $idEmisor, $idReceptor, $titulo);
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                error_log("Error al insertar la notificación: " . $stmt->error);
                return false;
            }
        } else {
            error_log("Error al preparar la consulta de inserción de notificación: " . $conn->error);
            return false;
        }
    }

    public static function rechazarContrato($idContrato, $idPueblo)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        // Actualizar el estado del contrato a CANCELADO
        $query = "UPDATE contratos SET estado = ? WHERE id = ? AND idPueblo = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $estadoCancelado = self::CANCELADO_ESTADO;
            $stmt->bind_param("iii", $estadoCancelado, $idContrato, $idPueblo);
            if ($stmt->execute()) {
                // Envía notificación de rechazo al emisor del contrato
                $contrato = self::buscaContratoPorId($idContrato);
                if ($contrato) {
                    self::insertarNotificacion($idContrato, $idPueblo, $contrato->getIdEmpresa(), self::NOTIFICA_RECHAZO);
                }
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                error_log("Error al actualizar el estado del contrato ({$conn->errno}): {$conn->error}");
                return false;
            }
        } else {
            error_log("Error al preparar la consulta de actualización del contrato: " . $conn->error);
            return false;
        }
    }

    // Esto deberia actualizar solo un contrato
    public static function actualiza($id, $idEmpresa, $idPueblo, $duracion, $terminos)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE contratos SET idEmpresa=%d, idPueblo=%d, duracion=%d, terminos='%s' WHERE id=%d",
            $idEmpresa, $idPueblo, $duracion, $conn->real_escape_string($terminos), $id);

        if ($conn->query($query)) {
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

}
?>
