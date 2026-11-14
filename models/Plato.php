<?php
require_once "../app/config/Conn.php";
class Plato
{
    public function listar($soloDisponibles = false)
    {
        $sql = 'SELECT p.*, c.nombre AS categoria FROM platos p JOIN categorias c ON c.id_categoria = p.id_categoria';
        if ($soloDisponibles) {
            $sql .= ' WHERE p.disponible = 1 AND c.estado = 1';
        }
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare($sql . ' ORDER BY c.nombre, p.nombre');
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;}
    public function buscar($id)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT * FROM platos WHERE id_plato = ?');
        $stmt->execute(array($id));
        $fila = $stmt->fetch();
        $conn->cerrar();
        return $fila ? $fila : null;}
    public function crear($d)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('INSERT INTO platos (id_categoria, nombre, descripcion, precio, disponible) VALUES (?, ?, ?, ?, 1)');
        $stmt->execute(array($d['id_categoria'], $d['nombre'], $d['descripcion'], $d['precio']));
        $nuevoId = (int) $conexion->lastInsertId();
        $conn->cerrar();
        return $nuevoId;}
    public function actualizar($id, $d)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('UPDATE platos SET id_categoria = ?, nombre = ?, descripcion = ?, precio = ? WHERE id_plato = ?');
        $stmt->execute(array($d['id_categoria'], $d['nombre'], $d['descripcion'], $d['precio'], $id));
        $conn->cerrar();}
    public function cambiarDisponible($id, $disponible)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('UPDATE platos SET disponible = ? WHERE id_plato = ?');
        $stmt->execute(array($disponible, $id));
        $conn->cerrar();}}
