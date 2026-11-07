<?php
class PagoController
{
    public function listar()
    {
        $service = new PagoService();
        $pedidos = $service->pedidosListos();
        require __DIR__ . '/../views/pagos/registrar.php';
    }

    public function cobrar()
    {
        $service = new PagoService();
        $id = $_GET['id'];
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resultado = $service->cobrar($id, $_POST['metodo'], $_POST['monto']);
            if ($resultado['ok']) {
                header('Location: index.php?ruta=pagos/comprobante&id=' . $resultado['id_venta']);
                exit;
            }
            $error = $resultado['error'];
        }

        $pedido = $service->buscarPedido($id);
        if ($pedido == null) {
            header('Location: index.php?ruta=pagos/listar');
            exit;
        }
        require __DIR__ . '/../views/pagos/registrar.php';
    }

    public function comprobante()
    {
        $service = new PagoService();
        $venta = $service->buscarVenta($_GET['id']);
        require __DIR__ . '/../views/pagos/comprobante.php';
    }
}
