<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Usuario;

class Vecino extends Usuario
{
    private $id;
    private $idPueblo;
    private $idEmpresa;

    public function __construct($id, $idPueblo, $idEmpresa = null)
    {
        $this->id = $id;
        $this->idPueblo = $idPueblo;
        $this->idEmpresa = $idEmpresa;
    }

    public static function getVecinos()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM vecinos";
        $resultado = $conn->query($query);

        $vecinos = [];

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $vecinos[] = new Vecino($fila['id'], $fila['idPueblo'], $fila['idEmpresa']);
            }
            $resultado->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $vecinos;
    }

    public static function getVecinosPorPueblo($idPueblo)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM vecinos WHERE idPueblo = ?";
        
        $vecinos = [];
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $idPueblo);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($fila = $result->fetch_assoc()) {
                $vecinos[] = new Vecino($fila['id'], $fila['idPueblo'], $fila['idEmpresa']);
            }
            $stmt->close();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $vecinos;
    }

    // Método para agregar un nuevo vecino a la base de datos
    public function inserta()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO vecinos (id, idPueblo, idEmpresa) VALUES (%d, %d, %d)",
            $this->id,
            $this->idPueblo,
            $this->idEmpresa
        );
        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    // Getters y setters
    public function getId()
    {
        return $this->id;
    }

    public function getIdPueblo()
    {
        return $this->idPueblo;
    }

    public function setIdPueblo($idPueblo)
    {
        $this->idPueblo = $idPueblo;
    }

    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }

    public function setIdEmpresa($idEmpresa)
    {
        $this->idEmpresa = $idEmpresa;
    }
}
