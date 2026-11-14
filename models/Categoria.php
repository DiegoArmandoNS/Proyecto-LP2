<?php
require_once "../app/config/Conn.php";
class Categoria
{
    public function listar($soloActivas = false)
    {
        $sql = 'SELECT * FROM categorias';
        if ($soloActivas) {
            $sql .= ' WHERE estado = 1';
        }
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare($sql . ' ORDER BY nombre');
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;}
    public function buscar($id)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT * FROM categorias WHERE id_categoria = ?');
        $stmt->execute(array($id));
        $fila = $stmt->fetch();
        $conn->cerrar();
        return $fila ? $fila : null;}
    public function crear($nombre, $descripcion)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('INSERT INTO categorias (nombre, descripcion, estado) VALUES (?, ?, 1)');
        $stmt->execute(array($nombre, $descripcion));
        $nuevoId = (int) $conexion->lastInsertId();
        $conn->cerrar();
        return $nuevoId;}
    public function actualizar($id, $nombre, $descripcion)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('UPDATE categorias SET nombre = ?, descripcion = ? WHERE id_categoria = ?');
        $stmt->execute(array($nombre, $descripcion, $id));
        $conn->cerrar();}
    public function cambiarEstado($id, $estado)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('UPDATE categorias SET estado = ? WHERE id_categoria = ?');
        $stmt->execute(array($estado, $id));
        $conn->cerrar();}}