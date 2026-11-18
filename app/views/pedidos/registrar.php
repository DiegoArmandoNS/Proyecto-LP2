<?php require "../app/views/layouts/header.php"; ?>
<?php $edicion = $pedido !== null; ?>
<?php if ($error !== '') { ?>
    <div class="alerta error"><?php echo e($error); ?></div>
<?php } ?>
<form method="post" action="index.php?ruta=pedidos/guardar">
    <input type="hidden" name="id_pedido" value="<?php echo $edicion ? (int) $pedido['id_pedido'] : 0; ?>">

    <?php if ($edicion) { ?>
        <p>Mesa <b><?php echo (int) $pedido['numero_mesa']; ?></b></p>
        <input type="hidden" name="id_mesa" value="<?php echo (int) $pedido['id_mesa']; ?>">
    <?php } else { ?>
        <label>Mesa</label>
        <select name="id_mesa" required>
            <option value="">Elige una mesa</option>
            <?php foreach ($mesas as $m) { ?>
                <option value="<?php echo $m['id_mesa']; ?>" <?php echo $mesa_elegida == $m['id_mesa'] ? 'selected' : ''; ?>>
                    Mesa <?php echo (int) $m['numero_mesa']; ?> (<?php echo e(textoEstado($m['estado'])); ?>)
                </option>
            <?php } ?>
        </select>
    <?php } ?>

    <table>
        <tr><th>Plato</th><th class="der">Precio</th><th>Disponibilidad</th><th>Cantidad</th><th>Observación del plato</th></tr>
        <?php foreach ($platos as $p) { ?>
            <?php
            $agotado = $p['porciones'] !== null && $p['porciones'] < 1;
            $cant = isset($cantidades[$p['id_plato']]['cantidad']) ? (int) $cantidades[$p['id_plato']]['cantidad'] : 0;
            $obs = isset($cantidades[$p['id_plato']]['observaciones']) ? $cantidades[$p['id_plato']]['observaciones'] : '';
            ?>
            <tr>
                <td><b><?php echo e($p['nombre']); ?></b><br><small><?php echo e($p['categoria']); ?></small></td>
                <td class="der"><?php echo soles($p['precio']); ?></td>
                <td>
                    <?php if ($agotado) { ?>
                        <span class="rojo">Sin insumos suficientes</span>
                    <?php } elseif ($p['porciones'] !== null) { ?>
                        Quedan <?php echo (int) $p['porciones']; ?> porciones
                    <?php } else { ?>
                        Disponible
                    <?php } ?>
                </td>
                <td><input type="number" min="0" max="50" name="items[<?php echo $p['id_plato']; ?>][cantidad]" value="<?php echo $cant; ?>" <?php echo $agotado && !$edicion ? 'disabled' : ''; ?>></td>
                <td><input name="items[<?php echo $p['id_plato']; ?>][observaciones]" value="<?php echo e($obs); ?>" placeholder="Ej: sin ají"></td>
            </tr>
        <?php } ?>
    </table>

    <label>Observaciones del pedido</label>
    <textarea name="observaciones" rows="2"><?php echo e($observaciones); ?></textarea>
    <p>
        <button type="submit"><?php echo $edicion ? 'Guardar cambios' : 'Confirmar y enviar a cocina'; ?></button>
        <a href="index.php?ruta=pedidos/listar">Cancelar</a>
    </p>
</form>
<?php require "../app/views/layouts/footer.php"; ?>
