<?php
namespace es\ucm\fdi\aw;

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
}
?>
