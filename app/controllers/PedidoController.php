<?php
require_once "../app/models/Pedido.php";
require_once "../app/models/DetallePedido.php";
require_once "../app/models/Mesa.php";
require_once "../app/services/PedidoService.php";
require_once "../app/services/CartaService.php";

class PedidoController
{
    public function listar()
    {
        $modelo = new Pedido();
        $idUsuario = rolUsuario() === 'mesero' ? idUsuario() : 0;
        $fecha = recibido('fecha', date('Y-m-d'));
        $pedidos = $modelo->listar(array(), $idUsuario, $fecha);
        $listos = 0;
        foreach ($pedidos as $p) {
            if ($p['estado'] === 'listo') {
                $listos++;
            }
        }
        $titulo = 'Pedidos';
        require "../app/views/pedidos/listar.php";
    }

    public function registrar()
    {
        $mesa = new Mesa();
        $carta = new CartaService();
        $servicio = new PedidoService();
        $platos = $carta->platosConPorciones();
        $mesas = $mesa->listar();
        $pedido = null;
        $cantidades = array();
        $mesa_elegida = (int) recibido('mesa', 0);
        $observaciones = '';
        $error = '';
        $id = (int) recibido('id', 0);
        if ($id > 0) {
            $modelo = new Pedido();
            $pedido = $modelo->buscar($id);
            if ($pedido === null) {
                redirigir('pedidos/listar', 'El pedido no existe.', 'error');
            }
            try {
                $servicio->verificarPropiedad($pedido, idUsuario(), rolUsuario());
            } catch (Exception $e) {
                redirigir('pedidos/detalle&id=' . $id, $e->getMessage(), 'error');
            }
            $detalle = new DetallePedido();
            foreach ($detalle->porPedido($id) as $d) {
                $cantidades[$d['id_plato']] = array('cantidad' => $d['cantidad'], 'observaciones' => $d['observaciones']);
            }
            $mesa_elegida = (int) $pedido['id_mesa'];
            $observaciones = $pedido['observaciones'];
        }
        $titulo = $id > 0 ? 'Modificar pedido #' . $id : 'Nuevo pedido';
        require "../app/views/pedidos/registrar.php";
    }

    public function guardar()
    {
        $id = (int) enviado('id_pedido', 0);
        $items = isset($_POST['items']) && is_array($_POST['items']) ? $_POST['items'] : array();
        $observaciones = enviado('observaciones');
        $idMesa = (int) enviado('id_mesa', 0);
        $servicio = new PedidoService();
        try {
            if ($id > 0) {
                $servicio->modificar($id, idUsuario(), rolUsuario(), $items, $observaciones);
                redirigir('pedidos/detalle&id=' . $id, 'Pedido modificado.');
            }
            $idNuevo = $servicio->registrar($idMesa, idUsuario(), $items, $observaciones);
            redirigir('pedidos/detalle&id=' . $idNuevo, 'Pedido registrado y enviado a cocina.');
        } catch (Exception $e) {
            $mesa = new Mesa();
            $modelo = new Pedido();
            $carta = new CartaService();
            $platos = $carta->platosConPorciones();
            $mesas = $mesa->listar();
            $pedido = $id > 0 ? $modelo->buscar($id) : null;
            $cantidades = $items;
            $mesa_elegida = $idMesa;
            $error = $e->getMessage();
            $titulo = $id > 0 ? 'Modificar pedido #' . $id : 'Nuevo pedido';
            require "../app/views/pedidos/registrar.php";
        }
    }

    public function detalle()
    {
        $modelo = new Pedido();
        $id = (int) recibido('id', 0);
        $pedido = $modelo->buscar($id);
        if ($pedido === null) {
            redirigir('pedidos/listar', 'El pedido no existe.', 'error');
        }
        if (rolUsuario() === 'mesero' && (int) $pedido['id_usuario'] !== idUsuario()) {
            mostrarError('Solo puedes ver tus propios pedidos.', 403);
        }
        $detalle = new DetallePedido();
        $items = $detalle->porPedido($id);
        $total = $detalle->total($id);
        $servicio = new PedidoService();
        $puedeEditar = $servicio->puedeEditar($pedido, idUsuario(), rolUsuario());
        $titulo = 'Pedido #' . $id;
        require "../app/views/pedidos/detalle.php";
    }

    public function cancelar()
    {
        $id = (int) enviado('id_pedido', 0);
        $motivo = enviado('motivo');
        try {
            $servicio = new PedidoService();
            $servicio->cancelar($id, idUsuario(), rolUsuario(), $motivo);
            redirigir('pedidos/listar', 'Pedido #' . $id . ' cancelado. Los insumos fueron repuestos.');
        } catch (Exception $e) {
            redirigir('pedidos/detalle&id=' . $id, $e->getMessage(), 'error');
        }
    }
}
