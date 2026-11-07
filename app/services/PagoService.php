<?php
class PagoService
{
    public function iniciarDatos()
    {
        if (isset($_SESSION['pedidos'])) {
            return;
        }
        $_SESSION['pedidos'] = array(
            array(
                'id_pedido' => 101, 'mesa' => 3, 'estado' => 'listo',
                'items' => array(
                    array('plato' => 'Arroz con pollo', 'cantidad' => 2, 'precio' => 18),
                    array('plato' => 'Chicha morada', 'cantidad' => 1, 'precio' => 12),
                ),
            ),
            array(
                'id_pedido' => 102, 'mesa' => 5, 'estado' => 'listo',
                'items' => array(
                    array('plato' => 'Lomo saltado', 'cantidad' => 1, 'precio' => 25),
                    array('plato' => 'Ceviche', 'cantidad' => 2, 'precio' => 30),
                ),
            ),
        );
        $_SESSION['pagos'] = array();
        $_SESSION['ventas'] = array();
    }

    public function calcularTotal($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total = $total + ($item['cantidad'] * $item['precio']);
        }
        return $total;
    }

    public function pedidosListos()
    {
        $this->iniciarDatos();
        $listos = array();
        foreach ($_SESSION['pedidos'] as $pedido) {
            if ($pedido['estado'] == 'listo') {
                $pedido['total'] = $this->calcularTotal($pedido['items']);
                $listos[] = $pedido;
            }
        }
        return $listos;
    }

    public function buscarPedido($id)
    {
        foreach ($this->pedidosListos() as $pedido) {
            if ($pedido['id_pedido'] == $id) {
                return $pedido;
            }
        }
        return null;
    }

    public function cobrar($id_pedido, $metodo, $monto_recibido)
    {
        $pedido = $this->buscarPedido($id_pedido);

        if ($pedido == null) {
            return array('ok' => false, 'error' => 'El pedido no existe o ya fue pagado.');
        }
        if ($monto_recibido < $pedido['total']) {
            return array('ok' => false, 'error' => 'Pago incompleto: la cuenta es S/ ' . $pedido['total']);
        }

        $vuelto = 0;
        if ($metodo == 'efectivo') {
            $vuelto = $monto_recibido - $pedido['total'];
        }

        $id_pago = count($_SESSION['pagos']) + 1;
        $id_venta = count($_SESSION['ventas']) + 1;

        $pago = new Pago($id_pago, $id_pedido, $pedido['total'], $metodo, $monto_recibido, $vuelto);
        $venta = new Venta($id_venta, $id_pedido, $id_pago, $pedido['total']);

        $_SESSION['pagos'][] = (array) $pago;
        $_SESSION['ventas'][] = array(
            'id_venta' => $venta->id_venta,
            'numero' => $venta->numero_comprobante,
            'fecha' => $venta->fecha_venta,
            'mesa' => $pedido['mesa'],
            'items' => $pedido['items'],
            'total' => $venta->total,
            'metodo' => $metodo,
            'recibido' => $monto_recibido,
            'vuelto' => $vuelto,
        );

        foreach ($_SESSION['pedidos'] as $i => $p) {
            if ($p['id_pedido'] == $id_pedido) {
                $_SESSION['pedidos'][$i]['estado'] = 'pagado';
            }
        }

        return array('ok' => true, 'id_venta' => $id_venta);
    }

    public function buscarVenta($id_venta)
    {
        $this->iniciarDatos();
        foreach ($_SESSION['ventas'] as $venta) {
            if ($venta['id_venta'] == $id_venta) {
                return $venta;
            }
        }
        return null;
    }
}
