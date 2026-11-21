<?php require "../app/views/layouts/header.php"; ?>
<p>Precio: <b><?php echo soles($plato['precio']); ?></b> |
    Porciones que se pueden preparar con el stock actual:
    <b><?php echo $porciones === null ? 'sin receta (sin límite)' : (int) $porciones; ?></b></p>
<table>
    <tr><th>Insumo</th><th class="der">Cantidad por plato</th><th class="der">Stock actual</th><th></th></tr>
    <?php foreach ($items as $i) { ?>
        <tr>
            <td><?php echo e($i['nombre']); ?></td>
            <td class="der"><?php echo cantidad($i['cantidad']) . ' ' . e($i['unidad_medida']); ?></td>
            <td class="der"><?php echo cantidad($i['stock_actual']) . ' ' . e($i['unidad_medida']); ?></td>
            <td><a href="index.php?ruta=carta/receta_quitar&id=<?php echo $i['id_receta_item']; ?>&id_plato=<?php echo $plato['id_plato']; ?>">Quitar</a></td>
        </tr>
    <?php } ?>
</table>
<?php if (count($items) === 0) { ?><p>Este plato todavía no tiene ingredientes.</p><?php } ?>
<h2>Agregar ingrediente</h2>
<form method="post" action="index.php?ruta=carta/receta_agregar">
    <input type="hidden" name="id_plato" value="<?php echo (int) $plato['id_plato']; ?>">
    <label>Insumo</label>
    <select name="id_insumo" required>
        <option value="">Elige un insumo</option>
        <?php foreach ($insumos as $s) { ?>
            <option value="<?php echo $s['id_insumo']; ?>"><?php echo e($s['nombre'] . ' (' . $s['unidad_medida'] . ')'); ?></option>
        <?php } ?>
    </select>
    <label>Cantidad que usa cada plato</label>
    <input name="cantidad" type="number" step="0.001" min="0.001" required>
    <p><button type="submit">Guardar ingrediente</button> <a href="index.php?ruta=carta/listar">Volver a la carta</a></p>
</form>
<?php require "../app/views/layouts/footer.php"; ?>
