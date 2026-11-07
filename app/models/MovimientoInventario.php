<?php
require_once __DIR__ . '/../../config/conexion.php';

class MovimientoInventario {
    private $conn;
    private $tabla = "movimientos_inventario";

    public function __construct() {
        $this->conn = Conexion::conectar();
    }

    public function listarHistorial() {
        $sql = "SELECT m.id, i.nombre AS insumo, m.tipo, m.cantidad, m.motivo, m.fecha FROM " . $this->tabla . " m INNER JOIN insumos i ON m.insumo_id = i.id ORDER BY m.fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarMovimiento($insumoId, $tipo, $cantidad, $motivo) {
        $sql = "INSERT INTO " . $this->tabla . " (insumo_id, tipo, cantidad, motivo) VALUES (:insumo_id, :tipo, :cantidad, :motivo)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":insumo_id", $insumoId, PDO::PARAM_INT);
        $stmt->bindValue(":tipo", $tipo, PDO::PARAM_STR);
        $stmt->bindValue(":cantidad", $cantidad);
        $stmt->bindValue(":motivo", htmlspecialchars(strip_tags(trim($motivo))), PDO::PARAM_STR);

        return $stmt->execute();
    }
}
?>