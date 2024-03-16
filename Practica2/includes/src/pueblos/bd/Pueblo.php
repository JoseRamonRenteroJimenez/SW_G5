<?php

class Pueblo
{
    use MagicProperties;

    public static function buscaPorId($idPueblo)
    {
        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM pueblo WHERE ID=%d", $idPueblo);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Pueblo($fila['ID'], $fila['c.a.'], $fila['nombre'], $fila['servicios'], $fila['password']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function crea($c_a, $nombre, $servicios, $password)
    {
        $pueblo = new Pueblo(null, $c_a, $nombre, json_encode($servicios), self::hashPassword($password));
        return $pueblo->guarda();
    }

    private static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private static function inserta($pueblo)
    {
        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO pueblo(`c.a.`, nombre, servicios, password) VALUES ('%s', '%s', '%s', '%s')",
            $conn->real_escape_string($pueblo->c_a),
            $conn->real_escape_string($pueblo->nombre),
            $conn->real_escape_string($pueblo->servicios),
            $conn->real_escape_string($pueblo->password)
        );
        if ($conn->query($query)) {
            $pueblo->ID = $conn->insert_id;
            return $pueblo;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    private $ID;
    private $c_a;
    private $nombre;
    private $servicios; // JSON string
    private $password;

    private function __construct($ID, $c_a, $nombre, $servicios, $password)
    {
        $this->ID = $ID;
        $this->c_a = $c_a;
        $this->nombre = $nombre;
        $this->servicios = $servicios;
        $this->password = $password;
    }

    public function guarda()
    {
        if ($this->ID !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }

    private static function actualiza($pueblo)
    {
        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf("UPDATE pueblo SET `c.a.`='%s', nombre='%s', servicios='%s', password='%s' WHERE ID=%d",
            $conn->real_escape_string($pueblo->c_a),
            $conn->real_escape_string($pueblo->nombre),
            $conn->real_escape_string($pueblo->servicios),
            $conn->real_escape_string($pueblo->password),
            $pueblo->ID
        );
        if ($conn->query($query)) {
            return $pueblo;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    // Métodos para acceder y modificar propiedades si es necesario
    // ...
}
