<?php require "../app/views/layouts/header.php"; ?>

<?php if (count($alertas) === 0) { ?>
    <div class="alerta ok">Todos los insumos están por encima de su stock mínimo.</div>
<?php } else { ?>
    <div class="alerta aviso">Estos insumos llegaron al stock mínimo o están por debajo. Conviene reponerlos.</div>
    <table>
        <tr><th>Insumo</th><th class="der">Stock actual</th><th class="der">Stock mínimo</th></tr>
        <?php foreach ($alertas as $a) { ?>
            <tr>
                <td><?php echo e($a['nombre']); ?></td>
                <td class="der rojo"><?php echo cantidad($a['stock_actual']) . ' ' . e($a['unidad_medida']); ?></td>
                <td class="der"><?php echo cantidad($a['stock_minimo']) . ' ' . e($a['unidad_medida']); ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>
<p><a href="index.php?ruta=inventario/movimientos">Registrar ingreso</a> <a href="index.php?ruta=inventario/listar">Volver al inventario</a></p>
<?php require "../app/views/layouts/footer.php"; ?>
