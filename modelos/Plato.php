<?php
class Plato {
    private $conexion;
    public function __construct($conexion) {
        $this->conexion = $conexion;}
    public function listar() {
        $sql = "SELECT * FROM platos";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);}
    public function registrar($id_categoria, $nombre, $descripcion, $precio) {
        $sql = "INSERT INTO platos
                (idcategorias, nombre, descripcion, precio, disponible)
                VALUES (:id_categoria, :nombre, :descripcion, :precio, '1')";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':id_categoria', $id_categoria);
        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam(':descripcion', $descripcion);
        $consulta->bindParam(':precio', $precio);
        return $consulta->execute();}
    public function actualizar($id_plato, $id_categoria, $nombre, $descripcion, $precio) {
        $sql = "UPDATE platos
                SET idcategorias = :id_categoria,
                    nombre = :nombre,
                    descripcion = :descripcion,
                    precio = :precio
                WHERE idplatos = :id_plato";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':id_plato', $id_plato);
        $consulta->bindParam(':id_categoria', $id_categoria);
        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam(':descripcion', $descripcion);
        $consulta->bindParam(':precio', $precio);
        return $consulta->execute();}
    public function cambiarDisponibilidad($id_plato, $disponible) {
        $sql = "UPDATE platos
                SET disponible = :disponible
                WHERE idplatos = :id_plato";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':disponible', $disponible);
        $consulta->bindParam(':id_plato', $id_plato);
        return $consulta->execute(); }}?>