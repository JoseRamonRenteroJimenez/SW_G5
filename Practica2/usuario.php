<?php

require_once __DIR__.'\BD.php';

class User {
    private $id;
    private $email;
    private $password;
    private $role;

    public function __construct($id = null, $email, $password, $role) {
        $this->id = $id;
        $this->email = $email;
        $this->password = self::hashPassword($password); // Contraseña hasheada para almacenarla en la BD
        $this->role = $role;
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function hasRole($role) {
        return ($this->role === $role) ? true : false;
    }
    

    public static function findUserByEmail($email) {
        // Obtener la conexión a la base de datos
        $conn = BD::getInstance()->getConexionBd();
    
        // Escapar el email para prevenir inyecciones SQL. Las operaciones de base de datos no escapan ($conn->real_escape_string()) los parámetros del usuario.
        $safeEmail = $conn->real_escape_string($email);

        // Construir la consulta SQL
        $query = sprintf("SELECT * FROM User U WHERE U.email='%s'", $safeEmail);
    
        // Ejecutar la consulta en la base de datos.
        $rs = $conn->query($query);
        $user = false; // Inicializar la variable $user como false para manejar el caso en que no se encuentre el usuario.
    
        if ($rs) {
            // Si la consulta es exitosa, intentar obtener la fila de resultados como un array asociativo.
            $row = $rs->fetch_assoc();
            if ($row) {
                // Si se encuentra una fila, instanciar un nuevo objeto User con los datos obtenidos.
                $user = new User($row['u_id'], $row['email'], $row['password'], $row['role']);
            }
            // Liberar el conjunto de resultados.
            $rs->free();
        } else {
            // Si hay un error con la consulta, registrar este error en el log.
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        // Retornar el objeto User si se encontró, o false en caso contrario.
        return $user;
    }
    
    

    public static function login($email, $password) {
        $user = self::findUserByEmail($email);
        
        return ($user && $user->verifyPassword($password)) ? $user : false;
    }

    private static function hashPassword($password) {
        $options = [
            'cost' => 11
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
   
    private static function insertUser($user) {
        $auxUser = User::findUserByEmail($user->getEmail());

        if(!$auxUser) {
            $result = false;
            $conn = BD::getInstance()->getConexionBd();
            $query= sprintf("insert into User(email, password, role) VALUES ('%s', '%s', '%s')"
                , $conn->real_escape_string($user->email)
                , $conn->real_escape_string($user->password)
                , $conn->real_escape_string($user->role)
            );

            if ( ($result = $conn->query($query)) )
                $user->id = $conn->insert_id;
            else
                error_log("Error BD ({$conn->errno}): {$conn->error}");
            
            return $result;
        } else
            return false;        
    } 
    
    private static function update($user) {
        $conn = BD::getInstance()->getConexionBd();
        
        // Real escape string para evitar inyecciones 
        $email = $conn->real_escape_string($user->getEmail());
        $password = $conn->real_escape_string($user->password); 
        $role = $conn->real_escape_string($user->getRole());
        $id = $user->getId(); // No necesita ser escapado porque se espera que sea un entero.
    
        // Construir la consulta SQL de actualización usando los valores escapados.
        $query = "UPDATE User SET email = '$email', password = '$password', role = '$role' WHERE u_id = $id";
    
        // Ejecuta la consulta.
        if (!$conn->query($query)) {
            // Registrar en el log si hay un error en la ejecución de la consulta.
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            //  Lanzar una excepción para manejar el error fuera de esta función.
            throw new Exception("Error al actualizar el usuario: {$conn->error}");
        } else if ($conn->affected_rows === 0) {
            // Caso en que la consulta se ejecuta pero no actualiza ningún registro.
            error_log("No se actualizó ningún registro para el usuario con ID: $id");
        }
    
        // Retornar true si se afectó algún registro 
        return $conn->affected_rows > 0;
    }
    

    public function changePassword($newPassword) {
        // Hash de la nueva contraseña.
        $hashedPassword = self::hashPassword($newPassword);
        if ($hashedPassword === false) {
            throw new Exception("Error al hashear la contraseña.");
        }
        
        // Actualizar la propiedad 'password' del usuario con el nuevo hash de la contraseña.
        $this->password = $hashedPassword;
        // Añadir una llamada a un método de actualización en la base de datos 
        return $this->save(); // Save() maneja la actualización en la base de datos.
    }
    
    
    public static function createUser($email, $password, $role) {
        // Validar el formato del email.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del correo electrónico es inválido.");
        }
    
        // Validar la longitud de la contraseña.
        if (strlen($password) < 8) {
            throw new Exception("La contraseña debe tener al menos 8 caracteres.");
        }
    
        // Validar el rol. Asegurarse de que el rol sea uno de los tres roles válidos.
        $validRoles = ['pueblo', 'empresa', 'admin']; // Roles específicos definidos.
        if (!in_array($role, $validRoles)) {
            throw new Exception("El rol especificado es inválido. Los roles válidos son 'pueblo', 'empresa', y 'admin'.");
        }
    
        // Verificar si ya existe un usuario con el mismo email.
        if (self::findUserByEmail($email) !== false) {
            throw new Exception("Ya existe un usuario con ese email.");
        }
        
        // Crear un nuevo usuario con la contraseña hasheada.
        $user = new User(null, $email, self::hashPassword($password), $role);
    
        // Intentar guardar el nuevo usuario en la base de datos.
        if (!$user->save()) {
            // Si la operación de guardar falla, lanza una excepción.
            throw new Exception("Error al guardar el usuario.");
        }
    
        // Retornar true para indicar que el usuario fue creado exitosamente.
        return true;
    }
    
    
    

    public static function updateUser($user, $newPassword = null) {
        $conn = BD::getInstance()->getConexionBd();
    
        // Validar el formato del email.
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del correo electrónico es inválido.");
        }
    
        // Validar el rol. Asegurarse de que el rol sea uno de los tres roles válidos.
        $validRoles = ['pueblo', 'empresa', 'admin']; // Roles específicos definidos.
        if (!in_array($user->role, $validRoles)) {
            throw new Exception("El rol especificado es inválido. Los roles válidos son 'pueblo', 'empresa', y 'admin'.");
        }
    
        // Preparar datos para la actualización.
        $email = $conn->real_escape_string($user->email);
        $role = $conn->real_escape_string($user->role);
        $id = $user->id;
    
        // La actualización de la contraseña es opcional.
        if ($newPassword !== null) {
            if (strlen($newPassword) < 8) {
                throw new Exception("La nueva contraseña debe tener al menos 8 caracteres.");
            }
            $hashedPassword = self::hashPassword($newPassword);
        } else {
            // Si no se proporciona una nueva contraseña, usa la existente sin modificar.
            $hashedPassword = $user->password;
        }
        $hashedPassword = $conn->real_escape_string($hashedPassword);
    
        // Construir la consulta SQL utilizando los valores escapados.
        $query = "UPDATE User SET email = '$email', password = '$hashedPassword', role = '$role' WHERE u_id = $id";
    
        // Ejecutar la consulta.
        if (!$conn->query($query)) {
            // Registrar el error si la consulta falla.
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    
        return true;
    }
    
    
    //Borrar usuario a nivel alto
    private static function deleteCurrentUser($user) {
        if (!isset($user->id)) {
            //Manejar el caso de un ID no válido o lanzar una excepción.
            throw new Exception("El usuario no tiene un ID válido.");
        }
    
        $result = self::deleteUserById($user->id);
        
        if (!$result) {
            // Manejar el fallo en la eliminación.
            error_log("No se pudo eliminar el usuario con ID: {$user->id}");
            return false;
        }
    
        return true;
    }
    
    
    private static function deleteUserById($idUser) {
        // Verifica que el ID del usuario sea un valor válido.
        if (!$idUser) {
            return false;
        }
        
        // Conexión a la base de datos.
        $conn = BD::getInstance()->getConexionBd();
    
        // Escapa el valor del ID del usuario para prevenir inyecciones SQL.
        $safeId = $conn->real_escape_string($idUser);
    
        // Construye la consulta SQL utilizando el valor escapado.
        $query = "DELETE FROM User WHERE u_id = $safeId";
    
        // Ejecuta la consulta.
        if (!$conn->query($query)) {
            // Si la ejecución falla, registra el error y retorna false.
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    
        // Retorna true para indicar que el usuario fue eliminado con éxito.
        return true;
    }
    
//Propia eliminacion de usuario
    public function delete() {
        return ($this->id !== null) ? self::deleteUser($this) : false;
    }
    
}


//echar un ojo a delete user y delete current user