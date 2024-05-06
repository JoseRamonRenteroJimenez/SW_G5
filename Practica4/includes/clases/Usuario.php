<?php
namespace es\ucm\fdi\aw;

class Usuario
{
    public const ADMIN_ROLE = 1;
    public const EMPRESA_ROLE = 2;
    public const PUEBLO_ROLE = 3;
    public const VECINO_ROLE = 4;

    private $id;
    private $nombreUsuario;
    private $password;
    private $nombre;
    private $rol;
    private $nombreImg; 

    private function __construct($nombreUsuario, $password, $nombre, $rol, $nombreImg, $id = null)
    {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->password = $password;
        $this->nombre = $nombre;
        $this->rol = $rol;
        $this->nombreImg = $nombreImg; // Se actualiza el constructor para incluir nombreImg
    }

    public static function login($nombreUsuario, $password)
    {
        $usuario = self::buscaUsuario($nombreUsuario);
        if ($usuario && $usuario->compruebaPassword($password)) {
            return $usuario; // El usuario es retornado directamente sin necesidad de cargar roles. 
        }
        return false;
    }

    public static function crea($nombreUsuario, $password, $nombre, $nombreImg, $rol)
    {
        if (self::buscaUsuario($nombreUsuario) != false) {
            error_log("Usuario ya existe");
            return 0;
        }
        $user = new Usuario($nombreUsuario, self::hashPassword($password), $nombre, $rol, $nombreImg);
        self::inserta($user);
        return $user;
    }

    public static function buscaUsuario($nombreUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios WHERE nombreUsuario='%s'", $conn->real_escape_string($nombreUsuario));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Usuario($fila['nombreUsuario'], $fila['password'], $fila['nombre'], $fila['rol'], $fila['nombreImg'], $fila['id']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function buscaPorId($idUsuario) // Busca un usuario por su ID
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios WHERE id=%d", $idUsuario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Usuario($fila['nombreUsuario'], $fila['password'], $fila['nombre'], $fila['rol'], $fila['nombreImg'], $fila['id']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private static function inserta($usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO usuarios(nombreUsuario, nombre, password, rol, nombreImg) VALUES ('%s', '%s', '%s', %d, '%s')"
            , $conn->real_escape_string($usuario->nombreUsuario)
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->password)
            , $usuario->rol
            , $conn->real_escape_string($usuario->nombreImg)
        );
        if ($conn->query($query)) {
            $usuario->id = $conn->insert_id;
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    private static function actualiza($usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE usuarios SET nombreUsuario='%s', nombre='%s', password='%s', rol=%d, nombreImg='%s' WHERE id=%d"
            , $conn->real_escape_string($usuario->nombreUsuario)
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->password)
            , $usuario->rol
            , $conn->real_escape_string($usuario->nombreImg)
            , $usuario->id
        );
        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
    
    
    private static function borra($usuario)
    {
        return self::borraPorId($usuario->id);
    }
    
    private static function borraPorId($idUsuario)
    {
        if (!$idUsuario) {
            return false;
        } 
        
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM usuarios WHERE id = %d", $idUsuario);
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    public function getId() // Devuelve el ID del usuario
    {
        return $this->id;
    }

    public function getNombreUsuario() // Devuelve el nombre de usuario
    {
        return $this->nombreUsuario;
    }

    public function getNombre() // Devuelve el nombre del usuario
    {
        return $this->nombre;
    }

    public function getRol() // Devuelve el rol del usuario
    {
        return $this->rol;
    }

    public function getNombreImg() // Devuelve el nombre de la imagen del usuario
    {
        return $this->nombreImg;
    }

    public function setNombreUsuario($nombreUsuario) {
        $this->nombreUsuario = $nombreUsuario;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function compruebaPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function cambiaPassword($nuevoPassword)
    {
        $this->password = self::hashPassword($nuevoPassword);
    }
    
    public function guarda() // Guarda el usuario en la base de datos
    {
        if ($this->id !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }
    
    public function borrate() // Borra el usuario de la base de datos
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }

    public static function eliminarUsuario($idUsuario) {
        return self::borraPorId($idUsuario);
    }
}
?>
