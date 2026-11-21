<?php
require_once "../app/config/Conn.php";
require_once "../app/models/Mesa.php";

class Pago
{

    public function cobrar($idPedido, $idCajero, $idMesa, $total, $recibido, $vuelto, $metodo)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $mesa = new Mesa();
        $conexion->beginTransaction();
        try {
            $stmt = $conexion->prepare("UPDATE pedidos SET estado = 'pagado' WHERE id_pedido = ? AND estado = 'listo'");
            $stmt->execute(array($idPedido));
            if ($stmt->rowCount() === 0) {
                throw new Exception('El pedido no existe, no está listo o ya fue pagado.');
            }
            $stmt = $conexion->prepare('INSERT INTO pagos (id_pedido, id_usuario, monto, monto_recibido, vuelto, metodo_pago, fecha_pago) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute(array($idPedido, $idCajero, $total, $recibido, $vuelto, $metodo, date('Y-m-d H:i:s')));
            $idPago = (int) $conexion->lastInsertId();
            $stmt = $conexion->prepare('SELECT COALESCE(MAX(id_venta), 0) + 1 AS siguiente FROM ventas');
            $stmt->execute();
            $fila = $stmt->fetch();
            $numero = 'C001-' . str_pad($fila['siguiente'], 8, '0', STR_PAD_LEFT);
            $stmt = $conexion->prepare('INSERT INTO ventas (id_pedido, id_pago, numero_comprobante, total, fecha_venta) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute(array($idPedido, $idPago, $numero, $total, date('Y-m-d H:i:s')));
            $idVenta = (int) $conexion->lastInsertId();
            $mesa->actualizarEstado($conexion, $idMesa);
            $conexion->commit();
            $conn->cerrar();
            return $idVenta;
        } catch (PDOException $e) {
            $conexion->rollBack();
            $conn->cerrar();
            throw new Exception('No se pudo completar la operación. Intenta de nuevo.');
        } catch (Exception $e) {
            $conexion->rollBack();
            $conn->cerrar();
            throw $e;
        }
    }
}
