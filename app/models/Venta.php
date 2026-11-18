<?php
require_once 'app/config/Conn.php';

class Venta
{
    public function buscar($id)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT v.* , pg.metodo_pago, pg.monto_recibido, pg.vuelto, pe.id_mesa, m.numero_mesa, u.nombre AS cajero_nombre, U.apellido AS cajero_apellido
                                FROM ventas v
                                JOIN pagos pg ON pg.id_pago = v.id_pago JOIN pedidos pe ON pe.id_pedido = v.id_pedido
                                JOIN mesas m ON pe.id_mesa = m.id_mesa JOIN usuarios u ON pg.id_usuario = u.id_usuario
                                WHERE v.id_venta = ?');
        $stmt->execute(array($id));
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn->cerrar();
        return $fila ?: null;
    }

    public function listarPorFecha($fecha)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT v.id_venta, v.id_pedido, v.numero_comprobante, v.total, v.fecha_venta, pg.metodo_pago, m.numero_mesa
                                FROM ventas v JOIN pagos pg ON pg.id_pago = v.id_pago JOIN pedidos pe ON pe.id_pedido = v.id_pedido
                                JOIN mesas m ON m.id_mesa = pe.id_mesa
                                WHERE DATE(v.fecha_venta) = ? ORDER BY v.fecha_venta');
        $stmt->execute(array($fecha));
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function platosMasVendidos($fecha)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT pl.nombre, SUM(d.cantidad) AS cantidad, SUM(d.subtotal) AS importe
                                FROM ventas v JOIN detalle_pedidos d ON d.id_pedido = v.id_pedido JOIN platos pl ON pl.id_plato = d.id_plato
                                WHERE DATE(v.fecha_venta) = ? GROUP BY pl.id_plato, pl.nombre ORDER BY cantidad DESC, 
                                importe DESC LIMIT 10');
        $stmt->execute(array($fecha));
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function totalesPorMetodo($fecha) 
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT pg.metodo_pago, COUNT(*) AS ventas, SUM(v.total) AS total
                                FROM ventas v JOIN pagos pg ON pg.id_pago = v.id_pago
                                WHERE DATE(v.fecha_venta) = ? GROUP BY pg.metodo_pago');
        $stmt->execute(array($fecha));
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function totalPorCajeros($fecha)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT u.nombre, u.apellido, COUNT(*) AS ventas, SUM(v.total) AS total
                FROM vemtas v JOIN pagos pg ON pg.id_pago = v.id_pago JOIN usuarios u ON u.id_usuario = pg.id_usuario
                WHERE DATE(v.fecha_venta) = ? GROUP BY u.id_usuario, u.apellido ORDER BY total DESC');
        $stmt->execute(array($fecha));
        $resultado = $stmt->fetchALL();
        $conn->cerrar();
        return $resultado;
    }

    public function resumenDelDia($fecha) 
    {
        $ventas = $this->listarPorFecha($fecha);
        $total = 0;
        foreach ($ventas as $v) {
            $total += $v['total'];
        }
        $metodos = array();
        foreach (listaMetodos() as $clave => $nombre) {
            $metodos[$clave] = array('nombre' => $nombre, 'ventas' => 0, 'total' => 0);
        }
        foreach ($this->totalesPorMetodo($fecha) as $fila) {
            $metodos[$fila['metodo_pago']]['ventas'] = $fila['ventas'];
            $metodos[$fila['metodo_pago']]['total'] = $fila['total'];
        }
        $cantidad = count($ventas);
        return array('ventas' => round($total, 2), 'cantidad' => $cantidad > 0 ? round($total / $cantidad, 2) : 0,
                     'platos' => $this->platosMasVendidos($fecha), 'metodos' => $metodos);
    }
}