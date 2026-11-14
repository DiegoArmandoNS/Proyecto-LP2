<?php
require_once "../app/config/Conn.php";

class Mesa
{
    public function listar()
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT * FROM mesas ORDER BY numero_mesa');
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function buscar($id)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT * FROM mesas WHERE id_mesa = ?');
        $stmt->execute(array($id));
        $fila = $stmt->fetch();
        $conn->cerrar();
        return $fila ? $fila : null;
    }

    public function numeroExiste($numero)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT id_mesa FROM mesas WHERE numero_mesa = ?');
        $stmt->execute(array($numero));
        $fila = $stmt->fetch();
        $conn->cerrar();
        return $fila !== false;
    }

    public function crear($numero, $capacidad)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('INSERT INTO mesas (numero_mesa, capacidad, estado) VALUES (?, ?, ?)');
        $stmt->execute(array($numero, $capacidad, 'libre'));
        $nuevoId = (int) $conexion->lastInsertId();
        $conn->cerrar();
        return $nuevoId;
    }

    public function cambiarEstado($id, $estado)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('UPDATE mesas SET estado = ? WHERE id_mesa = ?');
        $stmt->execute(array($estado, $id));
        $conn->cerrar();
    }

    public function actualizarEstado($conexion, $idMesa)
    {
        $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM pedidos WHERE id_mesa = ? AND estado IN ('pendiente', 'en_preparacion')");
        $stmt->execute(array($idMesa));
        $fila = $stmt->fetch();
        if ($fila['total'] > 0) {
            $estado = 'ocupada';
        } else {
            $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM pedidos WHERE id_mesa = ? AND estado = 'listo'");
            $stmt->execute(array($idMesa));
            $fila = $stmt->fetch();
            $estado = $fila['total'] > 0 ? 'por_cobrar' : 'libre';
        }
        $stmt = $conexion->prepare('UPDATE mesas SET estado = ? WHERE id_mesa = ?');
        $stmt->execute(array($estado, $idMesa));
    }
}
