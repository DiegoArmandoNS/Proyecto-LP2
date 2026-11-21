<?php require "../app/views/layouts/header.php"; ?>
<p><a href="index.php?ruta=reportes/ventas&fecha=<?php echo e($fecha); ?>">Ver reporte de ventas</a></p>
<form method="get" action="index.php">
    <input type="hidden" name="ruta" value="reportes/inventario">
    <div><label>Fecha</label><input type="date" name="fecha" value="<?php echo e($fecha); ?>"></div>
    <div><button type="submit">Consultar</button></div>
</form>

<h2>Consumo de insumos del día</h2>
<table>
    <tr><th>Insumo</th><th class="der">Ingresos (compras)</th><th class="der">Consumo en pedidos</th><th class="der">Mermas</th></tr>
    <?php foreach ($reporte['resumen'] as $r) { ?>
        <tr>
            <td><?php echo e($r['nombre']); ?></td>
            <td class="der"><?php echo cantidad($r['ingresos']) . ' ' . e($r['unidad_medida']); ?></td>
            <td class="der"><?php echo cantidad($r['consumo']) . ' ' . e($r['unidad_medida']); ?></td>
            <td class="der"><?php echo cantidad($r['mermas']) . ' ' . e($r['unidad_medida']); ?></td>
        </tr>
    <?php } ?>
</table>
<?php if (count($reporte['resumen']) === 0) { ?><p>No hay movimientos en esta fecha.</p><?php } ?>

<h2>Movimientos del día</h2>
<table>
    <tr><th>Hora</th><th>Insumo</th><th>Tipo</th><th class="der">Cantidad</th><th>Motivo</th><th>Usuario</th></tr>
    <?php foreach ($reporte['movimientos'] as $m) { ?>
        <tr>
            <td><?php echo e(substr($m['fecha_movimiento'], 11, 5)); ?></td>
            <td><?php echo e($m['insumo']); ?></td>
            <td><?php echo e(ucfirst($m['tipo'])); ?></td>
            <td class="der"><?php echo cantidad($m['cantidad']) . ' ' . e($m['unidad_medida']); ?></td>
            <td><?php echo e($m['motivo']); ?></td>
            <td><?php echo e($m['usuario_nombre'] . ' ' . $m['usuario_apellido']); ?></td>
        </tr>
    <?php } ?>
</table>
<?php require "../app/views/layouts/footer.php"; ?>
