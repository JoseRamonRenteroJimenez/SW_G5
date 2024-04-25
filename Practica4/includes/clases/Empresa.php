<?php
namespace es\ucm\fdi\aw\clases;

class Empresa extends Usuario
{
    private $id;
    private $numTrabajadores;
    private $ambito; // Ahora almacena el ID del ámbito en lugar del nombre directamente

    public function __construct($id, $numTrabajadores, $ambito)
    {
        $this->id = $id;
        $this->numTrabajadores = $numTrabajadores;
        $this->ambito = $ambito;
    }

    public static function registrar(Empresa $empresa)
    {
        // Guardar la empresa en la base de datos
        if ($empresa->inserta()) {
            return true; // Devolver true para indicar éxito
        } else {
            return false; // Error al registrar el pueblo
        }
    }

    protected function inserta()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO empresas (id, nTrabajadores, ambito) VALUES (%d, %d, %d)",
            $this->id,
            $this->numTrabajadores,
            $this->ambito
        );
        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    protected function actualiza()
    {
        // Llamada al método guarda de la clase padre para manejar la actualización en la tabla usuarios.
        if (parent::guarda()) {
            $conn = Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("UPDATE empresas SET nTrabajadores=%d, ambito=%d WHERE id=%d",
                $this->numTrabajadores,
                $this->ambito,
                $this->id
            );
            if ($conn->query($query)) {
                return true;
            } else {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
        }
        return false;
    }

    // Getters y setters para los nuevos atributos.
    public function getNumTrabajadores()
    {
        return $this->numTrabajadores;
    }

    public function setNumTrabajadores($numTrabajadores)
    {
        $this->numTrabajadores = $numTrabajadores;
    }

    public function getAmbito()
    {
        return $this->ambito;
    }

    public function setAmbito($ambito)
    {
        $this->ambito = $ambito;
    }

    public static function buscaNombreEmpresa($idEmpresa)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        
        // Query para obtener el nombre de usuario asociado a la ID de empresa
        $query = "SELECT nombreUsuario FROM usuarios WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $idEmpresa);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($fila = $result->fetch_assoc()) {
                // Una vez obtenido el nombre de usuario, buscamos la empresa por su nombre de usuario
                $nombreUsuario = $fila['nombreUsuario'];
                return $nombreUsuario;
            }
        }
        return null;
    }

    public static function getAmbitoEmpresa($idEmpresa)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT ambito FROM empresas WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $idEmpresa);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($fila = $result->fetch_assoc()) {
                return $fila['ambito'];
            }
        }
        return null;
    }

    public static function eliminarPorId($idEmpresa)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM empresas WHERE id = ?";
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $idEmpresa);
            if ($stmt->execute()) {
                return true; // Eliminación exitosa
            } else {
                error_log("Error al eliminar la empresa ({$stmt->errno}): {$stmt->error}");
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
