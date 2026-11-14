<?php
require_once "../app/config/Conn.php";
require_once "../app/models/MovimientoInventario.php";

class Insumo
{
    public function listar($soloActivos = false)
    {
        $sql = 'SELECT * FROM insumos';
        if ($soloActivos) {
            $sql .= ' WHERE estado = 1';
        }
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare($sql . ' ORDER BY nombre');
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function buscar($id)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT * FROM insumos WHERE id_insumo = ?');
        $stmt->execute(array($id));
        $fila = $stmt->fetch();
        $conn->cerrar();
        return $fila ? $fila : null;
    }

    public function crear($d)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('INSERT INTO insumos (nombre, unidad_medida, stock_actual, stock_minimo, estado) VALUES (?, ?, ?, ?, 1)');
        $stmt->execute(array($d['nombre'], $d['unidad_medida'], $d['stock_actual'], $d['stock_minimo']));
        $nuevoId = (int) $conexion->lastInsertId();
        $conn->cerrar();
        return $nuevoId;
    }

    public function actualizar($id, $d)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('UPDATE insumos SET nombre = ?, unidad_medida = ?, stock_minimo = ?, estado = ? WHERE id_insumo = ?');
        $stmt->execute(array($d['nombre'], $d['unidad_medida'], $d['stock_minimo'], $d['estado'], $id));
        $conn->cerrar();
    }

    public function alertas()
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT * FROM insumos WHERE estado = 1 AND stock_actual <= stock_minimo ORDER BY nombre');
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function registrarMovimiento($idInsumo, $tipo, $cantidad, $motivo, $idUsuario, $stockAnterior)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $movimiento = new MovimientoInventario();
        $conexion->beginTransaction();
        try {
            if ($tipo === 'ingreso') {
                $stmt = $conexion->prepare('UPDATE insumos SET stock_actual = stock_actual + ? WHERE id_insumo = ?');
                $stmt->execute(array($cantidad, $idInsumo));
                $movimiento->registrarEn($conexion, $idInsumo, $idUsuario, null, 'ingreso', $cantidad, $motivo);
            } elseif ($tipo === 'merma') {
                $stmt = $conexion->prepare('UPDATE insumos SET stock_actual = stock_actual - ? WHERE id_insumo = ? AND stock_actual >= ?');
                $stmt->execute(array($cantidad, $idInsumo, $cantidad));
                if ($stmt->rowCount() === 0) {
                    throw new Exception('La merma es mayor al stock actual (' . cantidad($stockAnterior) . ').');
                }
                $movimiento->registrarEn($conexion, $idInsumo, $idUsuario, null, 'merma', $cantidad, $motivo);
            } else {
                $stmt = $conexion->prepare('UPDATE insumos SET stock_actual = ? WHERE id_insumo = ?');
                $stmt->execute(array($cantidad, $idInsumo));
                $diferencia = round($cantidad - $stockAnterior, 3);
                $detalle = $motivo . ' (de ' . cantidad($stockAnterior) . ' a ' . cantidad($cantidad) . ')';
                $movimiento->registrarEn($conexion, $idInsumo, $idUsuario, null, 'ajuste', $diferencia, $detalle);
            }
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
}
