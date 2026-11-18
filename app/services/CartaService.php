<?php
require_once "../app/models/Plato.php";
require_once "../app/models/Categoria.php";
require_once "../app/models/Insumo.php";
require_once "../app/models/RecetaItem.php";
class CartaService
{
    public function validarPlato($d)
    {$errores = array();
        $nombre = trim($d['nombre'] ?? '');
        $idCategoria = (int) ($d['id_categoria'] ?? 0);
        $precio = $d['precio'] ?? '';
        if ($nombre === '') {
            $errores[] = 'El nombre del plato es obligatorio.';
        } elseif (strlen($nombre) < 3) {
            $errores[] = 'El nombre debe tener al menos 3 caracteres.';}
        if ($idCategoria <= 0) {
            $errores[] = 'Elige una categoría.';
        } else {
            $categoria = new Categoria();
            $fila = $categoria->buscar($idCategoria);
            if ($fila === null || (int) $fila['estado'] !== 1) {
                $errores[] = 'La categoría no existe o está inactiva.';
            }}
        if (!esNumero($precio) || (float) $precio <= 0) {
            $errores[] = 'El precio debe ser un número mayor a 0.';}
        return $errores;}
    public function validarIngrediente($idInsumo, $cantidad)
    {
        $insumo = new Insumo();
        $fila = $insumo->buscar($idInsumo);
        if (
            (int) $idInsumo <= 0 ||
            $fila === null ||
            (int) $fila['estado'] !== 1 ||
            !esNumero($cantidad) ||
            (float) $cantidad <= 0
        ) {
            throw new Exception(
                'Elige un insumo activo y una cantidad mayor a 0.'
            );}}
    public function platosConPorciones()
    {
        $plato = new Plato();
        $receta = new RecetaItem();
        $platos = $plato->listar(true);
        foreach ($platos as $i => $p) {
            $platos[$i]['porciones'] =
                $receta->porcionesDisponibles($p['id_plato']);}
        return $platos;}
    public function consumoDePedido($items)
    {
        $receta = new RecetaItem();
        $consumo = array();
        foreach ($items as $idPlato => $item) {
            foreach ($receta->porPlato($idPlato) as $ingrediente) {
                $necesario = round(
                    $ingrediente['cantidad'] * $item['cantidad'],
                    3);
                if (!isset($consumo[$ingrediente['id_insumo']])) {
                    $consumo[$ingrediente['id_insumo']] = 0;}
                $consumo[$ingrediente['id_insumo']] = round(
                    $consumo[$ingrediente['id_insumo']] + $necesario,
                    3);}}return $consumo;}}