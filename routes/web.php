<?php
switch ($ruta) {
    case 'login':
        require_once "../app/controllers/AuthController.php";
        $controlador = new AuthController();
        $controlador->login();
        break;
    case 'logout':
        requiereRol(array());
        require_once "../app/controllers/AuthController.php";
        $controlador = new AuthController();
        $controlador->logout();
        break;
    case 'inicio':
        requiereRol(array());
        require_once "../app/controllers/AuthController.php";
        $controlador = new AuthController();
        $controlador->inicio();
        break;
    case 'usuarios/listar':
        requiereRol(array('administrador'));
        require_once "../app/controllers/UsuarioController.php";
        $controlador = new UsuarioController();
        $controlador->listar();
        break;
    case 'usuarios/formulario':
        requiereRol(array('administrador'));
        require_once "../app/controllers/UsuarioController.php";
        $controlador = new UsuarioController();
        $controlador->formulario();
        break;
    case 'usuarios/guardar':
        requiereRol(array('administrador'));
        require_once "../app/controllers/UsuarioController.php";
        $controlador = new UsuarioController();
        $controlador->guardar();
        break;
    case 'usuarios/estado':
        requiereRol(array('administrador'));
        require_once "../app/controllers/UsuarioController.php";
        $controlador = new UsuarioController();
        $controlador->estado();
        break;
    case 'mesas/listar':
        requiereRol(array('administrador', 'mesero', 'cajero'));
        require_once "../app/controllers/MesaController.php";
        $controlador = new MesaController();
        $controlador->listar();
        break;
    case 'mesas/guardar':
        requiereRol(array('administrador'));
        require_once "../app/controllers/MesaController.php";
        $controlador = new MesaController();
        $controlador->guardar();
        break;
    case 'mesas/estado':
        requiereRol(array('administrador'));
        require_once "../app/controllers/MesaController.php";
        $controlador = new MesaController();
        $controlador->estado();
        break;
    case 'carta/listar':
        requiereRol(array('administrador', 'mesero'));
        require_once "../app/controllers/PlatoController.php";
        $controlador = new PlatoController();
        $controlador->listar();
        break;
    case 'carta/formulario':
        requiereRol(array('administrador'));
        require_once "../app/controllers/PlatoController.php";
        $controlador = new PlatoController();
        $controlador->formulario();
        break;
    case 'carta/guardar':
        requiereRol(array('administrador'));
        require_once "../app/controllers/PlatoController.php";
        $controlador = new PlatoController();
        $controlador->guardar();
        break;
    case 'carta/disponibilidad':
        requiereRol(array('administrador'));
        require_once "../app/controllers/PlatoController.php";
        $controlador = new PlatoController();
        $controlador->disponibilidad();
        break;
    case 'carta/categorias':
        requiereRol(array('administrador'));
        require_once "../app/controllers/PlatoController.php";
        $controlador = new PlatoController();
        $controlador->categorias();
        break;
    case 'carta/categoria_guardar':
        requiereRol(array('administrador'));
        require_once "../app/controllers/PlatoController.php";
        $controlador = new PlatoController();
        $controlador->categoriaGuardar();
        break;
    case 'carta/categoria_estado':
        requiereRol(array('administrador'));
        require_once "../app/controllers/PlatoController.php";
        $controlador = new PlatoController();
        $controlador->categoriaEstado();
        break;
    case 'carta/recetas':
        requiereRol(array('administrador'));
        require_once "../app/controllers/PlatoController.php";
        $controlador = new PlatoController();
        $controlador->recetas();
        break;
    case 'carta/receta_agregar':
        requiereRol(array('administrador'));
        require_once "../app/controllers/PlatoController.php";
        $controlador = new PlatoController();
        $controlador->recetaAgregar();
        break;
    case 'carta/receta_quitar':
        requiereRol(array('administrador'));
        require_once "../app/controllers/PlatoController.php";
        $controlador = new PlatoController();
        $controlador->recetaQuitar();
        break;
    case 'pedidos/listar':
        requiereRol(array('administrador', 'mesero'));
        require_once "../app/controllers/PedidoController.php";
        $controlador = new PedidoController();
        $controlador->listar();
        break;
    case 'pedidos/registrar':
        requiereRol(array('administrador', 'mesero'));
        require_once "../app/controllers/PedidoController.php";
        $controlador = new PedidoController();
        $controlador->registrar();
        break;
    case 'pedidos/guardar':
        requiereRol(array('administrador', 'mesero'));
        require_once "../app/controllers/PedidoController.php";
        $controlador = new PedidoController();
        $controlador->guardar();
        break;
    case 'pedidos/detalle':
        requiereRol(array('administrador', 'mesero', 'cajero'));
        require_once "../app/controllers/PedidoController.php";
        $controlador = new PedidoController();
        $controlador->detalle();
        break;
    case 'pedidos/cancelar':
        requiereRol(array('administrador', 'mesero'));
        require_once "../app/controllers/PedidoController.php";
        $controlador = new PedidoController();
        $controlador->cancelar();
        break;
    case 'cocina/pedidos':
        requiereRol(array('administrador', 'cocinero'));
        require_once "../app/controllers/CocinaController.php";
        $controlador = new CocinaController();
        $controlador->pedidos();
        break;
    case 'cocina/estado':
        requiereRol(array('administrador', 'cocinero'));
        require_once "../app/controllers/CocinaController.php";
        $controlador = new CocinaController();
        $controlador->estado();
        break;
    case 'inventario/listar':
        requiereRol(array('administrador'));
        require_once "../app/controllers/InventarioController.php";
        $controlador = new InventarioController();
        $controlador->listar();
        break;
    case 'inventario/formulario':
        requiereRol(array('administrador'));
        require_once "../app/controllers/InventarioController.php";
        $controlador = new InventarioController();
        $controlador->formulario();
        break;
    case 'inventario/guardar':
        requiereRol(array('administrador'));
        require_once "../app/controllers/InventarioController.php";
        $controlador = new InventarioController();
        $controlador->guardar();
        break;
    case 'inventario/movimientos':
        requiereRol(array('administrador'));
        require_once "../app/controllers/InventarioController.php";
        $controlador = new InventarioController();
        $controlador->movimientos();
        break;
    case 'inventario/movimiento_guardar':
        requiereRol(array('administrador'));
        require_once "../app/controllers/InventarioController.php";
        $controlador = new InventarioController();
        $controlador->registrarMovimiento();
        break;
    case 'inventario/alertas':
        requiereRol(array('administrador'));
        require_once "../app/controllers/InventarioController.php";
        $controlador = new InventarioController();
        $controlador->alertas();
        break;
    case 'pagos/pendientes':
        requiereRol(array('administrador', 'cajero'));
        require_once "../app/controllers/PagoController.php";
        $controlador = new PagoController();
        $controlador->pendientes();
        break;
    case 'pagos/registrar':
        requiereRol(array('administrador', 'cajero'));
        require_once "../app/controllers/PagoController.php";
        $controlador = new PagoController();
        $controlador->registrar();
        break;
    case 'pagos/guardar':
        requiereRol(array('administrador', 'cajero'));
        require_once "../app/controllers/PagoController.php";
        $controlador = new PagoController();
        $controlador->guardar();
        break;
    case 'pagos/comprobante':
        requiereRol(array('administrador', 'cajero'));
        require_once "../app/controllers/PagoController.php";
        $controlador = new PagoController();
        $controlador->comprobante();
        break;
    case 'reportes/ventas':
        requiereRol(array('administrador'));
        require_once "../app/controllers/ReporteController.php";
        $controlador = new ReporteController();
        $controlador->ventas();
        break;
    case 'reportes/inventario':
        requiereRol(array('administrador'));
        require_once "../app/controllers/ReporteController.php";
        $controlador = new ReporteController();
        $controlador->inventario();
        break;
    default:
        mostrarError('La página no existe.', 404);
        break;
}
