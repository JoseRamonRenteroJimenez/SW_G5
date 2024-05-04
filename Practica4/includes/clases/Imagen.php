<?php
namespace es\ucm\fdi\aw;

class Imagen
{
    
    public const USUARIO_PROPIETARIO = 1;
    public const ANUNCIO_PROPIETARIO = 2;

    private $id;
    private $idPropietario;
    private $ruta;
    private $nombre;
    private $mimeType;
    private $tipoPropietario;

    private function __construct($idPropietario, $ruta, $nombre, $mimeType, $tipoPropietario, $id = null)
    {
        $this->id = $id;
        $this->idPropietario = $idPropietario;
        $this->ruta = $ruta;
        $this->nombre = $nombre;
        $this->mimeType = $mimeType;
        $this->tipoPropietario = $tipoPropietario;
    }

    public static function crea($idPropietario, $filePath, $fileName, $fileType, $tipoPropietario)
    {
        if (!self::isFileValid($fileName, $fileType)) {
            error_log("Invalid file type or size");
            return null;
        }
        if (self::buscaPropietario($idPropietario)) {
            error_log("Este usuario ya tiene una imagen");
            return 0;
        }
        return self::inserta($idPropietario, $filePath, $fileName, $fileType, $tipoPropietario);
    }

    public static function buscaPropietario($idPropietario) // Busca un usuario por su nombre de usuario
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM imagenes U WHERE U.idPropietario='%s'", $conn->real_escape_string($nombreUsuario));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Imagen($fila['idPropietario'], $fila['ruta'], $fila['nombre'], $fila['mimeType'], $fila['tipoAcceso'], $fila['tipoPropietario'], $fila['id']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function inserta($idPropietario, $filePath, $fileName, $fileType, $tipoPropietario)
    {
        $db = Aplicacion::getInstance()->conexionBd();
        $query = "INSERT INTO imagenes (idPropietario, ruta, nombre, mimeType, tipoPropietario) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("isssi", $idPropietario, $filePath, $fileName, $fileType, $tipoPropietario);
        if ($stmt->execute()) {
            return new self($db->insert_id, $idPropietario, $filePath, $fileName, $fileType, $tipoPropietario);
        } else {
            error_log("Error BD ({$db->errno}): {$db->error}");
            return null;
        }
    }

    private static function isFileValid($fileName, $fileType)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        return in_array($fileExtension, $allowedExtensions) && strpos($fileType, 'image/') === 0;
    }

    public function guarda()
    {
        $db = Aplicacion::getInstance()->conexionBd();
        if ($this->id !== null) {
            $query = "UPDATE imagenes SET ruta=?, nombre=?, mimeType=?, tipoPropietario=? WHERE id=?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("sssii", $this->ruta, $this->nombre, $this->mimeType, $this->tipoPropietario, $this->id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        }
        return false;
    }

    public static function buscaPorId($id)
    {
        $db = Aplicacion::getInstance()->conexionBd();
        $query = "SELECT * FROM imagenes WHERE id=?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($fila = $result->fetch_assoc()) {
            return new Imagen($fila['idPropietario'], $fila['ruta'], $fila['nombre'], $fila['mimeType'], $fila['tipoPropietario'], $fila['id']);
        }
        return null;
    }

    private static function borra($imagen)
    {
        return self::borraPorId($imagen->id);
    }
    
    private static function borraPorId($idImagen)
    {
        if (!$idImagen) {
            return false;
        } 
        
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM imagenes WHERE id = %d", $idImagen);
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    // Additional getters and setters as needed
}
?>
