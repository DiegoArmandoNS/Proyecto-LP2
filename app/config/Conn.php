<?php
class Conn
{
    private $dsn;
    private $usuario;
    private $pass;
    private $conexion;

    public function __construct()
    {
        $this->dsn = "mysql:host=localhost;dbname=restaurante_l;charset=utf8";
        $this->usuario = "root";
        $this->pass = "";
    }

    public function conectar()
    {
        $this->conexion = new PDO($this->dsn, $this->usuario, $this->pass);
        $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $this->conexion;
    }

    public function cerrar()
    {
        $this->conexion = NULL;
    }
}
