<?php
class RecetaItem {
    private $conexion;
    public function __construct($conexion) {
        $this->conexion = $conexion;}
    public function listarPorPlato($id_plato) {
        $sql = "SELECT receta_items.idreceta_items,
                       receta_items.idplatos,
                       receta_items.id_insumos,
                       insumos.` nombre` AS nombre_insumo,
                       insumos.unidad_medida,
                       receta_items.cantidad
                FROM receta_items
                INNER JOIN insumos
                ON receta_items.id_insumos = insumos.id_insumos
                WHERE receta_items.idplatos = :id_plato";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':id_plato', $id_plato);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);}
    public function registrar($id_plato, $id_insumo, $cantidad) {
        $sql = "INSERT INTO receta_items
                (idplatos, id_insumos, cantidad)
                VALUES (:id_plato, :id_insumo, :cantidad)";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':id_plato', $id_plato);
        $consulta->bindParam(':id_insumo', $id_insumo);
        $consulta->bindParam(':cantidad', $cantidad);
        return $consulta->execute();}
    public function actualizar($id_receta_item, $cantidad) {
        $sql = "UPDATE receta_items
                SET cantidad = :cantidad
                WHERE idreceta_items = :id_receta_item";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':cantidad', $cantidad);
        $consulta->bindParam(':id_receta_item', $id_receta_item);
        return $consulta->execute();}
    public function eliminar($id_receta_item) {
        $sql = "DELETE FROM receta_items
                WHERE idreceta_items = :id_receta_item";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':id_receta_item', $id_receta_item);
        return $consulta->execute();}}?>