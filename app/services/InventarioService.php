<?php
require_once "../app/models/Insumo.php";
require_once "../app/models/MovimientoInventario.php";

class InventarioService
{
    public function validarInsumo($d, $esNuevo)
    {
        $errores = array();
        if ($d['nombre'] === '' || $d['unidad_medida'] === '') {
            $errores[] = 'El nombre y la unidad de medida son obligatorios.';
        }
        if (!esNumero($d['stock_minimo']) || $d['stock_minimo'] < 0) {
            $errores[] = 'El stock mínimo debe ser un número igual o mayor a 0.';
        }
        if ($esNuevo && (!esNumero($d['stock_actual']) || $d['stock_actual'] < 0)) {
            $errores[] = 'El stock inicial debe ser un número igual o mayor a 0.';
        }
        return $errores;
    }

    public function crearInsumo($datos, $idUsuario)
    {
        $insumo = new Insumo();
        $nuevo = $insumo->crear($datos);
        if ($datos['stock_actual'] > 0) {
            $movimiento = new MovimientoInventario();
            $movimiento->registrar($nuevo, $idUsuario, null, 'ingreso', $datos['stock_actual'], 'Stock inicial');
        }
        return $nuevo;
    }

    public function registrarMovimiento($idInsumo, $tipo, $cantidad, $motivo, $idUsuario)
    {
        if (!in_array($tipo, array('ingreso', 'merma', 'ajuste'))) {
            throw new Exception('Tipo de movimiento no válido.');
        }
        if (!esNumero($cantidad) || $cantidad < 0 || ($tipo !== 'ajuste' && $cantidad <= 0)) {
            if ($tipo === 'ajuste') {
                throw new Exception('El stock real debe ser un número igual o mayor a 0.');
            }
            throw new Exception('La cantidad debe ser un número mayor a 0.');
        }
        if ($motivo === '' && $tipo === 'ingreso') {
            $motivo = 'Compra';
        }
        if ($motivo === '') {
            throw new Exception('Escribe el motivo del movimiento.');
        }
        $insumo = new Insumo();
        $fila = $insumo->buscar($idInsumo);
        if ($fila === null) {
            throw new Exception('El insumo no existe.');
        }
        $insumo->registrarMovimiento($idInsumo, $tipo, round((float) $cantidad, 3), $motivo, $idUsuario, $fila['stock_actual']);
    }
}
