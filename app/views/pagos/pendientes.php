<?php require "../app/views/layouts/header.php"; ?>
<meta http-equiv="refresh" content="15">
<table>
    <tr><th>Pedido</th><th>Mesa</th><th>Mesero</th><th>Hora</th><th class="der">Total</th><th></th></tr>
    <?php foreach ($pedidos as $p) { ?>
        <tr>
            <td>#<?php echo $p['id_pedido']; ?></td>
            <td>Mesa <?php echo (int) $p['numero_mesa']; ?></td>
            <td><?php echo e($p['mesero_nombre'] . ' ' . $p['mesero_apellido']); ?></td>
            <td><?php echo e(substr($p['fecha_pedido'], 11, 5)); ?></td>
            <td class="der"><?php echo soles($p['total']); ?></td>
            <td><a href="index.php?ruta=pagos/registrar&id=<?php echo $p['id_pedido']; ?>">Cobrar</a></td>
        </tr>
    <?php } ?>
</table>
<?php if (count($pedidos) === 0) { ?><p>No hay pedidos listos para cobrar.</p><?php } ?>
<?php require "../app/views/layouts/footer.php"; ?>
