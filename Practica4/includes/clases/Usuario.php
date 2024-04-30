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

    private function __construct($nombreUsuario, $password, $nombre, $rol, $id = null)
    {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->password = $password;
        $this->nombre = $nombre;
        $this->rol = $rol;
    }

    public static function login($nombreUsuario, $password)
    {
        $usuario = self::buscaUsuario($nombreUsuario);
        if ($usuario && $usuario->compruebaPassword($password)) {
            return $usuario; // El usuario es retornado directamente sin necesidad de cargar roles. 
        }
        return false;
    }
    
    public static function crea($nombreUsuario, $password, $nombre, $rol)
    {
        // Verificar si ya existe un usuario con el mismo nombreUsuario
        if (self::buscaUsuario($nombreUsuario) != false) {
            error_log("Usuario ya existe");
            return 0; // Retorna 0 para indicar que el usuario ya existe
        }
        //Creamos e introducimos el usuario a la base de datos
        $user = new Usuario($nombreUsuario, self::hashPassword($password), $nombre, $rol);
        self::inserta($user);
        return $user; // Retorna el objeto Usuario, no solo el ID
    }

    public static function buscaUsuario($nombreUsuario) // Busca un usuario por su nombre de usuario
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios U WHERE U.nombreUsuario='%s'", $conn->real_escape_string($nombreUsuario));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Usuario($fila['nombreUsuario'], $fila['password'], $fila['nombre'], $fila['rol'], $fila['id']);
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
                $result = new Usuario($fila['nombreUsuario'], $fila['password'], $fila['nombre'], $fila['rol'], $fila['id']);
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
   
    private static function inserta($usuario) // Inserta un usuario en la base de datos
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO usuarios(nombreUsuario, nombre, password, rol) VALUES ('%s', '%s', '%s', %d)"
            , $conn->real_escape_string($usuario->nombreUsuario)
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->password)
            , $usuario->rol
        );
        if ( $conn->query($query) ) {
            $usuario->id = $conn->insert_id;
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }
    
    private static function actualiza($usuario) // Actualiza los datos del usuario en la base de datos
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE usuarios U SET nombreUsuario = '%s', nombre='%s', password='%s', rol=%d WHERE U.id=%d"
            , $conn->real_escape_string($usuario->nombreUsuario)
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->password)
            , $usuario->rol
            , $usuario->id
        );
        if ( $conn->query($query) ) {
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;
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
