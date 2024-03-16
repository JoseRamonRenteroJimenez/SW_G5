<?php

/**
 * Gestión de conexiones a la base de datos.
 */
class BD {
    private static $instancia = null;
    private $conexion;

    /**
     * Obtiene la única instancia de la clase BD.
     *
     * @return BD La instancia singleton de BD.
     */
    public static function getInstance(): BD {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }

        return self::$instancia;
    }

    /**
     * Constructor privado para prevenir la creación de instancias desde fuera de la clase.
     */
    private function __construct() {
        $this->inicializaConexion();
    }

    /**
     * Inicializa la conexión a la base de datos.
     */
    private function inicializaConexion(): void {
        $this->conexion = new mysqli(BD_HOST, BD_USER, BD_PASS, BD_NAME, BD_PORT);

        if ($this->conexion->connect_errno) {
            error_log("Error de conexión a la BD: ({$this->conexion->connect_errno}) {$this->conexion->connect_error}");
        }

        if (!$this->conexion->set_charset("utf8mb4")) {
            error_log("Error al configurar la codificación de la BD: ({$this->conexion->errno}) {$this->conexion->error}");
        }

        // Garantiza que cierraConexion() se llame automáticamente al finalizar el script.
        register_shutdown_function([$this, 'cierraConexion']);
    }

    /**
     * Devuelve la conexión activa a la base de datos.
     *
     * @return mysqli La conexión a la base de datos.
     */
    public function getConexionBd(): mysqli {
        return $this->conexion;
    }

    /**
     * Cierra la conexión a la base de datos si está abierta.
     */
    private function cierraConexion(): void {
        if ($this->conexion !== null) {
            $this->conexion->close();
        }
    }
}
