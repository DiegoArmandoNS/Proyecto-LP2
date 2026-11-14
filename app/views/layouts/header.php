<?php
require_once "../app/models/Pedido.php";

$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
unset($_SESSION['flash']);
$u = usuarioActual();
$rol = $u ? $u['rol'] : '';
$menu = array();
if ($rol === 'administrador') {
    $menu = array('inicio' => 'Inicio', 'usuarios/listar' => 'Usuarios', 'mesas/listar' => 'Mesas', 'carta/listar' => 'Carta', 'pedidos/listar' => 'Pedidos', 'cocina/pedidos' => 'Cocina', 'inventario/listar' => 'Inventario', 'pagos/pendientes' => 'Cobros', 'reportes/ventas' => 'Reportes');
} elseif ($rol === 'mesero') {
    $menu = array('inicio' => 'Inicio', 'mesas/listar' => 'Mesas', 'carta/listar' => 'Carta', 'pedidos/registrar' => 'Nuevo pedido', 'pedidos/listar' => 'Mis pedidos');
} elseif ($rol === 'cocinero') {
    $menu = array('inicio' => 'Inicio', 'cocina/pedidos' => 'Cocina');
} elseif ($rol === 'cajero') {
    $menu = array('inicio' => 'Inicio', 'pagos/pendientes' => 'Cobros', 'mesas/listar' => 'Mesas');
}
$listosMesero = 0;
if ($rol === 'mesero') {
    $modeloPedido = new Pedido();
    $listosMesero = count($modeloPedido->listar(array('listo'), idUsuario()));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($titulo); ?> - Restaurante L</title>
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
<nav>
    <b>Restaurante L</b> |
    <?php foreach ($menu as $ruta => $texto) { ?>
        <a href="index.php?ruta=<?php echo e($ruta); ?>"><?php echo e($texto); ?></a>
    <?php } ?>
    <?php if ($u) { ?>
        | <?php echo e($u['nombre'] . ' ' . $u['apellido']); ?> (<?php echo e($rol); ?>) <a href="index.php?ruta=logout">Salir</a>
    <?php } ?>
</nav>
<hr>
    <?php if ($listosMesero > 0) { ?>
        <div class="alerta aviso">Tienes <?php echo $listosMesero; ?> pedido(s) listo(s) para entregar. <a href="index.php?ruta=pedidos/listar">Verlos</a></div>
    <?php } ?>
    <?php if ($flash) { ?>
        <div class="alerta <?php echo e($flash['tipo']); ?>"><?php echo e($flash['mensaje']); ?></div>
    <?php } ?>
    <h1><?php echo e($titulo); ?></h1>
