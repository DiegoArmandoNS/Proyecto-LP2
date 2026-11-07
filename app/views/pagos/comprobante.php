<h2>Comprobante <?php echo $venta['numero']; ?></h2>
<p>Restaurante L - <?php echo $venta['fecha']; ?> - Mesa <?php echo $venta['mesa']; ?></p>

<table border="1" cellpadding="5">
    <?php foreach ($venta['items'] as $item) { ?>
        <tr>
            <td><?php echo $item['cantidad']; ?> x <?php echo $item['plato']; ?></td>
            <td><?php echo $item['cantidad'] * $item['precio']; ?></td>
        </tr>
    <?php } ?>
    <tr><th>TOTAL</th><th>S/ <?php echo $venta['total']; ?></th></tr>
</table>

<p>Método: <?php echo $venta['metodo']; ?></p>
<p>Recibido: S/ <?php echo $venta['recibido']; ?> - Vuelto: S/ <?php echo $venta['vuelto']; ?></p>
<p><small>Comprobante interno simple (no es boleta ni factura SUNAT).</small></p>

<p><a href="index.php?ruta=pagos/listar">Volver a cobros</a></p>
