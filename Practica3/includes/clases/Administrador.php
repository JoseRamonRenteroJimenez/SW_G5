<?php
namespace es\ucm\fdi\aw;

class Administrador extends Usuario
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    // Método estático para registrar un administrador
    public static function registrar(Administrador $admin)
    {
        // Guardar el administrador en la base de datos
        if ($admin->guarda()) {
            return true; // Registro exitoso
        } else {
            return false; // Error al registrar el administrador
        }
    }

    // Método para insertar un nuevo administrador en la base de datos
    protected function inserta()
    {
        // Llamada al método guarda de la clase padre para manejar la inserción en la tabla usuarios.
        if (parent::guarda()) {
            $conn = Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("INSERT INTO administradores (id) VALUES (%d)", $this->getId());
            if ($conn->query($query)) {
                return true;
            } else {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
        }
        return false;
    }

    // Método para actualizar los datos de un administrador en la base de datos
    protected function actualiza()
    {
        // Llamada al método guarda de la clase padre para manejar la actualización en la tabla usuarios.
        if (parent::guarda()) {
            // No hay nada que actualizar específicamente para un administrador en esta implementación
            return true;
        }
        return false;
    }

    public static function eliminarPorId($idAdministrador)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM administradores WHERE id = ?";
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $idAdministrador);
            if ($stmt->execute()) {
                return true; // Eliminación exitosa
            } else {
                error_log("Error al eliminar el administrador ({$stmt->errno}): {$stmt->error}");
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
