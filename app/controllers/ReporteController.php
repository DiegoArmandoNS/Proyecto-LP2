<?php
require_once "../app/services/ReporteService.php";

class ReporteController
{
    public function ventas()
    {
        $servicio = new ReporteService();
        $fecha = $servicio->fechaValida(recibido('fecha', date('Y-m-d')));
        $titulo = 'Reporte de ventas del día';
        $reporte = $servicio->ventasDelDia($fecha);
        $metodos = listaMetodos();
        require "../app/views/reportes/ventas.php";
    }

    public function inventario()
    {
        $servicio = new ReporteService();
        $fecha = $servicio->fechaValida(recibido('fecha', date('Y-m-d')));
        $titulo = 'Reporte de inventario del día';
        $reporte = $servicio->inventarioDelDia($fecha);
        require "../app/views/reportes/inventario.php";
    }
}
