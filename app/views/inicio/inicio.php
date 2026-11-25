<?php require "../app/views/layouts/header.php"; ?>
<?php $rol = rolUsuario(); ?>
<p>Bienvenido, <b><?php echo e(usuarioActual()['nombre']); ?></b>.</p>

<?php if ($rol === 'administrador') { ?>
    <div>
        <p>Ventas de hoy: <b><?php echo soles($ventas_total); ?></b></p>
        <p>Pedidos cobrados hoy: <b><?php echo (int) $ventas_cantidad; ?></b></p>
        <p>Insumos en stock mínimo: <b class="<?php echo $alertas > 0 ? 'rojo' : ''; ?>"><?php echo (int) $alertas; ?></b>
            <?php if ($alertas > 0) { ?><a href="index.php?ruta=inventario/alertas">Ver alertas</a><?php } ?></p>
    </div>
<?php } ?>

<div>
    <?php if ($rol === 'mesero' || $rol === 'administrador') { ?>
        <a href="index.php?ruta=pedidos/registrar">Nuevo pedido</a>
        <a href="index.php?ruta=pedidos/listar">Pedidos</a>
        <a href="index.php?ruta=mesas/listar">Mesas</a>
    <?php } ?>
    <?php if ($rol === 'cocinero' || $rol === 'administrador') { ?>
        <a href="index.php?ruta=cocina/pedidos">Pantalla de cocina</a>
    <?php } ?>
    <?php if ($rol === 'cajero' || $rol === 'administrador') { ?>
        <a href="index.php?ruta=pagos/pendientes">Cobros</a>
    <?php } ?>
    <?php if ($rol === 'administrador') { ?>
        <a href="index.php?ruta=reportes/ventas">Reporte de ventas</a>
        <a href="index.php?ruta=inventario/listar">Inventario</a>
    <?php } ?>
</div>
<?php require "../app/views/layouts/footer.php"; ?>
