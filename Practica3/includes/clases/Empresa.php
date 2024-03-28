<?php
namespace es\ucm\fdi\aw;

class Empresa extends Usuario
{
    private $id;
    private $numTrabajadores;
    private $ambito;

    public function __construct($id, $numTrabajadores, $ambito)
    {
        $this->id = $id;
        $this->numTrabajadores = $numTrabajadores;
        $this->ambito = $ambito;
    }

    public static function registrar(Empresa $empresa)
    {
        // Guardar la empresa en la base de datos
        if ($empresa->guarda()) {
            return true; // Registro exitoso
        } else {
            return false; // Error al registrar la empresa
        }
    }

    protected function inserta()
    {
        // Llamada al método guarda de la clase padre para manejar la inserción en la tabla usuarios.
        if (parent::guarda()) {
            $conn = Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("INSERT INTO empresas (id, num_trabajadores, ambito) VALUES (%d, %d, '%s')",
                $this->getId(),
                $this->numTrabajadores,
                $conn->real_escape_string($this->ambito)
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

    protected function actualiza()
    {
        // Llamada al método guarda de la clase padre para manejar la actualización en la tabla usuarios.
        if (parent::guarda()) {
            $conn = Aplicacion::getInstance()->getConexionBd();
            $query = sprintf("UPDATE empresas SET num_trabajadores=%d, ambito='%s' WHERE id=%d",
                $this->numTrabajadores,
                $conn->real_escape_string($this->ambito),
                $this->getId()
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
}
?>
