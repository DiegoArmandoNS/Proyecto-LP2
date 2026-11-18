<?php require "../app/views/layouts/header.php"; ?>
<p><a href="index.php?ruta=reportes/inventario&fecha=<?php echo e($fecha); ?>">Ver reporte de inventario</a></p>
<form method="get" action="index.php">
    <input type="hidden" name="ruta" value="reportes/ventas">
    <div><label>Fecha</label><input type="date" name="fecha" value="<?php echo e($fecha); ?>"></div>
    <div><button type="submit">Consultar</button></div>
</form>

<div>
    <p>Total vendido: <b><?php echo soles($reporte['total']); ?></b></p>
    <p>Pedidos cobrados: <b><?php echo (int) $reporte['cantidad']; ?></b></p>
    <p>Ticket promedio: <b><?php echo soles($reporte['promedio']); ?></b></p>
</div>

<h2>Platos más vendidos</h2>
<table>
    <tr><th>#</th><th>Plato</th><th class="der">Cantidad</th><th class="der">Importe</th></tr>
    <?php foreach ($reporte['platos'] as $n => $p) { ?>
        <tr><td><?php echo $n + 1; ?></td><td><?php echo e($p['nombre']); ?></td><td class="der"><?php echo (int) $p['cantidad']; ?></td><td class="der"><?php echo soles($p['importe']); ?></td></tr>
    <?php } ?>
</table>
<?php if (count($reporte['platos']) === 0) { ?><p>No hay ventas en esta fecha.</p><?php } ?>

<h2>Ventas por método de pago</h2>
<table>
    <tr><th>Método</th><th class="der">Ventas</th><th class="der">Total</th></tr>
    <?php foreach ($reporte['metodos'] as $m) { ?>
        <tr><td><?php echo e($m['nombre']); ?></td><td class="der"><?php echo (int) $m['ventas']; ?></td><td class="der"><?php echo soles($m['total']); ?></td></tr>
    <?php } ?>
</table>

<h2>Ventas por cajero</h2>
<table>
    <tr><th>Cajero</th><th class="der">Ventas</th><th class="der">Total</th></tr>
    <?php foreach ($reporte['cajeros'] as $c) { ?>
        <tr><td><?php echo e($c['nombre'] . ' ' . $c['apellido']); ?></td><td class="der"><?php echo (int) $c['ventas']; ?></td><td class="der"><?php echo soles($c['total']); ?></td></tr>
    <?php } ?>
</table>

<h2>Detalle de ventas</h2>
<table>
    <tr><th>Comprobante</th><th>Hora</th><th>Mesa</th><th>Método</th><th class="der">Total</th></tr>
    <?php foreach ($reporte['ventas'] as $v) { ?>
        <tr>
            <td><a href="index.php?ruta=pagos/comprobante&id=<?php echo $v['id_venta']; ?>"><?php echo e($v['numero_comprobante']); ?></a></td>
            <td><?php echo e(substr($v['fecha_venta'], 11, 5)); ?></td>
            <td><?php echo (int) $v['numero_mesa']; ?></td>
            <td><?php echo e($metodos[$v['metodo_pago']]); ?></td>
            <td class="der"><?php echo soles($v['total']); ?></td>
        </tr>
    <?php } ?>
</table>
<?php require "../app/views/layouts/footer.php"; ?>
