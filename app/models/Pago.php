<?php
class Pago
{
    public $id_pago;
    public $id_pedido;
    public $monto;
    public $metodo_pago;
    public $fecha_pago;
    public $recibido;
    public $vuelto;

    public function __construct($id_pago, $id_pedido, $monto, $metodo_pago, $recibido, $vuelto)
    {
        $this->id_pago = $id_pago;
        $this->id_pedido = $id_pedido;
        $this->monto = $monto;
        $this->metodo_pago = $metodo_pago;
        $this->fecha_pago = date('Y-m-d H:i:s');
        $this->recibido = $recibido;
        $this->vuelto = $vuelto;
    }
}
