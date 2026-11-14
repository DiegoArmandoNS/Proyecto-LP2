<?php
require_once "../app/config/Conn.php";

class MovimientoInventario
{
    public function registrar($idInsumo, $idUsuario, $idPedido, $tipo, $cantidad, $motivo)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('INSERT INTO movimientos_inventario (id_insumo, id_usuario, id_pedido, tipo, cantidad, motivo, fecha_movimiento) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute(array($idInsumo, $idUsuario, $idPedido, $tipo, $cantidad, $motivo, date('Y-m-d H:i:s')));
        $nuevoId = (int) $conexion->lastInsertId();
        $conn->cerrar();
        return $nuevoId;
    }

    public function listar($idInsumo = 0, $tipo = '', $fecha = '')
    {
        $sql = 'SELECT m.*, i.nombre AS insumo, i.unidad_medida, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido
                FROM movimientos_inventario m JOIN insumos i ON i.id_insumo = m.id_insumo JOIN usuarios u ON u.id_usuario = m.id_usuario WHERE 1 = 1';
        $parametros = array();
        if ($idInsumo > 0) {
            $sql .= ' AND m.id_insumo = ?';
            $parametros[] = $idInsumo;
        }
        if ($tipo !== '') {
            $sql .= ' AND m.tipo = ?';
            $parametros[] = $tipo;
        }
        if ($fecha !== '') {
            $sql .= ' AND DATE(m.fecha_movimiento) = ?';
            $parametros[] = $fecha;
        }
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare($sql . ' ORDER BY m.fecha_movimiento DESC, m.id_movimiento DESC LIMIT 200');
        $stmt->execute($parametros);
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function resumenDelDia($fecha)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare("SELECT i.nombre, i.unidad_medida,
                    SUM(CASE WHEN m.tipo = 'ingreso' AND m.id_pedido IS NULL THEN m.cantidad ELSE 0 END) AS ingresos,
                    SUM(CASE WHEN m.tipo = 'salida' THEN m.cantidad WHEN m.tipo = 'ingreso' AND m.id_pedido IS NOT NULL THEN -m.cantidad ELSE 0 END) AS consumo,
                    SUM(CASE WHEN m.tipo = 'merma' THEN m.cantidad ELSE 0 END) AS mermas
             FROM movimientos_inventario m JOIN insumos i ON i.id_insumo = m.id_insumo
             WHERE DATE(m.fecha_movimiento) = ? GROUP BY i.id_insumo, i.nombre, i.unidad_medida ORDER BY i.nombre");
        $stmt->execute(array($fecha));
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function registrarEn($conexion, $idInsumo, $idUsuario, $idPedido, $tipo, $cantidad, $motivo)
    {
        $stmt = $conexion->prepare('INSERT INTO movimientos_inventario (id_insumo, id_usuario, id_pedido, tipo, cantidad, motivo, fecha_movimiento) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute(array($idInsumo, $idUsuario, $idPedido, $tipo, $cantidad, $motivo, date('Y-m-d H:i:s')));
    }
}
