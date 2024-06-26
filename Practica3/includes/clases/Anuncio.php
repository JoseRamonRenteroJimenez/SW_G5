<?php

namespace es\ucm\fdi\aw\clases;

class Anuncio
{
    private $id;
    private $titulo;
    private $descripcion;
    private $usuarioId; // Asume que cada anuncio está asociado con un usuario.
    private $contacto;

    public function __construct($titulo, $descripcion, $usuarioId, $contacto, $id = null)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->usuarioId = $usuarioId;
        $this->contacto = $contacto;
    }

    // Métodos getters para los atributos.
    public function getId()
    {
        return $this->id;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    public function getContacto()
    {
        return $this->contacto;
    }

    // Método estático para obtener los anuncios de un usuario específico.
    public static function getAnunciosByUserId($usuarioId)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM anuncios WHERE idAutor = %d", $usuarioId);
        $result = $conn->query($query);
        $anuncios = [];
        
        if ($result) {
            while ($fila = $result->fetch_assoc()) {
                $anuncios[] = new self($fila['titulo'], $fila['descripcion'], $fila['idAutor'], $fila['contacto'], $fila['id']);
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $anuncios;
    }

    public static function getAllAnuncios()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM anuncios");
        $result = $conn->query($query);
        $anuncios = [];
        
        if ($result) {
            while ($fila = $result->fetch_assoc()) {
                $anuncios[] = new self($fila['titulo'], $fila['descripcion'], $fila['idAutor'], $fila['contacto'], $fila['id']);
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $anuncios;
    }

    // Método estático para borrar un anuncio por su ID.
    public static function borrarPorId($idAnuncio)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM anuncios WHERE id = %d", $idAnuncio);

        if ($conn->query($query)) {
            if ($conn->affected_rows > 0) {
                return true;
            } else {
                error_log("Se intentó borrar un anuncio que no existe o ya fue borrado.");
                return false;
            }
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function insertar($titulo, $descripcion, $categoria, $usuarioId, $contacto) {
        // Validaciones básicas
        if (empty($titulo) || empty($descripcion)) {
            error_log("El título y la descripción no pueden estar vacíos.");
            return false;
        }
    
        // Validación de longitud mínima
        if (strlen($titulo) < 5) {
            error_log("El título debe tener al menos 5 caracteres.");
            return false;
        }
    
        if (strlen($descripcion) < 10) {
            error_log("La descripción debe tener al menos 10 caracteres.");
            return false;
        }
    
        // Sanitización de entradas
        $conn = Aplicacion::getInstance()->getConexionBd();
        $tituloSanitizado = $conn->real_escape_string($titulo);
        $descripcionSanitizada = $conn->real_escape_string($descripcion);
        $contactoSanitizado = $conn->real_escape_string($contacto);
    
        // Inserción en la base de datos
        $query = sprintf("INSERT INTO anuncios (titulo, descripcion, categoria, contacto, idAutor) VALUES ('%s', '%s', %d, '%s', %d)",
            $tituloSanitizado, $descripcionSanitizada, $categoria, $contactoSanitizado, $usuarioId);
        
        if ($conn->query($query)) {
            return $conn->insert_id; // Devuelve el ID del anuncio insertado.
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
    
    public static function actualizar($idAnuncio, $titulo, $descripcion, $contacto, $usuarioId) {
        // Validaciones 
        if (empty($titulo) || empty($descripcion)) {
            error_log("El título y la descripción no pueden estar vacíos.");
            return false;
        }
    
        if (strlen($titulo) < 5) {
            error_log("El título debe tener al menos 5 caracteres.");
            return false;
        }
    
        if (strlen($descripcion) < 10) {
            error_log("La descripción debe tener al menos 10 caracteres.");
            return false;
        }
    
        // Continuar con la actualización despues de la validacion
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE anuncios SET titulo='%s', descripcion='%s', contacto='%s', idAutor=%d WHERE id=%d",
            $conn->real_escape_string($titulo), $conn->real_escape_string($descripcion), $conn->real_escape_string($contacto), $usuarioId, $idAnuncio);

        if ($conn->query($query)) {
            if ($conn->affected_rows > 0) {
                return true;
            } else {
                error_log("No se actualizó ningún anuncio. Es posible que el anuncio no exista o los datos sean iguales a los existentes.");
                return false;
            }
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function eliminarPorIdAutor($idAutor)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM anuncios WHERE idAutor = ?";
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $idAutor);
            if ($stmt->execute()) {
                return true; // Eliminación exitosa
            } else {
                error_log("Error al eliminar los anuncios del autor ({$stmt->errno}): {$stmt->error}");
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
