<?php
require_once "../app/models/Pedido.php";
require_once "../app/models/DetallePedido.php";
require_once "../app/models/Plato.php";
require_once "../app/models/Mesa.php";
require_once "../app/services/CartaService.php";

class PedidoService
{
    private function limpiarItems($items)
    {
        $limpios = array();
        foreach ($items as $idPlato => $item) {
            $cantidad = isset($item['cantidad']) ? (int) $item['cantidad'] : 0;
            if ($cantidad > 0) {
                $limpios[(int) $idPlato] = array(
                    'cantidad' => $cantidad,
                    'observaciones' => isset($item['observaciones']) ? trim($item['observaciones']) : ''
                );
            }
        }
        return $limpios;
    }

    private function validarItems($items)
    {
        if (count($items) === 0) {
            throw new Exception('Agrega al menos un plato con cantidad mayor a 0.');
        }
        $plato = new Plato();
        foreach ($items as $idPlato => $item) {
            $fila = $plato->buscar($idPlato);
            if ($fila === null) {
                throw new Exception('Uno de los platos no existe.');
            }
            if ((int) $fila['disponible'] !== 1) {
                throw new Exception('El plato "' . $fila['nombre'] . '" no está disponible.');
            }
            if ($item['cantidad'] > 50) {
                throw new Exception('La cantidad máxima por plato es 50.');
            }
        }
    }

    private function agregarPrecios($items)
    {
        $plato = new Plato();
        foreach ($items as $idPlato => $item) {
            $fila = $plato->buscar($idPlato);
            $items[$idPlato]['precio'] = $fila['precio'];
        }
        return $items;
    }

    private function prepararItems($items)
    {
        $limpios = $this->limpiarItems($items);
        $this->validarItems($limpios);
        return $this->agregarPrecios($limpios);
    }

    public function verificarPropiedad($pedido, $idUsuario, $rol)
    {
        if ($rol !== 'administrador' && (int) $pedido['id_usuario'] !== (int) $idUsuario) {
            throw new Exception('Solo puedes modificar tus propios pedidos.');
        }
        if ($pedido['estado'] !== 'pendiente') {
            throw new Exception('Solo se puede modificar o cancelar un pedido en estado Pendiente.');
        }
    }

    public function puedeEditar($pedido, $idUsuario, $rol)
    {
        return $pedido['estado'] === 'pendiente' && ($rol === 'administrador' || (int) $pedido['id_usuario'] === (int) $idUsuario);
    }

    public function registrar($idMesa, $idUsuario, $items, $observaciones)
    {
        $mesa = new Mesa();
        if ($mesa->buscar($idMesa) === null) {
            throw new Exception('Elige una mesa.');
        }
        $items = $this->prepararItems($items);
        $carta = new CartaService();
        $consumo = $carta->consumoDePedido($items);
        $pedido = new Pedido();
        return $pedido->registrarCompleto($idMesa, $idUsuario, $observaciones, $items, $consumo);
    }

    public function modificar($idPedido, $idUsuario, $rol, $items, $observaciones)
    {
        $modelo = new Pedido();
        $pedido = $modelo->buscar($idPedido);
        if ($pedido === null) {
            throw new Exception('El pedido no existe.');
        }
        $this->verificarPropiedad($pedido, $idUsuario, $rol);
        $items = $this->prepararItems($items);
        $carta = new CartaService();
        $consumo = $carta->consumoDePedido($items);
        $modelo->modificarCompleto($idPedido, $idUsuario, $observaciones, $items, $consumo);
    }

    public function cancelar($idPedido, $idUsuario, $rol, $motivo)
    {
        $modelo = new Pedido();
        $pedido = $modelo->buscar($idPedido);
        if ($pedido === null) {
            throw new Exception('El pedido no existe.');
        }
        $this->verificarPropiedad($pedido, $idUsuario, $rol);
        if ($motivo === '') {
            throw new Exception('Escribe el motivo de la cancelación.');
        }
        $modelo->cancelarCompleto($idPedido, $idUsuario, $pedido['id_mesa'], $motivo);
    }

    public function cambiarEstadoCocina($idPedido, $nuevoEstado)
    {
        $modelo = new Pedido();
        $pedido = $modelo->buscar($idPedido);
        if ($pedido === null) {
            throw new Exception('El pedido no existe.');
        }
        $permitidos = array('pendiente' => 'en_preparacion', 'en_preparacion' => 'listo');
        if (!isset($permitidos[$pedido['estado']]) || $permitidos[$pedido['estado']] !== $nuevoEstado) {
            throw new Exception('No se puede pasar el pedido de ' . textoEstado($pedido['estado']) . ' a ' . textoEstado($nuevoEstado) . '.');
        }
        $modelo->cambiarEstadoCocina($idPedido, $pedido['id_mesa'], $nuevoEstado);
    }

    public function pedidosParaCocina()
    {
        $modelo = new Pedido();
        $detalle = new DetallePedido();
        $pedidos = $modelo->paraCocina();
        foreach ($pedidos as $i => $p) {
            $pedidos[$i]['items'] = $detalle->porPedido($p['id_pedido']);
        }
        return $pedidos;
    }
}
