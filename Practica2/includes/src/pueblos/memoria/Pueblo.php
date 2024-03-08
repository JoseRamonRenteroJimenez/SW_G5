<?php

class Pueblo
{
    const BD_SESS_KEY = 'PUEBLOS_BD';

    static function init()
    {
        $bd = $_SESSION[self::BD_SESS_KEY] ?? null;
        if ($bd == null) {
            $bd = [
                1 => new Pueblo('Andalucía', 'Pueblo4', '{"servicio1": "value1"}', 'contraseña_pueblo_1'),
                2 => new Pueblo('Aragón', 'Pueblo5', '{"servicio2": "value2"}', 'contraseña_pueblo_2'),
                // Agrega más instancias de Pueblo según sea necesario
            ];
            $_SESSION[self::BD_SESS_KEY] = $bd;
        }
    }

    use MagicProperties;

    public static function getAllPueblos()
    {
        return $_SESSION[self::BD_SESS_KEY] ?? [];
    }

    public static function getPuebloById($id)
    {
        return $_SESSION[self::BD_SESS_KEY][$id] ?? null;
    }

    public static function getPuebloByNombre($nombre)
    {
        $result = $_SESSION[self::BD_SESS_KEY][$nombre] ?? false;
        return $result;
    }

    private static function inserta($pueblo)
    {
        $pueblo->id = count($_SESSION[self::BD_SESS_KEY]) + 1;
        $_SESSION[self::BD_SESS_KEY][$pueblo->id] = $pueblo;
        return $pueblo;
    }

    private static function actualiza($pueblo)
    {
        $_SESSION[self::BD_SESS_KEY][$pueblo->id] = $pueblo;
        return $pueblo;
    }

    private static function borraPueblo($pueblo)
    {
        return self::borraPorId($pueblo->id);
    }

    private static function borraPorId($id)
    {
        if (isset($_SESSION[self::BD_SESS_KEY][$id])) {
            unset($_SESSION[self::BD_SESS_KEY][$id]);
            return true;
        }
        return false;
    }

    private $id;
    private $c_a;
    private $nombre;
    private $servicios;
    private $password;

    private function __construct($c_a, $nombre, $servicios, $password, $id = null)
    {
        $this->id = $id;
        $this->c_a = $c_a;
        $this->nombre = $nombre;
        $this->servicios = $servicios;
        $this->password = $password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getC_A()
    {
        return $this->c_a;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getServicios()
    {
        return $this->servicios;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function guarda()
    {
        if ($this->id !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }

    public function borra()
    {
        if ($this->id !== null) {
            return self::borraPueblo($this);
        }
        return false;
    }
}
