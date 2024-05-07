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

    // Tipos de notificación
    public const NOTIFICA_CREACION = 1;  // Asumamos que 1 es para la creación de un contrato pendiente de aprobación
    public const NOTIFICA_APROBACION = 2;  // Asumamos que 2 es cuando un contrato es aprobado
    public const NOTIFICA_RECHAZO = 3;  // Asumamos que 3 es cuando un contrato es rechazado

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

    /*
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
            sreturn false;
        }
    }
    */

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
            Notificacion::insertarNotificacion(new Notificacion($contratoId, $idEmpresa, $idPueblo, self::NOTIFICA_CREACION, "Nuevo Contrato Pendiente de Aprobación"));
            return $contratoId;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }


    public static function confirmarContrato($idContrato, $confirmacion)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        // Actualizar el estado del contrato
        $estado = $confirmacion ? self::ACTIVO_ESTADO : self::CANCELADO_ESTADO;
        $query = "UPDATE contratos SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ii", $estado, $idContrato);
            if ($stmt->execute()) {
                $stmt->close();

                // Obtener la empresa asociada al contrato
                $query = "SELECT idEmpresa, idPueblo FROM contratos WHERE id = ?";
                $stmt = $conn->prepare($query);
                if ($stmt) {
                    $stmt->bind_param("i", $idContrato);
                    if ($stmt->execute()) {
                        $stmt->bind_result($idEmpresa, $idPueblo);
                        if ($stmt->fetch()) {
                            $stmt->close();
                            
                            // Obtener el ámbito de la empresa
                            $empresa = new Empresa($idEmpresa, null, null); // Crea una instancia de Empresa
                            $ambitoEmpresa = $empresa->getAmbitoEmpresa($idEmpresa); // Obtener el ámbito de la empresa
                            Servicio::registrar(new Servicio($idPueblo, $ambitoEmpresa, 1)); // Registrar el servicio en el pueblo

                            return true;
                        } else {
                            $stmt->close();
                            error_log("No se encontró el contrato con ID: $idContrato");
                            return false;
                        }
                    } else {
                        $stmt->close();
                        error_log("Error al ejecutar la consulta para obtener la empresa asociada al contrato ({$conn->errno}): {$conn->error}");
                        return false;
                    }
                } else {
                    error_log("Error al preparar la consulta para obtener la empresa asociada al contrato: " . $conn->error);
                    return false;
                }
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

    // Esto debería actualizar solo un contrato
    public static function actualiza($id, $fechaInicial, $fechaFinal, $terminos)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE contratos SET fechaInicial='%s', fechaFinal='%s', terminos='%s' WHERE id=%d",
            $fechaInicial, $fechaFinal, $conn->real_escape_string($terminos), $id);

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
