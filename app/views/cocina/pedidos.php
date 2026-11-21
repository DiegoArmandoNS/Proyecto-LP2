<?php require "../app/views/layouts/header.php"; ?>
<meta http-equiv="refresh" content="5">
<?php if (count($pedidos) === 0) { ?>
    <p>No hay pedidos pendientes. Esta pantalla se actualiza sola cada 5 segundos.</p>
<?php } ?>
<div class="cocina">
    <?php foreach ($pedidos as $p) { ?>
        <div>
            <b>Mesa <?php echo (int) $p['numero_mesa']; ?></b>
            Pedido #<?php echo $p['id_pedido']; ?> - <?php echo e(substr($p['fecha_pedido'], 11, 5)); ?><br>
            <?php echo e(textoEstado($p['estado'])); ?>
            <ul>
                <?php foreach ($p['items'] as $i) { ?>
                    <li><b><?php echo (int) $i['cantidad']; ?> x</b> <?php echo e($i['plato']); ?>
                        <?php if ($i['observaciones']) { ?><br><small>(<?php echo e($i['observaciones']); ?>)</small><?php } ?></li>
                <?php } ?>
            </ul>
            <?php if ($p['observaciones']) { ?><p><i>Nota: <?php echo e($p['observaciones']); ?></i></p><?php } ?>
            <?php if ($p['estado'] === 'pendiente') { ?>
                <a href="index.php?ruta=cocina/estado&id=<?php echo $p['id_pedido']; ?>&estado=en_preparacion">Empezar a preparar</a>
            <?php } else { ?>
                <a href="index.php?ruta=cocina/estado&id=<?php echo $p['id_pedido']; ?>&estado=listo">Marcar listo</a>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<?php require "../app/views/layouts/footer.php"; ?>
