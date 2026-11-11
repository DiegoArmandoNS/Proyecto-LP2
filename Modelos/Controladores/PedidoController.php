<?php

require_once "Modelos/Pedido.php";
require_once("Vistas/listarPedido.php");
class PedidoController{
    public function mostrar(){
        $pedido = new Pedido();
        return $pedido->mostrar();
    }


}