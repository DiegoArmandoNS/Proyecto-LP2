<?php
require_once __DIR__ . '/../modelos/Plato.php';
class PlatoController {
    private $plato;
    public function __construct($conexion) {
        $this->plato = new Plato($conexion);}
    public function listar() {
        return $this->plato->listar();}
    public function registrar() {
        if (isset($_POST['nombre']) && isset($_POST['id_categoria']) &&
            isset($_POST['descripcion']) && isset($_POST['precio'])) {
            $nombre = trim($_POST['nombre']);
            $id_categoria = $_POST['id_categoria'];
            $descripcion = trim($_POST['descripcion']);
            $precio = $_POST['precio'];
            $errores = 0;
            if (empty($nombre)) {
                echo "Ingrese el nombre del plato";
                $errores++;}
            if (empty($id_categoria)) {
                echo "Seleccione una categoría";
                $errores++;}
            if (empty($precio) || !filter_var($precio, FILTER_VALIDATE_FLOAT)) {
                echo "Ingrese un precio válido";
                $errores++;}
            if ($errores == 0) {
                return $this->plato->registrar(
                    $id_categoria,
                    $nombre,
                    $descripcion,
                    $precio);}}
        return false;}
    public function actualizar() {
        if (isset($_POST['id_plato']) && isset($_POST['id_categoria']) &&
            isset($_POST['nombre']) && isset($_POST['descripcion']) &&
            isset($_POST['precio'])) {
            $id_plato = $_POST['id_plato'];
            $id_categoria = $_POST['id_categoria'];
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $precio = $_POST['precio'];
            $errores = 0;
            if (empty($id_plato) || empty($id_categoria) || empty($nombre)) {
                echo "Complete los campos obligatorios";
                $errores++;}
            if (empty($precio) || !filter_var($precio, FILTER_VALIDATE_FLOAT)) {
                echo "Ingrese un precio válido";
                $errores++;}
            if ($errores == 0) {
                return $this->plato->actualizar(
                    $id_plato,
                    $id_categoria,
                    $nombre,
                    $descripcion,
                    $precio);}}
        return false;}
    public function cambiarDisponibilidad() {
        if (isset($_POST['id_plato']) && isset($_POST['disponible'])) {
            $id_plato = $_POST['id_plato'];
            $disponible = $_POST['disponible'];
            return $this->plato->cambiarDisponibilidad(
                $id_plato,
                $disponible);}
        return false;}}?>