<h2>Cobros</h2>

<?php if (isset($pedido)) { ?>

        <h3>Pedido #<?php echo $pedido['id_pedido']; ?> - Mesa <?php echo $pedido['mesa']; ?></h3>

    <?php if ($error != '') { ?>
        <p style="color:red"><?php echo $error; ?></p>
    <?php } ?>

    <table border="1" cellpadding="5">
        <tr><th>Plato</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
        <?php foreach ($pedido['items'] as $item) { ?>
            <tr>
                <td><?php echo $item['plato']; ?></td>
                <td><?php echo $item['cantidad']; ?></td>
                <td><?php echo $item['precio']; ?></td>
                <td><?php echo $item['cantidad'] * $item['precio']; ?></td>
            </tr>
        <?php } ?>
        <tr><th colspan="3">TOTAL</th><th>S/ <?php echo $pedido['total']; ?></th></tr>
    </table>

    <form method="post" action="index.php?ruta=pagos/cobrar&id=<?php echo $pedido['id_pedido']; ?>">
        <p>Método de pago:
            <select name="metodo">
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
                <option value="yape">Yape</option>
                <option value="plin">Plin</option>
            </select>
        </p>
        <p>Monto recibido: <input type="number" step="0.01" name="monto" value="<?php echo $pedido['total']; ?>"></p>
        <button type="submit">Confirmar pago</button>
    </form>
    <p><a href="index.php?ruta=pagos/listar">Volver</a></p>

<?php } else { ?>

        <table border="1" cellpadding="5">
        <tr><th>Pedido</th><th>Mesa</th><th>Total</th><th>Acción</th></tr>
        <?php foreach ($pedidos as $p) { ?>
            <tr>
                <td>#<?php echo $p['id_pedido']; ?></td>
                <td><?php echo $p['mesa']; ?></td>
                <td>S/ <?php echo $p['total']; ?></td>
                <td><a href="index.php?ruta=pagos/cobrar&id=<?php echo $p['id_pedido']; ?>">Cobrar</a></td>
            </tr>
        <?php } ?>
    </table>
    <?php if (count($pedidos) == 0) { ?>
        <p>No hay pedidos por cobrar.</p>
    <?php } ?>

<?php } ?>
