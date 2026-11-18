<?php
require_once "../app/models/Plato.php";
require_once "../app/models/Insumo.php";
require_once "../app/models/RecetaItem.php";
class CartaService
{
    public function validarPlato($d)
    {
        $errores = array();
        if ($d['nombre'] === '') {
            $errores[] = 'El nombre del plato es obligatorio.';}
        if ((int) $d['id_categoria'] <= 0) {
            $errores[] = 'Elige una categoría.';}
        if (!esNumero($d['precio']) || $d['precio'] <= 0) {
            $errores[] = 'El precio debe ser un número mayor a 0.';}
        return $errores;}
    public function validarIngrediente($idInsumo, $cantidad)
    {
        $insumo = new Insumo();
        if ($insumo->buscar($idInsumo) === null || !esNumero($cantidad) || $cantidad <= 0) {
            throw new Exception('Elige un insumo y una cantidad mayor a 0.');}}
    public function platosConPorciones()
    {
        $plato = new Plato();
        $receta = new RecetaItem();
        $platos = $plato->listar(true);
        foreach ($platos as $i => $p) {
            $platos[$i]['porciones'] = $receta->porcionesDisponibles($p['id_plato']);}
        return $platos;}
    public function consumoDePedido($items)
    {
        $receta = new RecetaItem();
        $consumo = array();
        foreach ($items as $idPlato => $item) {
            foreach ($receta->porPlato($idPlato) as $ingrediente) {
                $necesario = round($ingrediente['cantidad'] * $item['cantidad'], 3);
                if (!isset($consumo[$ingrediente['id_insumo']])) {
                    $consumo[$ingrediente['id_insumo']] = 0;
            }
                $consumo[$ingrediente['id_insumo']] = round($consumo[$ingrediente['id_insumo']] + $necesario, 3);}}
        return $consumo;}}