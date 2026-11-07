<?php
class Venta
{
    public $id_venta;
    public $id_pedido;
    public $id_pago;
    public $numero_comprobante;
    public $total;
    public $fecha_venta;

    public function __construct($id_venta, $id_pedido, $id_pago, $total)
    {
        $this->id_venta = $id_venta;
        $this->id_pedido = $id_pedido;
        $this->id_pago = $id_pago;
        $this->numero_comprobante = 'C001-' . str_pad($id_venta, 8, '0', STR_PAD_LEFT);
        $this->total = $total;
        $this->fecha_venta = date('Y-m-d H:i:s');
    }
}
