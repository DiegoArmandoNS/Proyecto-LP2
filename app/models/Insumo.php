<?php
require_once __DIR__ . '/../../config/conexion.php';

class Insumo {
    private $conn;
    private $tabla = "insumos";

    public function __construct() {
        $this->conn = Conexion::conectar();
    }

    public function listar() {
        $sql = "SELECT id, nombre, unidad_medida, stock_actual, stock_minimo, precio_costo FROM " . $this->tabla . " WHERE estado = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar($nombre, $unidadMedida, $stockActual, $stockMinimo, $precioCosto) {
        $sql = "INSERT INTO " . $this->tabla . " (nombre, unidad_medida, stock_actual, stock_minimo, precio_costo) VALUES (:nombre, :unidad, :stock_act, :stock_min, :precio)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":nombre", htmlspecialchars(strip_tags(trim($nombre))), PDO::PARAM_STR);
        $stmt->bindValue(":unidad", htmlspecialchars(strip_tags(trim($unidadMedida))), PDO::PARAM_STR);
        $stmt->bindValue(":stock_act", $stockActual);
        $stmt->bindValue(":stock_min", $stockMinimo);
        $stmt->bindValue(":precio", $precioCosto);

        return $stmt->execute();
    }
}
?>