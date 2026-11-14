<?php require "../app/views/layouts/header.php"; ?>
<?php $rol = rolUsuario(); ?>
<table>
    <tr><th>Mesa</th><th>Capacidad</th><th>Estado</th><th>Acciones</th></tr>
    <?php foreach ($mesas as $m) { ?>
        <tr>
            <td>Mesa <?php echo (int) $m['numero_mesa']; ?></td>
            <td><?php echo (int) $m['capacidad']; ?> personas</td>
            <td><?php echo e(textoEstado($m['estado'])); ?></td>
            <td>
                <?php if ($rol === 'mesero' || $rol === 'administrador') { ?>
                    <a href="index.php?ruta=pedidos/registrar&mesa=<?php echo $m['id_mesa']; ?>">Nuevo pedido</a>
                <?php } ?>
                <?php if ($rol === 'administrador') { ?>
                    <?php foreach (array('libre' => 'Libre', 'ocupada' => 'Ocupada', 'por_cobrar' => 'Por cobrar') as $est => $txt) { ?>
                        <?php if ($est !== $m['estado']) { ?>
                            <a href="index.php?ruta=mesas/estado&id=<?php echo $m['id_mesa']; ?>&estado=<?php echo $est; ?>"><?php echo $txt; ?></a>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<?php if ($rol === 'administrador') { ?>
    <h2>Agregar mesa</h2>
    <form method="post" action="index.php?ruta=mesas/guardar">
        <div>
            <div><label>Número de mesa</label><input name="numero_mesa" type="number" min="1" required></div>
            <div><label>Capacidad</label><input name="capacidad" type="number" min="1" required></div>
        </div>
        <p><button type="submit">Agregar</button></p>
    </form>
<?php } ?>
<?php require "../app/views/layouts/footer.php"; ?>
