<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw;

class Notificacion
{
    public const CONTRATO_TIPO = 1;
    public const ENCARGO_TIPO = 2;
    public const NOTICIA_TIPO = 3;

    public const NO_VISTO_ESTADO = 1;
    public const VISTO_ESTADO = 2;

    // Tipos de notificación
    public const NOTIFICA_CREACION = 1;  // Asumamos que 1 es para la creación de un contrato pendiente de aprobación
    public const NOTIFICA_APROBACION = 2;  // Asumamos que 2 es cuando un contrato es aprobado
    public const NOTIFICA_RECHAZO = 3;  // Asumamos que 3 es cuando un contrato es rechazado
    
    private $id;
    private $idReferencia;
    private $tipo;
    private $fecha;
    private $estado;
    private $idEmisor;
    private $idReceptor;
    private $titulo;

    public function __construct($idReferencia, $tipo, $estado, $idEmisor, $idReceptor, $titulo, $id = null)
    {
        $this->id = $id;
        $this->idReferencia = $idReferencia;
        $this->tipo = $tipo;
        $this->estado = $estado;
        $this->idEmisor = $idEmisor;
        $this->idReceptor = $idReceptor;
        $this->titulo = $titulo;
    }

    public function getEstado() {
        return $this->estado;
    }

    public static function insertarNotificacion(Notificacion $notificacion)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "INSERT INTO notificaciones (idReferencia, tipo, fecha, estado, idEmisor, idReceptor, titulo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $currentDate = date('Y-m-d H:i:s');
            $stmt->bind_param("iisiiis", 
                $notificacion->idReferencia, 
                $notificacion->tipo, 
                $currentDate, 
                $notificacion->estado, 
                $notificacion->idEmisor, 
                $notificacion->idReceptor, 
                $notificacion->titulo);

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

    public static function getNotificacionesPorUsuario($idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM notificaciones WHERE idReceptor = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $idUsuario);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $notificaciones = [];
                while ($fila = $result->fetch_assoc()) {
                    $notificaciones[] = new Notificacion($fila['idReferencia'], $fila['tipo'], $fila['fecha'], $fila['estado'], $fila['idEmisor'], $fila['idReceptor'], $fila['titulo'], $fila['id']);
                }
                $stmt->close();
                return $notificaciones;
            } else {
                $stmt->close();
                error_log("Error al obtener notificaciones: " . $stmt->error);
                return null;
            }
        } else {
            error_log("Error al preparar la consulta de notificaciones: " . $conn->error);
            return null;
        }
    }

    public static function registrar(Notificacion $notificacion)
    {
        return self::insertarNotificacion($notificacion->idReferencia, $notificacion->idEmisor, $notificacion->idReceptor, $notificacion->tipo, $notificacion->titulo);
    }
}
?>