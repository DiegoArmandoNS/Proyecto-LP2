<?php require "../app/views/layouts/header.php"; ?>

<p>
    <a href="index.php?ruta=inventario/formulario">Nuevo insumo</a>
    <a href="index.php?ruta=inventario/movimientos">Movimientos</a>
    <a href="index.php?ruta=inventario/alertas">Alertas de stock mínimo</a>
</p>

<table>
    <tr><th>Insumo</th><th>Unidad</th><th class="der">Stock actual</th><th class="der">Stock mínimo</th><th>Estado</th><th></th></tr>
    <?php foreach ($insumos as $i) { ?>
        <?php $bajo = $i['estado'] == 1 && $i['stock_actual'] <= $i['stock_minimo']; ?>

        <tr>
            <td><?php echo e($i['nombre']); ?></td>
            <td><?php echo e($i['unidad_medida']); ?></td>
            <td class="der <?php echo $bajo ? 'rojo' : ''; ?>"><?php echo cantidad($i['stock_actual']); ?></td>
            <td class="der"><?php echo cantidad($i['stock_minimo']); ?></td>
            <td><?php echo $bajo ? '<span class="rojo">Stock mínimo</span>' : ($i['estado'] == 1 ? 'Normal' : 'Inactivo'); ?></td>
            <td><a href="index.php?ruta=inventario/formulario&id=<?php echo $i['id_insumo']; ?>">Editar</a></td>
        </tr>

    <?php } ?>
</table>

<?php require "../app/views/layouts/footer.php"; ?>
