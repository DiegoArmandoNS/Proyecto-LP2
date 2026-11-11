<?php
require_once __DIR__ . '/../servicios/CartaService.php';
class PlatoController {
    private $cartaService;
    public function __construct($conexion) {
        $this->cartaService = new CartaService($conexion);}
    public function listar() {
        return $this->cartaService->listarPlatos();}
    public function registrar() {
        $id_categoria = $_POST['id_categoria'];
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $precio = $_POST['precio'];
        if (empty($id_categoria) || empty($nombre) || empty($precio)) {
            return false;}
        if (!is_numeric($precio) || $precio <= 0) {
            return false;}
        return $this->cartaService->registrarPlato(
            $id_categoria,
            $nombre,
            $descripcion,
            $precio);}
    public function actualizar() {
        $id_plato = $_POST['id_plato'];
        $id_categoria = $_POST['id_categoria'];
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $precio = $_POST['precio'];
        if (empty($id_plato) || empty($id_categoria) || empty($nombre) || empty($precio)) {
            return false;}
        if (!is_numeric($precio) || $precio <= 0) {
            return false;}
        return $this->cartaService->actualizarPlato(
            $id_plato,
            $id_categoria,
            $nombre,
            $descripcion,
            $precio);}
    public function cambiarDisponibilidad() {
        $id_plato = $_POST['id_plato'];
        $disponible = $_POST['disponible'];
        if (empty($id_plato)) {
            return false;}
        return $this->cartaService->cambiarDisponibilidad(
            $id_plato,
            $disponible);}}?>