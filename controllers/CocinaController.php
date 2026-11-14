<?php
require_once "../app/services/PedidoService.php";

class CocinaController
{
    public function pedidos()
    {
        $servicio = new PedidoService();
        $pedidos = $servicio->pedidosParaCocina();
        $titulo = 'Cocina';
        require "../app/views/cocina/pedidos.php";
    }

    public function estado()
    {
        $id = (int) recibido('id', 0);
        $nuevoEstado = recibido('estado');
        try {
            $servicio = new PedidoService();
            $servicio->cambiarEstadoCocina($id, $nuevoEstado);
            redirigir('cocina/pedidos', 'Estado del pedido actualizado.');
        } catch (Exception $e) {
            redirigir('cocina/pedidos', $e->getMessage(), 'error');
        }
    }
}
