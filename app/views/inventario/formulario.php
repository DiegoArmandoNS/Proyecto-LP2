<?php require "../app/views/layouts/header.php"; ?>
<div>
    <?php if ($error !== '') { ?>
        <div class="alerta error"><?php echo e($error); ?></div>
    <?php } ?>
    <form method="post" action="index.php?ruta=inventario/guardar">
        <input type="hidden" name="id_insumo" value="<?php echo $insumo ? (int) $insumo['id_insumo'] : 0; ?>">
        <label>Nombre del insumo</label>
        <input name="nombre" value="<?php echo e(valor($datos, 'nombre')); ?>" required>
        <label>Unidad de medida (kg, litros, unidad...)</label>
        <input name="unidad_medida" value="<?php echo e(valor($datos, 'unidad_medida')); ?>" required>
        <?php if (!$insumo) { ?>
            <label>Stock inicial</label>
            <input name="stock_actual" type="number" step="0.001" min="0" value="<?php echo e(valor($datos, 'stock_actual') !== '' ? valor($datos, 'stock_actual') : '0'); ?>" required>
        <?php } else { ?>
            <p>Stock actual: <b><?php echo cantidad($insumo['stock_actual']); ?></b> (para cambiarlo usa Ingresos, Mermas o Ajustes en Movimientos)</p>
        <?php } ?>
        <label>Stock mínimo (para la alerta)</label>
        <input name="stock_minimo" type="number" step="0.001" min="0" value="<?php echo e(valor($datos, 'stock_minimo') !== '' ? valor($datos, 'stock_minimo') : '0'); ?>" required>
        <?php if ($insumo) { ?>
            <label>Estado</label>
            <select name="estado">
                <option value="1" <?php echo valor($datos, 'estado') == 1 ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?php echo valor($datos, 'estado') == 0 && valor($datos, 'estado') !== '' ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        <?php } ?>
        <p><button type="submit">Guardar</button> <a href="index.php?ruta=inventario/listar">Cancelar</a></p>
    </form>
</div>
<?php require "../app/views/layouts/footer.php"; ?>
