<?php require "../app/views/layouts/header.php"; ?>
<meta http-equiv="refresh" content="15">
<?php if ($listos > 0) { ?>
    <div class="alerta aviso"><?php echo $listos; ?> pedido(s) listo(s) para llevar a la mesa.</div>
<?php } ?>
<form method="get" action="index.php">
    <input type="hidden" name="ruta" value="pedidos/listar">
    <div><label>Fecha</label><input type="date" name="fecha" value="<?php echo e($fecha); ?>"></div>
    <div><button type="submit">Ver</button> <a href="index.php?ruta=pedidos/registrar">Nuevo pedido</a></div>
</form>
<table>
    <tr><th>Pedido</th><th>Hora</th><th>Mesa</th><th>Mesero</th><th>Estado</th><th></th></tr>
    <?php foreach ($pedidos as $p) { ?>
        <tr>
            <td>#<?php echo $p['id_pedido']; ?></td>
            <td><?php echo e(substr($p['fecha_pedido'], 11, 5)); ?></td>
            <td>Mesa <?php echo (int) $p['numero_mesa']; ?></td>
            <td><?php echo e($p['mesero_nombre'] . ' ' . $p['mesero_apellido']); ?></td>
            <td><?php echo e(textoEstado($p['estado'])); ?></td>
            <td><a href="index.php?ruta=pedidos/detalle&id=<?php echo $p['id_pedido']; ?>">Ver</a></td>
        </tr>
    <?php } ?>
</table>
<?php if (count($pedidos) === 0) { ?><p>No hay pedidos en esta fecha.</p><?php } ?>
<?php require "../app/views/layouts/footer.php"; ?>
