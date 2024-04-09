<?php
namespace es\ucm\fdi\aw;

//require_once __DIR__.'/../../includes/clases/Pueblo.php'; 
//require_once __DIR__.'/../../includes/clases/Empresa.php';

class Contrato
{
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
            return $conn->insert_id;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
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
}
?>
