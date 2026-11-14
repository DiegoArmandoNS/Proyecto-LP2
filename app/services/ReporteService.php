<?php
require_once "../app/models/Venta.php";
require_once "../app/models/MovimientoInventario.php";

class ReporteService
{
    public function fechaValida($fecha)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha) || !strtotime($fecha)) {
            return date('Y-m-d');
        }
        return $fecha;
    }

    public function ventasDelDia($fecha)
    {
        $venta = new Venta();
        return $venta->resumenDelDia($fecha);
    }

    public function inventarioDelDia($fecha)
    {
        $movimiento = new MovimientoInventario();
        return array(
            'movimientos' => $movimiento->listar(0, '', $fecha),
            'resumen' => $movimiento->resumenDelDia($fecha)
        );
    }
}
