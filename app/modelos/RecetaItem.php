<?php
class RecetaItem {
    private $conexion;
    public function __construct($conexion) {
        $this->conexion = $conexion;}
    public function listarPorPlato($id_plato) {
        $sql = "SELECT receta_items.id_receta_item,
                       receta_items.id_plato,
                       receta_items.id_insumo,
                       insumos.nombre AS nombre_insumo,
                       insumos.unidad_medida,
                       receta_items.cantidad
                FROM receta_items
                INNER JOIN insumos
                ON receta_items.id_insumo = insumos.id_insumo
                WHERE receta_items.id_plato = :id_plato";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':id_plato', $id_plato);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);}
    public function registrar($id_plato, $id_insumo, $cantidad) {
        $sql = "INSERT INTO receta_items
                (id_plato, id_insumo, cantidad)
                VALUES (:id_plato, :id_insumo, :cantidad)";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':id_plato', $id_plato);
        $consulta->bindParam(':id_insumo', $id_insumo);
        $consulta->bindParam(':cantidad', $cantidad);
        return $consulta->execute();}
    public function actualizar($id_receta_item, $cantidad) {
        $sql = "UPDATE receta_items
                SET cantidad = :cantidad
                WHERE id_receta_item = :id_receta_item";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':cantidad', $cantidad);
        $consulta->bindParam(':id_receta_item', $id_receta_item);
        return $consulta->execute();}
    public function eliminar($id_receta_item) {
        $sql = "DELETE FROM receta_items
                WHERE id_receta_item = :id_receta_item";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':id_receta_item', $id_receta_item);
        return $consulta->execute(); }}?>
