<?php
require_once "../app/config/Conn.php";
require_once "../app/models/Mesa.php";
require_once "../app/models/MovimientoInventario.php";

class Pedido
{
    public function buscar($id)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT p.*, m.numero_mesa, u.nombre AS mesero_nombre, u.apellido AS mesero_apellido
             FROM pedidos p JOIN mesas m ON m.id_mesa = p.id_mesa JOIN usuarios u ON u.id_usuario = p.id_usuario
             WHERE p.id_pedido = ?');
        $stmt->execute(array($id));
        $fila = $stmt->fetch();
        $conn->cerrar();
        return $fila ? $fila : null;
    }

    public function listar($estados = array(), $idUsuario = 0, $fecha = '')
    {
        $sql = 'SELECT p.*, m.numero_mesa, u.nombre AS mesero_nombre, u.apellido AS mesero_apellido
                FROM pedidos p JOIN mesas m ON m.id_mesa = p.id_mesa JOIN usuarios u ON u.id_usuario = p.id_usuario WHERE 1 = 1';
        $parametros = array();
        if (count($estados) > 0) {
            $marcas = array();
            foreach ($estados as $estado) {
                $marcas[] = '?';
                $parametros[] = $estado;
            }
            $sql .= ' AND p.estado IN (' . implode(',', $marcas) . ')';
        }
        if ($idUsuario > 0) {
            $sql .= ' AND p.id_usuario = ?';
            $parametros[] = $idUsuario;
        }
        if ($fecha !== '') {
            $sql .= ' AND DATE(p.fecha_pedido) = ?';
            $parametros[] = $fecha;
        }
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare($sql . ' ORDER BY p.fecha_pedido DESC');
        $stmt->execute($parametros);
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function paraCocina()
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare("SELECT p.*, m.numero_mesa FROM pedidos p JOIN mesas m ON m.id_mesa = p.id_mesa
             WHERE p.estado IN ('pendiente', 'en_preparacion') ORDER BY p.fecha_pedido ASC");
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function paraCobrar()
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare("SELECT p.*, m.numero_mesa, u.nombre AS mesero_nombre, u.apellido AS mesero_apellido
             FROM pedidos p JOIN mesas m ON m.id_mesa = p.id_mesa JOIN usuarios u ON u.id_usuario = p.id_usuario
             WHERE p.estado = 'listo' ORDER BY p.fecha_pedido ASC");
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function registrarCompleto($idMesa, $idUsuario, $observaciones, $items, $consumo)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $mesa = new Mesa();
        $conexion->beginTransaction();
        try {
            $this->verificarStock($conexion, $consumo);
            $stmt = $conexion->prepare('INSERT INTO pedidos (id_mesa, id_usuario, fecha_pedido, estado, observaciones) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute(array($idMesa, $idUsuario, date('Y-m-d H:i:s'), 'pendiente', $observaciones));
            $idPedido = (int) $conexion->lastInsertId();
            $this->guardarDetalles($conexion, $idPedido, $items);
            $this->descontarInsumos($conexion, $idPedido, $idUsuario, $consumo);
            $mesa->actualizarEstado($conexion, $idMesa);
            $conexion->commit();
            $conn->cerrar();
            return $idPedido;
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

    public function modificarCompleto($idPedido, $idUsuario, $observaciones, $items, $consumo)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $conexion->beginTransaction();
        try {
            $this->reponerInsumos($conexion, $idPedido, $idUsuario, 'Reposición por modificación del pedido #' . $idPedido);
            $this->verificarStock($conexion, $consumo);
            $stmt = $conexion->prepare('DELETE FROM detalle_pedidos WHERE id_pedido = ?');
            $stmt->execute(array($idPedido));
            $this->guardarDetalles($conexion, $idPedido, $items);
            $this->descontarInsumos($conexion, $idPedido, $idUsuario, $consumo);
            $stmt = $conexion->prepare('UPDATE pedidos SET observaciones = ? WHERE id_pedido = ?');
            $stmt->execute(array($observaciones, $idPedido));
            $conexion->commit();
            $conn->cerrar();
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

    public function cancelarCompleto($idPedido, $idUsuario, $idMesa, $motivo)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $mesa = new Mesa();
        $conexion->beginTransaction();
        try {
            $this->reponerInsumos($conexion, $idPedido, $idUsuario, 'Reposición por cancelación del pedido #' . $idPedido);
            $stmt = $conexion->prepare('UPDATE pedidos SET estado = ?, motivo_cancelacion = ? WHERE id_pedido = ?');
            $stmt->execute(array('cancelado', $motivo, $idPedido));
            $mesa->actualizarEstado($conexion, $idMesa);
            $conexion->commit();
            $conn->cerrar();
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

    public function cambiarEstadoCocina($idPedido, $idMesa, $estado)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $mesa = new Mesa();
        $conexion->beginTransaction();
        try {
            $stmt = $conexion->prepare('UPDATE pedidos SET estado = ? WHERE id_pedido = ?');
            $stmt->execute(array($estado, $idPedido));
            $mesa->actualizarEstado($conexion, $idMesa);
            $conexion->commit();
            $conn->cerrar();
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

    private function verificarStock($conexion, $consumo)
    {
        $stmt = $conexion->prepare('SELECT nombre, unidad_medida, stock_actual FROM insumos WHERE id_insumo = ?');
        $faltantes = array();
        foreach ($consumo as $idInsumo => $necesario) {
            $stmt->execute(array($idInsumo));
            $fila = $stmt->fetch();
            if (!$fila || $fila['stock_actual'] + 0.0000001 < $necesario) {
                $nombre = $fila ? $fila['nombre'] : 'Insumo ' . $idInsumo;
                $hay = $fila ? cantidad($fila['stock_actual']) . ' ' . $fila['unidad_medida'] : '0';
                $faltantes[] = $nombre . ' (se necesita ' . cantidad($necesario) . ', hay ' . $hay . ')';
            }
        }
        if (count($faltantes) > 0) {
            throw new Exception('No hay insumos suficientes: ' . implode('; ', $faltantes) . '.');
        }
    }

    private function guardarDetalles($conexion, $idPedido, $items)
    {
        $stmt = $conexion->prepare('INSERT INTO detalle_pedidos (id_pedido, id_plato, cantidad, precio_unitario, subtotal, observaciones) VALUES (?, ?, ?, ?, ?, ?)');
        foreach ($items as $idPlato => $item) {
            $subtotal = round($item['cantidad'] * $item['precio'], 2);
            $stmt->execute(array($idPedido, $idPlato, $item['cantidad'], $item['precio'], $subtotal, $item['observaciones']));
        }
    }

    private function descontarInsumos($conexion, $idPedido, $idUsuario, $consumo)
    {
        $movimiento = new MovimientoInventario();
        $stmt = $conexion->prepare('UPDATE insumos SET stock_actual = stock_actual - ? WHERE id_insumo = ? AND stock_actual >= ?');
        foreach ($consumo as $idInsumo => $cantidad) {
            $stmt->execute(array($cantidad, $idInsumo, $cantidad));
            if ($stmt->rowCount() === 0) {
                throw new Exception('No hay stock suficiente para completar el pedido.');
            }
            $movimiento->registrarEn($conexion, $idInsumo, $idUsuario, $idPedido, 'salida', $cantidad, 'Pedido #' . $idPedido);
        }
    }

    private function reponerInsumos($conexion, $idPedido, $idUsuario, $motivo)
    {
        $movimiento = new MovimientoInventario();
        $stmt = $conexion->prepare("SELECT id_insumo, SUM(CASE WHEN tipo = 'salida' THEN cantidad ELSE -cantidad END) AS neto FROM movimientos_inventario WHERE id_pedido = ? AND tipo IN ('salida', 'ingreso') GROUP BY id_insumo");
        $stmt->execute(array($idPedido));
        $netos = $stmt->fetchAll();
        $sumar = $conexion->prepare('UPDATE insumos SET stock_actual = stock_actual + ? WHERE id_insumo = ?');
        foreach ($netos as $fila) {
            if ($fila['neto'] > 0) {
                $sumar->execute(array($fila['neto'], $fila['id_insumo']));
                $movimiento->registrarEn($conexion, $fila['id_insumo'], $idUsuario, $idPedido, 'ingreso', $fila['neto'], $motivo);
            }
        }
    }
}
