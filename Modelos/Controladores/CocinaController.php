<?php

require_once "Modelos/Pedido.php";

class CocinaController{
    public function mostrar(){
        $pedido = new Pedido();
        return $pedido->mostrar();
    }
}