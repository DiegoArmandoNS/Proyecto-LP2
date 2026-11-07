<?php

class DetallePedido
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    public function agregar($idPedido, $idPlato, $cantidad, $precio, $observaciones = '')
    {
        $subtotal = $cantidad * $precio;

        $sql = "INSERT INTO Detalle_pedidos
                (cantidad, precio_unitario, subtotal,
                observaciones, id pedidos, idplatos)
                VALUES (?, ?, ?, ?, ?, ?)";

        $resultado = $this->db->prepare($sql);
        $resultado->execute([
            $cantidad,
            $precio,
            $subtotal,
            $observaciones,
            $idPedido,
            $idPlato
        ]);

        return $this->db->lastInsertId();
    }

    public function listarPorPedido($idPedido)
    {
        $sql = "SELECT * FROM Detalle_pedidos
                WHERE id pedidos = ?";

        $resultado = $this->db->prepare($sql);
        $resultado->execute([$idPedido]);

        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorPedidos($idsPedidos)
    {
        if (empty($idsPedidos)) {
            return [];
        }

        $marcadores = implode(',', array_fill(0, count($idsPedidos), '?'));

        $sql = "SELECT * FROM Detalle_pedidos
                WHERE id pedidos IN ($marcadores)";

        $resultado = $this->db->prepare($sql);
        $resultado->execute($idsPedidos);

        $datos = [];

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $datos[$fila['id pedidos']][] = $fila;
        }

        return $datos;
    }

    public function totalPorPedido($idPedido)
    {
        $sql = "SELECT SUM(subtotal) FROM Detalle_pedidos
                WHERE id pedidos = ?";

        $resultado = $this->db->prepare($sql);
        $resultado->execute([$idPedido]);

        return $resultado->fetchColumn();
    }
}