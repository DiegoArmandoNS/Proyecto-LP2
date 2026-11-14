<?php
require_once "../app/config/Conn.php";

class Usuario
{
    public function listar()
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT id_usuario, nombre, apellido, username, rol, estado, fecha_registro FROM usuarios ORDER BY nombre, apellido');
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;
    }

    public function buscar($id)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT * FROM usuarios WHERE id_usuario = ?');
        $stmt->execute(array($id));
        $fila = $stmt->fetch();
        $conn->cerrar();
        return $fila ? $fila : null;
    }

    public function buscarPorUsername($username)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT * FROM usuarios WHERE username = ?');
        $stmt->execute(array($username));
        $fila = $stmt->fetch();
        $conn->cerrar();
        return $fila ? $fila : null;
    }

    public function usernameExiste($username, $excepto = 0)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT id_usuario FROM usuarios WHERE username = ? AND id_usuario <> ?');
        $stmt->execute(array($username, $excepto));
        $fila = $stmt->fetch();
        $conn->cerrar();
        $fila = $fila ? $fila : null;
        return $fila !== null;
    }

    public function crear($d)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('INSERT INTO usuarios (nombre, apellido, username, password, rol, estado, fecha_registro) VALUES (?, ?, ?, ?, ?, 1, ?)');
        $stmt->execute(array($d['nombre'], $d['apellido'], $d['username'], password_hash($d['password'], PASSWORD_DEFAULT), $d['rol'], date('Y-m-d H:i:s')));
        $nuevoId = (int) $conexion->lastInsertId();
        $conn->cerrar();
        return $nuevoId;
    }

    public function actualizar($id, $d)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('UPDATE usuarios SET nombre = ?, apellido = ?, username = ?, rol = ? WHERE id_usuario = ?');
        $stmt->execute(array($d['nombre'], $d['apellido'], $d['username'], $d['rol'], $id));
        $conn->cerrar();
        if ($d['password'] !== '') {
            $conn = new Conn();
            $conexion = $conn->conectar();
            $stmt = $conexion->prepare('UPDATE usuarios SET password = ? WHERE id_usuario = ?');
            $stmt->execute(array(password_hash($d['password'], PASSWORD_DEFAULT), $id));
            $conn->cerrar();
        }
    }

    public function cambiarEstado($id, $estado)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('UPDATE usuarios SET estado = ? WHERE id_usuario = ?');
        $stmt->execute(array($estado, $id));
        $conn->cerrar();
    }
}
