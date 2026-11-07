<?php
require_once __DIR__ . '/../config/Database.php';

class Mesa {
    private $conexion;

    public function __construct() {
        $db = new Database();
        $this->conexion = $db->conectar();
    }

    public function listar() {
        $sql = "SELECT * FROM mesas ORDER BY numero_mesa ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM mesas WHERE id_mesa = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar($numero, $capacidad) {
        $sql = "INSERT INTO mesas (numero_mesa, capacidad, estado)
                VALUES (:numero, :capacidad, 'libre')";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':numero' => $numero,
            ':capacidad' => $capacidad
        ]);
    }

    public function cambiarEstado($id, $estado) {
        $estadosPermitidos = ['libre', 'ocupada', 'por_cobrar'];
        if (!in_array($estado, $estadosPermitidos, true)) {
            return false;
        }

        $sql = "UPDATE mesas SET estado = :estado WHERE id_mesa = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':estado' => $estado,
            ':id' => $id
        ]);
    }
}
