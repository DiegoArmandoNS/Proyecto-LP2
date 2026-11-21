<?php
require_once "../app/models/Pedido.php";
require_once "../app/models/DetallePedido.php";
require_once "../app/models/Pago.php";

class PagoService
{
    public function pedidosPendientes()
    {
        $modelo = new Pedido();
        $detalle = new DetallePedido();
        $pedidos = $modelo->paraCobrar();
        foreach ($pedidos as $i => $p) {
            $pedidos[$i]['total'] = $detalle->total($p['id_pedido']);
        }
        return $pedidos;
    }

    public function cobrar($idPedido, $idCajero, $metodo, $montoTexto)
    {
        $metodos = listaMetodos();
        if (!isset($metodos[$metodo])) {
            throw new Exception('Elige un método de pago válido.', 1);
        }
        if (!esNumero($montoTexto) || $montoTexto <= 0) {
            throw new Exception('El monto recibido debe ser un número mayor a 0.', 1);
        }
        $modeloPedido = new Pedido();
        $pedido = $modeloPedido->buscar($idPedido);
        if ($pedido === null || $pedido['estado'] !== 'listo') {
            throw new Exception('El pedido no existe, no está listo o ya fue pagado.', 2);
        }
        $detalle = new DetallePedido();
        $total = $detalle->total($idPedido);
        $recibido = round((float) $montoTexto, 2);
        if ($recibido < $total) {
            throw new Exception('Pago incompleto: la cuenta es ' . soles($total) . ' y se recibió ' . soles($recibido) . '.', 1);
        }
        if ($metodo !== 'efectivo' && abs($recibido - $total) > 0.001) {
            throw new Exception('Con ' . $metodos[$metodo] . ' el monto debe ser exactamente ' . soles($total) . '.', 1);
        }
        $vuelto = $metodo === 'efectivo' ? round($recibido - $total, 2) : 0;
        $pago = new Pago();
        return $pago->cobrar($idPedido, $idCajero, $pedido['id_mesa'], $total, $recibido, $vuelto, $metodo);
    }
}
