<?php require "../app/views/layouts/header.php"; ?>
<div class="comprobante">
    <h2>RESTAURANTE L</h2>
    <p>COMPROBANTE DE PAGO</p>
    <p><b><?php echo e($venta['numero_comprobante']); ?></b></p>
    <p><?php echo e($venta['fecha_venta']); ?></p>
    <hr>
    <p>Pedido #<?php echo (int) $venta['id_pedido']; ?> - Mesa <?php echo (int) $venta['numero_mesa']; ?></p>
    <table>
        <?php foreach ($items as $i) { ?>
            <tr><td><?php echo (int) $i['cantidad']; ?> x <?php echo e($i['plato']); ?></td><td class="der"><?php echo number_format($i['subtotal'], 2); ?></td></tr>
        <?php } ?>
        <tr><td><b>TOTAL</b></td><td class="der"><b><?php echo soles($venta['total']); ?></b></td></tr>
        <tr><td>Método</td><td class="der"><?php echo e($metodos[$venta['metodo_pago']]); ?></td></tr>
        <tr><td>Recibido</td><td class="der"><?php echo number_format($venta['monto_recibido'], 2); ?></td></tr>
        <tr><td>Vuelto</td><td class="der"><?php echo number_format($venta['vuelto'], 2); ?></td></tr>
    </table>
    <p>Atendió: <?php echo e($venta['cajero_nombre'] . ' ' . $venta['cajero_apellido']); ?></p>
    <p><small>Comprobante interno simple. No es boleta ni factura electrónica (SUNAT).</small></p>
</div>
<div>
    <button onclick="window.print()">Imprimir</button>
    <a href="index.php?ruta=pagos/pendientes">Volver a cobros</a>
</div>
<?php require "../app/views/layouts/footer.php"; ?>
