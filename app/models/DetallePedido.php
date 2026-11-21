<?php
require_once "../app/config/Conn.php";

class DetallePedido
{
    public function porPedido($idPedido)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT d.*, p.nombre AS plato FROM detalle_pedidos d JOIN platos p ON p.id_plato = d.id_plato
             WHERE d.id_pedido = ? ORDER BY d.id_detalle');
        $stmt->execute(array($idPedido));
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function total($idPedido)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT COALESCE(SUM(subtotal), 0) AS total FROM detalle_pedidos WHERE id_pedido = ?');
        $stmt->execute(array($idPedido));
        $fila = $stmt->fetch();
        $conn->cerrar();
        $fila = $fila ? $fila : null;
        return round((float) $fila['total'], 2);
    }
}
