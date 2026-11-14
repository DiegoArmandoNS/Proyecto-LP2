<?php require "../app/views/layouts/header.php"; ?>
<p>
    Mesa <b><?php echo (int) $pedido['numero_mesa']; ?></b> |
    Mesero: <?php echo e($pedido['mesero_nombre'] . ' ' . $pedido['mesero_apellido']); ?> |
    <?php echo e($pedido['fecha_pedido']); ?> |
    Estado: <?php echo e(textoEstado($pedido['estado'])); ?>
</p>
<?php if ($pedido['observaciones']) { ?><p>Observaciones: <?php echo e($pedido['observaciones']); ?></p><?php } ?>
<?php if ($pedido['estado'] === 'cancelado') { ?><p class="rojo">Motivo de cancelación: <?php echo e($pedido['motivo_cancelacion']); ?></p><?php } ?>

<table>
    <tr><th>Plato</th><th class="der">Cantidad</th><th class="der">P. unitario</th><th class="der">Subtotal</th><th>Observación</th></tr>
    <?php foreach ($items as $i) { ?>
        <tr>
            <td><?php echo e($i['plato']); ?></td>
            <td class="der"><?php echo (int) $i['cantidad']; ?></td>
            <td class="der"><?php echo soles($i['precio_unitario']); ?></td>
            <td class="der"><?php echo soles($i['subtotal']); ?></td>
            <td><?php echo e($i['observaciones']); ?></td>
        </tr>
    <?php } ?>
    <tr><th colspan="3" class="der">TOTAL</th><th class="der"><?php echo soles($total); ?></th><th></th></tr>
</table>

<div>
    <?php if ($puedeEditar) { ?>
        <a href="index.php?ruta=pedidos/registrar&id=<?php echo $pedido['id_pedido']; ?>">Modificar pedido</a>
    <?php } ?>
    <a href="index.php?ruta=pedidos/listar">Volver</a>
</div>

<?php if ($puedeEditar) { ?>
    <h2>Cancelar pedido</h2>
    <form method="post" action="index.php?ruta=pedidos/cancelar" onsubmit="return confirm('¿Cancelar el pedido?')">
        <input type="hidden" name="id_pedido" value="<?php echo (int) $pedido['id_pedido']; ?>">
        <label>Motivo de la cancelación</label>
        <input name="motivo" required>
        <p><button type="submit">Cancelar pedido</button></p>
    </form>
<?php } ?>
<?php require "../app/views/layouts/footer.php"; ?>
