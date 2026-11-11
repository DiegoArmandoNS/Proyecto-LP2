<?php

require_once "modelos/Categoria.php";
require_once "modelos/Plato.php";
require_once "modelos/RecetaItem.php";

class CartaService {

    private $categoria;
    private $plato;
    private $receta;

    public function __construct($conexion) {
        $this->categoria = new Categoria($conexion);
        $this->plato = new Plato($conexion);
        $this->receta = new RecetaItem($conexion);}
    public function listarCategorias() {
        return $this->categoria->listar();}
    public function registrarCategoria($nombre, $descripcion) {
        return $this->categoria->registrar($nombre, $descripcion);}
    public function listarPlatos() {
        return $this->plato->listar();}
    public function registrarPlato($id_categoria, $nombre, $descripcion, $precio) {
        return $this->plato->registrar(
            $id_categoria,
            $nombre,
            $descripcion,
            $precio);}
    public function actualizarPlato($id_plato, $id_categoria, $nombre, $descripcion, $precio) {
        return $this->plato->actualizar(
            $id_plato,
            $id_categoria,
            $nombre,
            $descripcion,
            $precio);}
    public function cambiarDisponibilidad($id_plato, $disponible) {
        return $this->plato->cambiarDisponibilidad(
            $id_plato,
            $disponible);}
    public function listarRecetaPorPlato($id_plato) {
        return $this->receta->listarPorPlato($id_plato);
}
    public function registrarIngrediente($id_plato, $id_insumo, $cantidad) {
        return $this->receta->registrar(
            $id_plato,
            $id_insumo,
            $cantidad);}
    public function actualizarIngrediente($id_receta_item, $cantidad) {
        return $this->receta->actualizar(
            $id_receta_item,
            $cantidad);}
    public function eliminarIngrediente($id_receta_item) {
        return $this->receta->eliminar($id_receta_item);}}?>