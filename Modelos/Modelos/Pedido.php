<?php

require_once __DIR__ . '/Conexion.php';


class Pedido
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Conexion::getConexion();
    }

    public function crear($idMesa, $idUsuario, $observaciones)
    {
        $sql = "INSERT INTO ` Pedidos`
                (fecha_pedido, estado, observaciones, idMesas, idUsuarios)
                VALUES (NOW(), 'Pendiente', ?, ?, ?)";

        $resultado = $this->db->prepare($sql);
        $resultado->execute([$observaciones, $idMesa, $idUsuario]);

        return $this->db->lastInsertId();
    }

    public function obtenerPorId($id, $bloquear = false)
    {
        $sql = "SELECT * FROM ` Pedidos`
                WHERE `id pedidos` = ?";

        if ($bloquear) {
            $sql .= " FOR UPDATE";
        }

        $resultado = $this->db->prepare($sql);
        $resultado->execute([$id]);

        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function mostrar($estado = null, $idUsuario = null)
    {
        $sql = "SELECT * FROM ` Pedidos` WHERE 1 = 1";
        $parametros = [];

        if ($estado !== null) {
            $sql .= " AND estado = ?";
            $parametros[] = $estado;
        }

        if ($idUsuario !== null) {
            $sql .= " AND idUsuarios = ?";
            $parametros[] = $idUsuario;
        }

        $sql .= " ORDER BY fecha_pedido DESC";

        $resultado = $this->db->prepare($sql);
        $resultado->execute($parametros);

        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarParaCocina()
    {
        $sql = "SELECT * FROM ` Pedidos`
                WHERE estado IN ('Pendiente', 'En preparación')
                ORDER BY fecha_pedido ASC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstado($id, $estado, $observaciones = null)
    {
        if ($observaciones === null) {
            $sql = "UPDATE ` Pedidos`
                    SET estado = ?
                    WHERE `id pedidos` = ?";

            $resultado = $this->db->prepare($sql);
            return $resultado->execute([$estado, $id]);
        }

        $sql = "UPDATE ` Pedidos`
                SET estado = ?, observaciones = ?
                WHERE `id pedidos` = ?";

        $resultado = $this->db->prepare($sql);
        return $resultado->execute([$estado, $observaciones, $id]);
    }

    public function contarActivosPorMesa($idMesa)
    {
        $sql = "SELECT COUNT(*) FROM ` Pedidos`
                WHERE idMesas = ?
                AND estado IN ('Pendiente', 'En preparación', 'Listo')";

        $resultado = $this->db->prepare($sql);
        $resultado->execute([$idMesa]);

        return (int) $resultado->fetchColumn();
    }
}