<?php
class Categoria {
    private $conexion;
    public function __construct($conexion) {
        $this->conexion = $conexion;}
    public function listar() {
        $sql = "SELECT * FROM categorias";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);}
    public function registrar($nombre, $descripcion) {
        $sql = "INSERT INTO categorias (nombre, descripcion, estado)
                VALUES (:nombre, :descripcion, 'Activa')";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam(':descripcion', $descripcion);
        return $consulta->execute();}}?>