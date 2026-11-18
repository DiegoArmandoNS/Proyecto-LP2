<?php
require_once "../app/config/Conn.php";
class RecetaItem
{
    public function porPlato($idPlato)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT r.id_receta_item, r.id_insumo, r.cantidad, i.nombre, i.unidad_medida, i.stock_actual
             FROM receta_items r JOIN insumos i ON i.id_insumo = r.id_insumo
             WHERE r.id_plato = ? ORDER BY i.nombre');
        $stmt->execute(array($idPlato));
        $resultado = $stmt->fetchAll();
        $conn->cerrar();
        return $resultado;}
    public function agregar($idPlato, $idInsumo, $cantidad)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('SELECT id_receta_item FROM receta_items WHERE id_plato = ? AND id_insumo = ?');
        $stmt->execute(array($idPlato, $idInsumo));
        $fila = $stmt->fetch();
        $conn->cerrar();
        $existe = $fila ? $fila : null;
        if ($existe) {
            $conn = new Conn();
            $conexion = $conn->conectar();
            $stmt = $conexion->prepare('UPDATE receta_items SET cantidad = ? WHERE id_receta_item = ?');
            $stmt->execute(array($cantidad, $existe['id_receta_item']));
            $conn->cerrar();
        } else {
            $conn = new Conn();
            $conexion = $conn->conectar();
            $stmt = $conexion->prepare('INSERT INTO receta_items (id_plato, id_insumo, cantidad) VALUES (?, ?, ?)');
            $stmt->execute(array($idPlato, $idInsumo, $cantidad));
            $conn->cerrar();}}
    public function quitar($id)
    {
        $conn = new Conn();
        $conexion = $conn->conectar();
        $stmt = $conexion->prepare('DELETE FROM receta_items WHERE id_receta_item = ?');
        $stmt->execute(array($id));
        $conn->cerrar();
    }
    public function porcionesDisponibles($idPlato)
    {
        $items = $this->porPlato($idPlato);
        if (count($items) === 0) {
            return null;
        }
        $minimo = null;
        foreach ($items as $item) {
            $porciones = (int) floor(($item['stock_actual'] + 0.0000001) / $item['cantidad']);
            if ($minimo === null || $porciones < $minimo) {
                $minimo = $porciones;}}
        return $minimo;}}