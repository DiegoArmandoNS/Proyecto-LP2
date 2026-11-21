<?php
require_once "../app/models/Pedido.php";
require_once "../app/models/DetallePedido.php";
require_once "../app/models/Venta.php";
require_once "../app/services/PagoService.php";

class PagoController
{
    public function pendientes()
    {
        $servicio = new PagoService();
        $pedidos = $servicio->pedidosPendientes();
        $titulo = 'Pedidos por cobrar';
        require "../app/views/pagos/pendientes.php";
    }

    public function registrar()
    {
        $modelo = new Pedido();
        $id = (int) recibido('id', 0);
        $pedido = $modelo->buscar($id);
        if ($pedido === null || $pedido['estado'] !== 'listo') {
            redirigir('pagos/pendientes', 'El pedido no existe o no está listo para cobrar.', 'error');
        }
        $detalle = new DetallePedido();
        $titulo = 'Cobrar pedido #' . $id;
        $items = $detalle->porPedido($id);
        $total = $detalle->total($id);
        $metodos = listaMetodos();
        require "../app/views/pagos/registrar.php";
    }

    public function guardar()
    {
        $id = (int) enviado('id_pedido', 0);
        try {
            $servicio = new PagoService();
            $idVenta = $servicio->cobrar($id, idUsuario(), enviado('metodo'), enviado('monto'));
            redirigir('pagos/comprobante&id=' . $idVenta, 'Pago registrado correctamente.');
        } catch (Exception $e) {
            if ($e->getCode() === 2) {
                redirigir('pagos/pendientes', $e->getMessage(), 'error');
            }
            redirigir('pagos/registrar&id=' . $id, $e->getMessage(), 'error');
        }
    }

    public function comprobante()
    {
        $venta = new Venta();
        $fila = $venta->buscar((int) recibido('id', 0));
        if ($fila === null) {
            redirigir('pagos/pendientes', 'Comprobante no encontrado.', 'error');
        }
        $detalle = new DetallePedido();
        $titulo = 'Comprobante ' . $fila['numero_comprobante'];
        $venta = $fila;
        $items = $detalle->porPedido($fila['id_pedido']);
        $metodos = listaMetodos();
        require "../app/views/pagos/comprobante.php";
    }
}
