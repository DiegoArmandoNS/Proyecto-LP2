<?php

class Conexion
{
    private string $dsn;
    private string $username;
    private string $password;
    private array $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    private ?PDO $conexion = null;


    private static ?Conexion $instancia = null;

    public function __construct(
        string $host = "localhost",
        string $dbname = "restauranteL_db",   
        string $username = "root",
        string $password = ""
    ) {
        $this->dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $this->username = $username;
        $this->password = $password;
    }

    public function iniciar(): PDO
    {
        try {
            if ($this->conexion === null) {
                $this->conexion = new PDO(
                    $this->dsn,
                    $this->username,
                    $this->password,
                    $this->options
                );
            }
            return $this->conexion;
        } catch (PDOException $e) {
            throw new Exception("Error en la conexión a la base de datos: " . $e->getMessage());
        }
    }

    // Método que usan Pedido y DetallePedido
    public static function getConexion(): PDO
    {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia->iniciar();
    }

    public function terminar(): void
    {
        $this->conexion = null;
    }
}