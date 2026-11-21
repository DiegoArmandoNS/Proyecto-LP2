<?php require "../app/views/layouts/header.php"; ?>
<p>Mesa <b><?php echo (int) $pedido['numero_mesa']; ?></b> | Mesero: <?php echo e($pedido['mesero_nombre'] . ' ' . $pedido['mesero_apellido']); ?></p>
<table>
    <tr><th>Plato</th><th class="der">Cantidad</th><th class="der">P. unitario</th><th class="der">Subtotal</th></tr>
    <?php foreach ($items as $i) { ?>
        <tr>
            <td><?php echo e($i['plato']); ?></td>
            <td class="der"><?php echo (int) $i['cantidad']; ?></td>
            <td class="der"><?php echo soles($i['precio_unitario']); ?></td>
            <td class="der"><?php echo soles($i['subtotal']); ?></td>
        </tr>
    <?php } ?>
    <tr><th colspan="3" class="der">TOTAL</th><th class="der"><?php echo soles($total); ?></th></tr>
</table>

<form method="post" action="index.php?ruta=pagos/guardar">
    <input type="hidden" name="id_pedido" value="<?php echo (int) $pedido['id_pedido']; ?>">
    <label>Método de pago</label>
    <select name="metodo" required>
        <?php foreach ($metodos as $clave => $texto) { ?>
            <option value="<?php echo e($clave); ?>"><?php echo e($texto); ?></option>
        <?php } ?>
    </select>
    <label>Monto recibido (S/)</label>
    <input id="monto" name="monto" type="number" step="0.01" min="0" value="<?php echo number_format($total, 2, '.', ''); ?>" required>
    <p>Vuelto: <b id="vuelto">S/ 0.00</b></p>
    <p><button type="submit">Confirmar pago y emitir comprobante</button> <a href="index.php?ruta=pagos/pendientes">Volver</a></p>
</form>
<script>
var total = <?php echo json_encode((float) $total); ?>;
var monto = document.getElementById('monto');
var vuelto = document.getElementById('vuelto');
function calcular() {
    var dif = parseFloat(monto.value || 0) - total;
    vuelto.textContent = 'S/ ' + (dif > 0 ? dif : 0).toFixed(2);
}
monto.addEventListener('input', calcular);
calcular();
</script>
<?php require "../app/views/layouts/footer.php"; ?>
