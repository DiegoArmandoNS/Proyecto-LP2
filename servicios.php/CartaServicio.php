<?php
require_once __DIR__ . '/../modelos/Categoria.php';
require_once __DIR__ . '/../modelos/Plato.php';
require_once __DIR__ . '/../modelos/RecetaItem.php';
class CartaService {
    private $categoria;
    private $plato;
    private $recetaItem;
    public function __construct($conexion) {
        $this->categoria = new Categoria($conexion);
        $this->plato = new Plato($conexion);
        $this->recetaItem = new RecetaItem($conexion);}
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
    public function listarRecetas($id_plato) {
        return $this->recetaItem->listarPorPlato($id_plato);}
    public function registrarReceta($id_plato, $id_insumo, $cantidad) {
        return $this->recetaItem->registrar(
            $id_plato,
            $id_insumo,
            $cantidad);}
    public function actualizarReceta($id_receta_item, $cantidad) {
        return $this->recetaItem->actualizar(
            $id_receta_item,
            $cantidad);}
    public function eliminarReceta($id_receta_item) {
        return $this->recetaItem->eliminar($id_receta_item);}}?>