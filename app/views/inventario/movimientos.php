<?php require "../app/views/layouts/header.php"; ?>
<h2>Registrar movimiento</h2>
<form method="post" action="index.php?ruta=inventario/movimiento_guardar">
    <label>Insumo</label>
    <select name="id_insumo" required>
        <option value="">Elige un insumo</option>
        <?php foreach ($insumos as $i) { ?>
            <option value="<?php echo $i['id_insumo']; ?>"><?php echo e($i['nombre'] . ' (stock: ' . cantidad($i['stock_actual']) . ' ' . $i['unidad_medida'] . ')'); ?></option>
        <?php } ?>
    </select>
    <label>Tipo</label>
    <select name="tipo" required>
        <option value="ingreso">Ingreso (compra)</option>
        <option value="merma">Merma (pérdida o vencido)</option>
        <option value="ajuste">Ajuste (escribir el stock real contado)</option>
    </select>
    <label>Cantidad (en ajuste: el stock real)</label>
    <input name="cantidad" type="number" step="0.001" min="0" required>
    <label>Motivo</label>
    <input name="motivo" placeholder="Ej: compra del día, producto vencido">
    <p><button type="submit">Registrar</button></p>
</form>

<h2>Historial</h2>
<form method="get" action="index.php">
    <input type="hidden" name="ruta" value="inventario/movimientos">
    <div><label>Insumo</label>
        <select name="id_insumo"><option value="0">Todos</option>
            <?php foreach ($insumos as $i) { ?>
                <option value="<?php echo $i['id_insumo']; ?>" <?php echo $filtro_insumo == $i['id_insumo'] ? 'selected' : ''; ?>><?php echo e($i['nombre']); ?></option>
            <?php } ?>
        </select></div>
    <div><label>Tipo</label>
        <select name="tipo"><option value="">Todos</option>
            <?php foreach (array('ingreso', 'salida', 'merma', 'ajuste') as $t) { ?>
                <option value="<?php echo $t; ?>" <?php echo $filtro_tipo === $t ? 'selected' : ''; ?>><?php echo ucfirst($t); ?></option>
            <?php } ?>
        </select></div>
    <div><label>Fecha</label><input type="date" name="fecha" value="<?php echo e($filtro_fecha); ?>"></div>
    <div><button type="submit">Filtrar</button></div>
</form>
<table>
    <tr><th>Fecha</th><th>Insumo</th><th>Tipo</th><th class="der">Cantidad</th><th>Motivo</th><th>Usuario</th></tr>
    <?php foreach ($movimientos as $m) { ?>
        <tr>
            <td><?php echo e($m['fecha_movimiento']); ?></td>
            <td><?php echo e($m['insumo']); ?></td>
            <td><?php echo e(ucfirst($m['tipo'])); ?></td>
            <td class="der"><?php echo cantidad($m['cantidad']) . ' ' . e($m['unidad_medida']); ?></td>
            <td><?php echo e($m['motivo']); ?></td>
            <td><?php echo e($m['usuario_nombre'] . ' ' . $m['usuario_apellido']); ?></td>
        </tr>
    <?php } ?>
</table>
<?php if (count($movimientos) === 0) { ?><p>No hay movimientos con ese filtro.</p><?php } ?>
<?php require "../app/views/layouts/footer.php"; ?>
