<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw;

class Notificacion
{
    public static function insertarNotificacion($idReferencia, $idEmisor, $idReceptor, $tipoNotificacion, $titulo)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
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
}
?>
