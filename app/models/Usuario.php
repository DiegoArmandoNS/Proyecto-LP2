<?php
require_once __DIR__ . '/../config/Database.php';

class Usuario {
    private $conexion;

    public function __construct() {
        $db = new Database();
        $this->conexion = $db->conectar();
    }

    public function listar() {
        $sql = "SELECT id_usuario, nombre, apellido, username, rol, estado, fecha_registro
                FROM usuarios ORDER BY id_usuario DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorUsername($username) {
        $sql = "SELECT * FROM usuarios WHERE username = :username LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar($datos) {
        $sql = "INSERT INTO usuarios
                (nombre, apellido, username, password, rol, estado)
                VALUES (:nombre, :apellido, :username, :password, :rol, 1)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':username' => $datos['username'],
            ':password' => password_hash($datos['password'], PASSWORD_DEFAULT),
            ':rol' => $datos['rol']
        ]);
    }

    public function actualizar($id, $datos) {
        $sql = "UPDATE usuarios
                SET nombre = :nombre, apellido = :apellido,
                    username = :username, rol = :rol
                WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':username' => $datos['username'],
            ':rol' => $datos['rol'],
            ':id' => $id
        ]);
    }

    public function desactivar($id) {
        $sql = "UPDATE usuarios SET estado = 0 WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
